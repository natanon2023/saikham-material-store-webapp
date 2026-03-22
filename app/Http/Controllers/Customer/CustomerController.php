<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductSet;
use App\Models\Project;
use App\Models\Customer;
use App\Models\AccessoryType;
use App\Models\AluminiumProfileType;
use App\Models\ExpenseType;
use App\Models\ProductSetName;
use App\Models\ProjectExpense;
use App\Models\Projectimages;
use App\Models\ProjectName;
use App\Models\ThaiAmphure;
use App\Models\ThaiProvince;
use App\Models\ThaiTambon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\AluminumSurfaceFinish;
use App\Models\GlassType;
use App\Models\ColourItem;
use App\Models\ConsumableType;
use App\Models\CustomerNeed;
use App\Models\ImageTypeName;
use App\Models\Material;
use App\Models\Price;
use App\Models\ProductSetItem;
use App\Models\ToolType;
use App\Models\MaterialLog;
use App\Models\Withdrawal;
use App\Models\WithdrawalItem;
use App\Models\ProjectIssue;
use App\Models\IssueImage;
use Carbon\Carbon;
use App\Models\AluminiumItem;
use App\Models\AluminiumLength;
use App\Models\GlassItem;
use App\Models\GlassSize;
use App\Models\AccessoryItem;
use App\Models\AssignedInstaller;
use App\Models\ToolItem;
use App\Models\ConsumableItem;
use App\Models\Dealer;
use App\Models\MaterialPrice;
use App\Models\Unit;
use App\Models\ProjectPurchase;
use App\Models\ProjectPurchaseItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\QuotationMaterial;
use App\Models\WithdrawalItemLog;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    public function publicPage()
    {
        $productset = Productset::all();
        return view('customer.publicpage',compact('productset'));
    }

    public function cakestatuspage(Request $request)
    {
        $phone = $request->phone; 
        
        $projects = []; 

        if ($phone != null) {
            $customer = Customer::where('phone', $phone)->first();
            if ($customer != null) {
                $projects = Project::where('customer_id', $customer->id)->orderBy('id', 'desc')->get();
            } else {
                return redirect()->back()->with('error', 'ไม่พบข้อมูลเบอร์โทรนี้');
            }
        }

        return view('customer.cakestatuspage', compact('projects', 'phone'));
    }

   
    public function projectDetail($id)
    {
        $project = Project::with([
            'customer.province',
            'customer.amphure',
            'customer.tambon',
            'projectname',
            'projectimage.imagetype',
            'customerneed.productset.productSetName',
            'projectexpenses.type',
            'quotation',        
            'installers',        
        ])->find($id);

        $statusesthiname = $this->getStatusName($project->status);

        $quotation = $project->quotation ?? null;

        $hasQuotation = $quotation !== null;
        $hasReceipt   = in_array($project->status, [
            'approved', 'material_planning', 'waiting_purchase',
            'ready_to_withdraw', 'materials_withdrawn', 'installing', 'completed',
        ]);

        return view('customer.projectdetail', compact(
            'project', 'statusesthiname', 'quotation', 'hasQuotation', 'hasReceipt'
        ));
    }

    public function addbiddocument($id)
    {
        $project = Project::with([
            'customer.province',
            'customer.amphure',
            'customer.tambon',
            'projectname',
            'projectexpenses.type',
            'customerneed.productset.productSetName',
            'customerneed.projectImage.imagetype',
            'quotation.items',
            'quotation.quotationMaterials'
        ])->find($id);

        $statusopenqtc = [
            'pending_quotation',
            'approved',
            'waiting_approval'
        ];

        return view('customer.bid.addbiddocument', compact('project','statusopenqtc'));
    }

    public function receipt($id)
    {
        $project = $this->getCalculatedProject($id);
        return view('customer.bid.receipt', compact('project'));
    }

    public function taxInvoice($id)
    {
        $project = $this->getCalculatedProject($id);
        return view('customer.bid.tax_invoice', compact('project'));
    }


    private function getCalculatedProject($id)
    {
        $project = Project::with([
            'customer.province',
            'customer.amphure',
            'customer.tambon',
            'projectname',
            'customerneed',
            'customerneed.productset.productSetName',
            'customerneed.productset.productsetitem',
            'projectexpenses',
            'projectexpenses.type',
            'customerneed.productset.productsetitem.material',
            'customerneed.productset.productsetitem.material.aluminiumItem.aluminiumType',
            'customerneed.productset.productsetitem.material.aluminiumItem.aluminumSurfaceFinish',
            'customerneed.productset.productsetitem.material.aluminiumItem.aluminiumLengths.price',
            'customerneed.productset.productsetitem.material.glassItem.glassType',
            'customerneed.productset.productsetitem.material.glassItem.colourItem',
            'customerneed.productset.productsetitem.material.glassItem.glassSize.price',
            'customerneed.productset.productsetitem.material.accessoryItem.accessoryType',
            'customerneed.productset.productsetitem.material.accessoryItem.aluminumSurfaceFinish',
            'customerneed.productset.productsetitem.material.accessoryItem.unit',
            'customerneed.productset.productsetitem.material.consumableItem.unit',
            'customerneed.productset.productsetitem.material.consumableItem.consumabletype',
            'customerneed.productset.productsetitem.material.toolItem.toolType',
            'customerneed.productset.productsetitem.material.price',
            'assignedSurveyor.profile',
            'quotation',
            'installers',
        ])->find($id);

        foreach ($project->customerneed as $need) {
            $m_w = $need->width / 100;
            $m_h = $need->height / 100;
            $needTotal = 0;
            $requestlent = max($m_w, $m_h);

            foreach ($need->productset->productsetitem as $item) {
                $material = $item->material;
                $type = $material->material_type;

                $selectprice = 0;
                $selectlot = '';
                $remark = '';
                $qtyuse = 0;
                $description = ''; 

                if ($type == 'อลูมิเนียม') {
                    $totalneedlen = ($m_w * 2) + ($m_h * 2);
                    $stock = Price::where('material_id', $material->id)
                        ->where('quantity', '>', 0)
                        ->whereHas('aluminiumlength', function ($show) use ($requestlent) {
                            $show->where('length_meter', '>=', $requestlent);
                        })
                        ->orderBy('id', 'asc')
                        ->first();

                    if ($stock) {
                        $len = $stock->aluminiumlength->length_meter;
                        $qtyuse = ceil($totalneedlen / $len);
                        $selectprice = $stock->price;
                        $selectlot = $stock->lot;
                        $item->calculated_price_id = $stock->id;
                        $remark = "ใช้อลูมิเนียมยาว {$len} ม. จำนวน {$qtyuse} เส้น";
                        
                        $aluName = $material->aluminiumItem->aluminiumType->name ?? '';
                        $aluColor = $material->aluminiumItem->aluminumSurfaceFinish->name ?? '';
                        $description = "อลูมิเนียม {$aluName} สี{$aluColor} (ความยาว {$len} ม.)";
                    } else {
                        $flatlen = 6;
                        $qtyuse = ceil($totalneedlen / $flatlen);
                        $selectprice = 300;
                        $selectlot = 'ไม่มีของ';
                        $remark = "ใช้ราคาเหมา 300 บ. ต่อ เส้น ({$flatlen} ม.) จำนวน {$qtyuse} เส้น";
                        $item->calculated_price_id = null;
                        
                        $aluName = $material->aluminiumItem->aluminiumType->name ?? '';
                        $description = "อลูมิเนียม {$aluName} (ราคาเหมา ความยาว {$flatlen} ม.)";
                    }
                } elseif ($type == 'กระจก') {
                    $stock = Price::where('material_id', $material->id)
                        ->where('quantity', '>', 0)
                        ->whereHas('glassSize', function ($q) use ($m_w, $m_h) {
                            $q->where('width_meter', '>=', $m_w)
                            ->where('length_meter', '>=', $m_h);
                        })
                        ->orderBy('id', 'asc')
                        ->first();

                    if ($stock && $stock->glassSize) {
                        $sheetW = $stock->glassSize->width_meter;
                        $sheetH = $stock->glassSize->length_meter;

                        $qtyuse      = 2;
                        $selectprice = $stock->price;
                        $selectlot   = $stock->lot;
                        $item->calculated_price_id = $stock->id;
                        $remark      = "ใช้กระจก {$sheetW}×{$sheetH} ม. จำนวน {$qtyuse} แผ่น (×2 กันแตก)";

                        $glassName  = $material->glassItem->glassType->name ?? '';
                        $glassColor = $material->glassItem->colourItem->name ?? '';
                        $description = "กระจก{$glassName} สี{$glassColor} (ขนาด {$sheetW}×{$sheetH} ม.)";

                    } else {
                        $flatW = 2;
                        $flatH = 2;
                        $qtyuse      = 2; 
                        $selectprice = 400;
                        $selectlot   = 'ไม่มีของ';
                        $item->calculated_price_id = null;
                        $remark      = "ใช้กระจกเหมา {$flatW}×{$flatH} ม. 400 บ./แผ่น จำนวน {$qtyuse} แผ่น (×2 กันแตก)";

                        $glassName   = $material->glassItem->glassType->name ?? '';
                        $description = "กระจก{$glassName} (ราคาเหมา {$flatW}×{$flatH} ม.)";
                    }
                } else {
                    $stock = Price::where('material_id', $material->id)->where('quantity', '>', 0)->orderBy('id', 'asc')->first();
                    if ($stock) {
                        $selectprice = $stock->price;
                        $selectlot = $stock->lot;
                        $qtyuse = 1;
                        $remark = '-';
                        $item->calculated_price_id = $stock->id;
                    } else {
                        $qtyuse = 1;
                        $selectprice = 100;
                        $selectlot = 'ไม่มีของ';
                        $remark = 'ใช้ราคาเหมา 100 บ.';
                        $item->calculated_price_id = null;
                    }
                    if ($type == 'อุปกรณ์เสริม') {
                        $accName = $material->accessoryItem->accessoryType->name ?? '';
                        $description = "อุปกรณ์เสริม: {$accName}";
                    } elseif ($type == 'วัสดุสิ้นเปลือง') {
                        $conName = $material->consumableItem->consumabletype->name ?? '';
                        $description = "วัสดุสิ้นเปลือง: {$conName}";
                    } elseif ($type == 'เครื่องมือช่าง') {
                        $toolName = $material->toolItem->toolType->name ?? '';
                        $description = "เครื่องมือช่าง: {$toolName}";
                    } else {
                        $description = "วัสดุอื่นๆ";
                    }
                }

                $total_item_price = $qtyuse * $selectprice;
                $item->calculated_description = $description;
                $item->calculated_material_type = $type;
                
                $item->calculated_lot = $selectlot;
                $item->calculated_unit_price = $selectprice;
                $item->calculated_qty = $qtyuse;
                $item->calculated_total = $total_item_price;
                $item->calculated_remark = $remark;

                $needTotal += $total_item_price;
            }

            $need->calculated_total = $needTotal;
        }

        return $project;
    }


    public function showcustomerproducts(){
        $productsets = ProductSet::with([
            'productSetName',
            'aluminumSurfaceFinish',
            'glasscolouritem',
            'glasstype',
            'productsetitem',
        ])->get();


        return view('customer.showcustomerproducts',compact('productsets'));
    }

    private function getStatusName($status) {
        return match ($status){
            'waiting_survey'      => 'รอสำรวจ',
            'pending_survey'      => 'ค้างสำรวจ',
            'surveying'           => 'กำลังสำรวจ',
            'pending_quotation'   => 'รอเสนอราคา',
            'waiting_approval'    => 'รออนุมัติ',
            'approved'            => 'อนุมัติแล้ว',
            'material_planning'   => 'วางแผนวัสดุ',
            'waiting_purchase'    => 'รอซื้อวัสดุ',
            'ready_to_withdraw'   => 'พร้อมเบิก',
            'materials_withdrawn' => 'เบิกวัสดุแล้ว',
            'installing'          => 'กำลังติดตั้ง',
            'completed'           => 'เสร็จสิ้น',
            'cancelled'           => 'ยกเลิก',
            default               => 'อื่นๆ'
        };
    }



    
    public function quotationPdf($id)
    {
        $project = Project::with([
            'customer.province', 'customer.amphure', 'customer.tambon',
            'projectname', 'projectexpenses.type', 'installers',
            'quotation.items', 'quotation.quotationMaterials',
        ])->find($id);

        $quotation = Quotation::where('project_id', $project->id)->latest()->first();

        if ($quotation) {
            $totalExpenses   = $quotation->total_expense_amount;
            $sumProductTotal = $quotation->total_product_amount;
            $totalLabor      = $quotation->total_labor_amount;
            $sevic           = $quotation->service_charge_amount;
            $sumincome       = $totalExpenses + $sumProductTotal + $totalLabor + $sevic;
            $pricevat        = $quotation->vat_amount;
            $sumvattotal     = $quotation->grand_total;
        } else {
            $totalExpenses   = $project->projectexpenses->sum('amount');
            $installerCount  = max($project->installers->count(), 1);
            $totalLabor      = $project->labor_cost_surveying
                            + ($project->estimated_work_days * $project->daily_labor_rate * $installerCount);
            $sumProductTotal = 0;
            $sumtotal        = $sumProductTotal + $totalExpenses + $totalLabor;
            $sevic           = $sumtotal * 0.20;
            $sumincome       = $sumtotal + $sevic;
            $pricevat        = $sumincome * 0.07;
            $sumvattotal     = $sumincome + $pricevat;
        }

        $installerCount = max($project->installers->count(), 1);

        $pdf = Pdf::loadView('customer.pdf.quotation', compact(
            'project', 'quotation',
            'totalExpenses', 'sumProductTotal', 'totalLabor',
            'sevic', 'sumincome', 'pricevat', 'sumvattotal', 'installerCount'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('ใบเสนอราคา-' . ($project->quotation_number ?? $project->id) . '.pdf');
    }


    public function receiptPdf($id)
    {
        $project = Project::with([
            'customer.province', 'customer.amphure', 'customer.tambon',
            'projectname', 'quotation',
        ])->find($id);

        $sumvattotal = $project->quotation->grand_total;
        $pricevat    = $project->quotation->vat_amount;
        $sumincome   = $sumvattotal - $pricevat;

        $pdf = Pdf::loadView('customer.pdf.receipt', compact(
            'project', 'sumvattotal', 'pricevat', 'sumincome'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('ใบเสร็จ-' . ($project->receipt_number ?? $project->id) . '.pdf');
    }


    public function taxInvoicePdf($id)
    {
        $project = Project::with([
            'customer.province', 'customer.amphure', 'customer.tambon',
            'projectname', 'quotation',
        ])->find($id);

        $sumvattotal = $project->quotation->grand_total;
        $pricevat    = $project->quotation->vat_amount;
        $sumincome   = $sumvattotal - $pricevat;

        $pdf = Pdf::loadView('customer.pdf.tax_invoice', compact(
            'project', 'sumvattotal', 'pricevat', 'sumincome'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('ใบกำกับภาษี-' . ($project->tax_invoice_number ?? $project->id) . '.pdf');
    }




    
    

    public function estimateForm($productset_id)
    {
        $productset = ProductSet::with([
            'productSetName',
            'aluminumSurfaceFinish',
            'glasscolouritem',
            'glasstype',
            'productsetitem.material.aluminiumItem.aluminiumType',
            'productsetitem.material.aluminiumItem.aluminumSurfaceFinish',
            'productsetitem.material.glassItem.glassType',
            'productsetitem.material.glassItem.colourItem',
            'productsetitem.material.accessoryItem.accessoryType',
            'productsetitem.material.consumableItem.consumabletype',
            'productsetitem.material.toolItem.toolType',
        ])->find($productset_id);

        if (!$productset) abort(404);

        return view('customer.estimate.form', compact('productset'));
    }

    public function estimateCalculate(Request $request, $productset_id)
    {
        $request->validate([
            'width'  => 'required|numeric|min:10|max:5000',
            'height' => 'required|numeric|min:10|max:5000',
        ]);

        $productset = ProductSet::with([
            'productSetName',
            'aluminumSurfaceFinish',
            'glasscolouritem',
            'glasstype',
            'productsetitem.material',
            'productsetitem.material.aluminiumItem.aluminiumType',
            'productsetitem.material.aluminiumItem.aluminumSurfaceFinish',
            'productsetitem.material.aluminiumItem.aluminiumLengths.price',
            'productsetitem.material.glassItem.glassType',
            'productsetitem.material.glassItem.colourItem',
            'productsetitem.material.glassItem.glassSize.price',
            'productsetitem.material.accessoryItem.accessoryType',
            'productsetitem.material.consumableItem.consumabletype',
            'productsetitem.material.toolItem.toolType',
            'productsetitem.material.price',
        ])->find($productset_id);

        $width       = (float) $request->width;
        $height      = (float) $request->height;
        $m_w         = $width  / 100;
        $m_h         = $height / 100;
        $requestlent = max($m_w, $m_h);

        $items     = [];
        $needTotal = 0;

        foreach ($productset->productsetitem as $item) {
            $material    = $item->material;
            $type        = $material->material_type;
            $selectprice = 0;
            $selectlot   = '';
            $remark      = '';
            $qtyuse      = 0;
            $description = '';
            $hasStock    = true;

            if ($type == 'อลูมิเนียม') {
                $totalneedlen = ($m_w * 2) + ($m_h * 2);

                $allMatchingStock = Price::where('material_id', $material->id)
                    ->where('quantity', '>', 0)
                    ->whereHas('aluminiumlength', fn($q) => $q->where('length_meter', '>=', $requestlent))
                    ->with('aluminiumlength')
                    ->orderBy('id', 'asc')
                    ->get();

                $stock = $allMatchingStock->first();

                if ($stock) {
                    $len            = $stock->aluminiumlength->length_meter;
                    $qtyuse         = ceil($totalneedlen / $len);
                    $totalAvailable = $allMatchingStock->sum('quantity');
                    $description    = ($material->aluminiumItem->aluminiumType->name ?? '') . ' สี ' . ($material->aluminiumItem->aluminumSurfaceFinish->name ?? '');

                    if ($totalAvailable >= $qtyuse) {
                        $selectprice = $stock->price;
                        $selectlot   = $stock->lot;
                        $hasStock    = true;
                        $remark      = "ใช้อลูมิเนียมยาว {$len} ม. จำนวน {$qtyuse} เส้น";
                    } else {
                        $selectprice = $stock->price;
                        $selectlot   = 'ราคาเหมา';
                        $hasStock    = false;
                        $remark      = "สต็อกมีแค่ {$totalAvailable} เส้น ต้องการ {$qtyuse} เส้น";
                    }
                } else {
                    $flatlen     = 6;
                    $qtyuse      = ceil($totalneedlen / $flatlen);
                    $selectprice = 300;
                    $selectlot   = 'ราคาเหมา';
                    $hasStock    = false;
                    $remark      = "ราคาเหมา 300 บ./เส้น ({$flatlen} ม.) จำนวน {$qtyuse} เส้น";
                    $description = ($material->aluminiumItem->aluminiumType->name ?? '') . ' สี ' . ($material->aluminiumItem->aluminumSurfaceFinish->name ?? '');
                }

            } elseif ($type == 'กระจก') {
                $qtyuse = 2; 
                $allMatchingStock = Price::where('material_id', $material->id)
                    ->where('quantity', '>', 0)
                    ->whereHas('glassSize', fn($q) => $q->where('width_meter', '>=', $m_w)->where('length_meter', '>=', $m_h))
                    ->with('glassSize')
                    ->orderBy('id', 'asc')
                    ->get();

                $stock = $allMatchingStock->first();

                if ($stock?->glassSize) {
                    $totalAvailable = $allMatchingStock->sum('quantity');
                    $sheetW         = $stock->glassSize->width_meter;
                    $sheetH         = $stock->glassSize->length_meter;
                    $description    = ($material->glassItem->glassType->name ?? '') . ' สี ' . ($material->glassItem->colourItem->name ?? '');

                    if ($totalAvailable >= $qtyuse) {
                        $selectprice = $stock->price;
                        $selectlot   = $stock->lot;
                        $hasStock    = true;
                        $remark      = "กระจก {$sheetW}×{$sheetH} ม. จำนวน {$qtyuse} แผ่น (×2 กันแตก)";
                    } else {
                        $selectprice = $stock->price;
                        $selectlot   = 'ราคาเหมา';
                        $hasStock    = false;
                        $remark      = "สต็อกมีแค่ {$totalAvailable} แผ่น ต้องการ {$qtyuse} แผ่น";
                    }
                } else {
                    $selectprice = 400;
                    $selectlot   = 'ราคาเหมา';
                    $hasStock    = false;
                    $remark      = "ราคาเหมา 400 บ./แผ่น จำนวน {$qtyuse} แผ่น (×2 กันแตก)";
                    $description = ($material->glassItem->glassType->name ?? '') . ' สี ' . ($material->glassItem->colourItem->name ?? '');
                }

            } else {
                $allStock = Price::where('material_id', $material->id)
                    ->where('quantity', '>', 0)
                    ->orderBy('id', 'asc')
                    ->get();
                $stock          = $allStock->first();
                $qtyuse         = 1;
                $totalAvailable = $allStock->sum('quantity');

                if ($stock && $totalAvailable >= $qtyuse) {
                    $selectprice = $stock->price;
                    $selectlot   = $stock->lot;
                    $hasStock    = true;
                    $remark      = '-';
                } else {
                    $selectprice = 100;
                    $selectlot   = 'ราคาเหมา';
                    $hasStock    = false;
                    $remark      = 'ราคาเหมา 100 บ.';
                }

                $description = match($type) {
                    'อุปกรณ์เสริม'   => $material->accessoryItem->accessoryType->name ?? '-',
                    'วัสดุสิ้นเปลือง' => $material->consumableItem->consumabletype->name ?? '-',
                    'เครื่องมือช่าง'  => $material->toolItem->toolType->name ?? '-',
                    default           => $material->name ?? '-',
                };
            }

            $rowTotal   = $qtyuse * $selectprice;
            $needTotal += $rowTotal;

            $items[] = [
                'type'        => $type,
                'description' => $description,
                'lot'         => $selectlot,
                'qty'         => $qtyuse,
                'unit_price'  => $selectprice,
                'total'       => $rowTotal,
                'remark'      => $remark,
                'has_stock'   => $hasStock,
            ];
        }

        $serviceCharge = $needTotal * 0.20;
        $beforeVat     = $needTotal + $serviceCharge;
        $vat           = $beforeVat * 0.07;
        $grandTotal    = $beforeVat + $vat;

        $result = [
            'productset'    => $productset,
            'width'         => $width,
            'height'        => $height,
            'items'         => $items,
            'subtotal'      => $needTotal,
            'serviceCharge' => $serviceCharge,
            'beforeVat'     => $beforeVat,
            'vat'           => $vat,
            'grandTotal'    => $grandTotal,
        ];

        return view('customer.estimate.result', compact('result'));
    }

    



    

   





    
}
