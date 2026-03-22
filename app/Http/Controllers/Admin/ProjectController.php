<?php

namespace App\Http\Controllers\Admin;

use App;
use App\Http\Controllers\Controller;
use App\Models\AccessoryType;
use App\Models\AluminiumProfileType;
use App\Models\Customer;
use App\Models\ExpenseType;
use App\Models\ProductSetName;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\Projectimages;
use App\Models\ProjectName;
use App\Models\ThaiAmphure;
use App\Models\ThaiProvince;
use App\Models\ThaiTambon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AluminumSurfaceFinish;
use App\Models\GlassType;
use App\Models\ColourItem;
use App\Models\ConsumableType;
use App\Models\CustomerNeed;
use App\Models\ImageTypeName;
use App\Models\Material;
use App\Models\Price;
use App\Models\ProductSet;
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
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class ProjectController extends Controller
{
    public function index($id)
    {
        $project = Project::withTrashed()->with([
            'customer.province',
            'customer.amphure',
            'customer.tambon',
            'createdBy',
            'updatedBy',
            'assignedSurveyor',
            'assignedInstaller',
            'projectname',
            'projectexpenses'

        ])->find($id);

        $statusColors = [
            'pending_survey' => ['#D4AF37', 'นัดสำรวจ'],
            'waiting_survey' => ['#FF8C00', 'รอวันสำรวจ'],
            'surveying' => ['#1E90FF', 'กำลังสำรวจ'],
            'pending_quotation' => ['#E91E63', 'รอเสนอราคา'],
            'waiting_approval' => ['#9C27B0', 'รออนุมัติ'],
            'approved' => ['#78d37b', 'อนุมัติและชำระเงินแล้ว'],
            'material_planning' => ['#00CED1', 'วางแผนวัสดุ'],
            'waiting_purchase' => ['#FF4500', 'รอสั่งซื้อ'],
            'ready_to_withdraw' => ['#008080', 'พร้อมเบิก'],
            'materials_withdrawn' => ['#8B4513', 'เบิกวัสดุแล้ว'],
            'installing' => ['#4CAF50', 'กำลังติดตั้ง'],
            'completed' => ['#708090', 'เสร็จสิ้น'],
            'cancelled' => ['#DC143C', 'ยกเลิก']
        ];

        $currentStatus = $statusColors[$project->status ] ?? ['#ffff', 'ไม่ระบุ'];



        return view("admin.projects.index", compact('project', 'statusColors', 'currentStatus'));
    }

    public function formprojectexpense($id)
    {
        $project = Project::find($id);
        $expense = ExpenseType::all();
        return view('admin.projects.projectexpense.formprojectexpense', compact('expense', 'project'));
    }

    public function createprojectexpense(Request $request)
    {

        $project = Project::find($request->project_id);
        ProjectExpense::create([
            'project_id' => $project->id,
            'expense_type_id' => $request->expense_type_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'created_by' => Auth::user()->id
        ]);

        return redirect()->route('admin.projects.expensedetail', $project->id)->with('success', 'เพิ่มรายการค่าใช้จ่ายสำเร็จ');
    }

    public function formeditProjectexpense($id)
    {
        $projectexpense = ProjectExpense::with([
            'type',
            'creator'
        ])->find($id);
        $expense = ExpenseType::all();
        return view('admin.projects.projectexpense.edit.formeditProjectexpense', compact('projectexpense', 'expense'));
    }

    public function editprojectexpense(Request $request)
    {
        $projectExpense = ProjectExpense::find($request->id);

        $project = Project::find($request->project_id);

        $projectExpense->update([
            'project_id' => $project->id,
            'expense_type_id' => $request->expense_type_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'created_by' => Auth::id(),
        ]);


        return redirect()->route('admin.projects.expensedetail', $project->id)->with('success', 'แก้ไขรายการค่าใช้จ่ายเรียบร้อยแล้ว');
    }

    public function deleteprojectexpense($id)
    {
        $projectexpense = ProjectExpense::find($id);

        $project = Project::find($projectexpense->project_id);

        $projectexpense->delete();

        return redirect()->route('admin.projects.expensedetail', $project->id)->with('success', 'ลบข้อมูลค่าใช้จ่ายสำเร็จ');
    }


    public function formexpense($id)
    {
        $project = Project::find($id);
        $expens = ExpenseType::withTrashed()->get();
        return view('admin.projects.projectexpense.formexpense', compact('project', 'expens'));
    }

    public function createexpense(Request $request)
    {
        ExpenseType::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'เพิ่มชื่อรายการค่าใช้สำเร็จ');
    }

    public function updateexpense(Request $request, $id)
    {
        $expense = ExpenseType::find($id);

        $expense->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'อัปเดตข้อมูลสำเร็จ');
    }

    public function deleteexpense($id)
    {
        $expense = ExpenseType::find($id);
        $expense->delete();

        return redirect()->back()->with('success', 'ลบรายการค่าใช้จ่ายสำเร็จ');
    }

    public function restoreexpense($id)
    {
        $expense = ExpenseType::withTrashed()->find($id);
        $expense->restore();

        return redirect()->back()->with('success', 'กู้คืนรายการค่าใช้จ่ายสำเร็จ');
    }



    public function expensedetail($id)
    {
        $project = Project::with([
            'customer.province',
            'customer.amphure',
            'customer.tambon',
            'createdBy',
            'updatedBy',
            'assignedSurveyor',
            'assignedInstaller',
            'projectname',
            'projectexpenses.type',
            'projectexpenses.creator'

        ])->find($id);
        return view('admin.projects.projectexpense.expensedetail', compact('project'));
    }

    public function formnewcustomer()
    {
        $customerall = Customer::withTrashed()->get();
        $province = ThaiProvince::orderBy('name_th', 'ASC')->get();

        return view("admin.projects.pendingsurvey.formnewcustomer", compact('province', 'customerall'));
    }

    public function createnewcustomer(Request $request)
    {
        Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'house_number' => $request->house_number,
            'village' => $request->village,
            'alley' => $request->alley,
            'road' => $request->road,
            'province_id' => $request->province_id,
            'amphure_id' => $request->amphure_id,
            'tambon_id' => $request->tambon_id,
            'zip_code' => $request->zip_code,
            'house_name' => $request->house_name,
            'note' => $request->note,
            'gender' => $request->gender,
            'prefix' => $request->prefix,
            'tax_id_number' => $request->tax_id_number
        ]);

        return back()->with('success', 'บันทึกข้อมูลลูกค้าใหม่สำเร็จ');
    }

    public function editcustomer($id)
    {
        $customer = Customer::with([
            'province',
            'amphure',
            'tambon',
        ])->find($id);

        $province = ThaiProvince::orderBy('name_th', 'ASC')->get();
        $amphure = ThaiAmphure::where('province_id', $customer->province_id)->orderBy('name_th', 'ASC')->get();
        $tambon = ThaiTambon::where('amphure_id', $customer->amphure_id)->orderBy('name_th', 'ASC')->get();

        return view('admin.projects.pendingsurvey.editcustomer', compact('customer', 'province', 'amphure', 'tambon'));
    }



    public function updatecustomer(Request $request, $id)
    {

        $customer = Customer::find($id);

        $customer->update([
            'prefix' => $request->prefix,
            'gender' => $request->gender,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'house_number' => $request->house_number,
            'village' => $request->village,
            'alley' => $request->alley,
            'road' => $request->road,
            'province_id' => $request->province_id,
            'amphure_id' => $request->amphure_id,
            'tambon_id' => $request->tambon_id,
            'zip_code' => $request->zip_code,
            'house_name' => $request->house_name,
            'tax_id_number' => $request->tax_id_number
        ]);

        return redirect()->route('admin.projects.formnewcustomer')->with('success', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function projecteditcustomer($id)
    {
        $customer = Customer::with([
            'projects',
            'province',
            'amphure',
            'tambon',
        ])->find($id);

        $province = ThaiProvince::orderBy('name_th', 'ASC')->get();
        $amphure = ThaiAmphure::where('province_id', $customer->province_id)->orderBy('name_th', 'ASC')->get();
        $tambon = ThaiTambon::where('amphure_id', $customer->amphure_id)->orderBy('name_th', 'ASC')->get();

        return view('admin.projects.pendingsurvey.projecteditcustomer', compact('customer', 'province', 'amphure', 'tambon'));
    }

    public function updatecustomerproject(Request $request, $id)
    {

        $customer = Customer::find($id);

        $customer->update([
            'prefix' => $request->prefix,
            'gender' => $request->gender,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'house_number' => $request->house_number,
            'village' => $request->village,
            'alley' => $request->alley,
            'road' => $request->road,
            'province_id' => $request->province_id,
            'amphure_id' => $request->amphure_id,
            'tambon_id' => $request->tambon_id,
            'zip_code' => $request->zip_code,
            'house_name' => $request->house_name,
            'tax_id_number' => $request->tax_id_number
        ]);

        $projectId = $request->input('project_id');

        return redirect()->route('admin.projects.alldetail',$projectId)->with('success', 'แก้ไขข้อมูลเรียบร้อย');
    }


    public function deletecustomer($id)
    {
        Customer::find($id)->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อย (สามารถกู้คืนได้)');
    }

    public function restorecustomer($id)
    {
        Customer::withTrashed()->find($id)->restore();
        return redirect()->back()->with('success', 'กู้คืนข้อมูลเรียบร้อย');
    }


    public function getAmphures($id)
    {
        $amphures = ThaiAmphure::where('province_id', $id)->orderBy('name_th', 'ASC')->get();
        return response()->json($amphures);
    }

    public function getTambons($id)
    {
        $tambons = ThaiTambon::where('amphure_id', $id)->select('id', 'name_th', 'zip_code')->orderBy('name_th', 'ASC')->get();
        return response()->json($tambons);
    }


    public function formprojectname()
    {
        $projectnameall = ProjectName::withTrashed()->get();
        return view('admin.projects.pendingsurvey.formprojectname', compact('projectnameall'));
    }

    public function createprojectname(Request $request)
    {
        ProjectName::create([
            'name' => $request->name
        ]);
        return redirect()->back()->with('success', 'เพิ่มชื่อโครงการสำเร็จ');
    }

    public function updateprojectname(Request $request, $id)
    {
        $project = ProjectName::find($id);

        if ($project) {
            $project->update([
                'name' => $request->name
            ]);
        }

        return redirect()->back()->with('success', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function deleteprojectname($id)
    {
        $project = ProjectName::find($id);
        if ($project) {
            $project->delete();
        }
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อย');
    }

    public function restoreprojectname($id)
    {
        $project = ProjectName::withTrashed()->find($id);
        if ($project) {
            $project->restore();
        }
        return redirect()->back()->with('success', 'กู้คืนข้อมูลเรียบร้อย');
    }



    public function updatestatuswaiting_survey(Request $request)
    {
        $project = Project::find($request->id);

        $project->update([
            'status' => 'waiting_survey'
        ]);

        return redirect()->route('admin.projects.index', $project->id)->with('success', 'บันทึกข้อมูลและอัปเดตสถานะสำเร็จ');
    }

    public function formpendingsurvey()
    {
        $projectname = ProjectName::all();
        $customer = Customer::all();
        $technician = User::where('role', 'technician')->get();
        return view("admin.projects.pendingsurvey.formpendingsurvey", compact('customer', 'technician', 'projectname'));
    }



    public function pendingsurvey(Request $request)
    {
        $prefix = 'INV' . date('ym');
        $lastProject = Project::where('tax_invoice_number', 'LIKE', $prefix . '%')->orderBy('id', 'desc')->first();

        $prefixqt = 'QT' . date('ym');
        $lastProjectqt = Project::where('quotation_number', 'LIKE', $prefixqt . '%')->orderBy('id', 'desc')->first();

        $prefixrc = 'RC' . date('ym');
        $lastProjectrc = Project::where('receipt_number', 'LIKE', $prefixrc . '%')->orderBy('id', 'desc')->first();

        if ($lastProject && $lastProject->tax_invoice_number) {
            $lastNumber = (int) substr($lastProject->tax_invoice_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        if ($lastProjectqt && $lastProjectqt->quotation_number) {
            $lastNumber = (int) substr($lastProjectqt->quotation_number, -4);
            $nextNumberqt = $lastNumber + 1;
        } else {
            $nextNumberqt = 1;
        }

        if ($lastProjectrc && $lastProjectrc->receipt_number) {
            $lastNumber = (int) substr($lastProjectrc->receipt_number, -4);
            $nextNumberrc = $lastNumber + 1;
        } else {
            $nextNumberrc = 1;
        }



        $autoTaxInvoiceNumber = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $autoprefixqt = $prefixqt . str_pad($nextNumberqt, 4, '0', STR_PAD_LEFT);
        $autoprefixrc = $prefixrc . str_pad($nextNumberrc, 4, '0', STR_PAD_LEFT);

        $project = Project::create([
            'project_name_id' => $request->project_name_id,
            'customer_id' => $request->customer_id,
            'assigned_surveyor_id' => $request->assigned_surveyor_id,
            'survey_date' => $request->survey_date,
            'note' => $request->note,
            'labor_cost_surveying' => $request->labor_cost_surveying,
            'tax_invoice_number' => $autoTaxInvoiceNumber,
            'quotation_number' => $autoprefixqt,
            'receipt_number' => $autoprefixrc,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.projects.index', $project->id)->with('success', 'เพิ่มโครงการใหม่และนัดหมายการสำรวจหน้างานสำเร็จ');
    }


    public function formsurveying($id)
    {

        $project = Project::with([
            'customer.province',
            'customer.amphure',
            'customer.tambon',
            'createdBy',
            'updatedBy',
            'assignedSurveyor',
            'assignedInstaller',
            'projectname',
            'projectimage.imagetype',
            'customerneed.creator',
            'customerneed.productset.productSetName','installers','customerneed.productset.productsetitem.material',
        ])->find($id);

       

        $technician = User::where('role', 'technician')->get();

        return view('admin.projects.survey.surveying', compact('project','technician'));
    }

    public function formcustomerneed($id)
    {
        $project = Project::find($id);
        $productset = ProductSet::with([
            'productSetName',
            'aluminumSurfaceFinish',
            'glasscolouritem'
        ])->get();

        $projectimg = Projectimages::where('project_id', $id)->get();

        return view('admin.projects.survey.formcustomerneed', compact('project', 'productset', 'projectimg'));
    }


    public function addcustomerneed(Request $request)
    {

        $project = Project::find($request->project_id);
        $file = $request->file('installation_image');
        $imageData = file_get_contents($file->getRealPath());

        CustomerNeed::create([
            'project_id' => $project->id,
            'product_set_id' => $request->product_set_id,
            'location' => $request->location,
            'quantity' => 1,
            'height' => $request->height,
            'width' => $request->width,
            'installation_image' => $imageData,
            'note_need' => $request->note_need,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.projects.formsurveying', $project->id)->with('success', 'เพิ่มรายการความต้องการลูกค้าสำเร็จ');
    }

    public function formcustomerneeddetial($id)
    {
        $project = Project::find($id);
        $productset = ProductSet::with([
            'productSetName'
        ])->get();

        $projectimg = Projectimages::where('project_id', $id)->get();

        return view('admin.projects.survey.formcustomerneeddetial', compact('project', 'productset', 'projectimg'));
    }


    public function addcustomerneeddetial(Request $request)
    {

        $project = Project::find($request->project_id);
        $file = $request->file('installation_image');
        $imageData = file_get_contents($file->getRealPath());

        CustomerNeed::create([
            'project_id' => $project->id,
            'product_set_id' => $request->product_set_id,
            'location' => $request->location,
            'quantity' => 1,
            'height' => $request->height,
            'width' => $request->width,
            'note_need' => $request->note_need,
            'installation_image' => $imageData,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.projects.alldetail', $project->id)->with('success', 'เพิ่มรายการความต้องการลูกค้าสำเร็จ');
    }

    public function editformcustomerneed($id)
    {

        $customerNeed = CustomerNeed::find($id);

        $project = Project::find($customerNeed->project_id);

        $productset = ProductSet::all();
        $projectimg = Projectimages::where('project_id', $project->id)->get();

        return view('admin.projects.survey.editformcustomerneed', compact('customerNeed', 'project', 'productset', 'projectimg'));
    }

    public function updatecustomerneed(Request $request, $id)
    {
        $customerNeed = CustomerNeed::find($id);

        $updateData = [
            'product_set_id' => $request->product_set_id,
            'location'       => $request->location,
            'height' => $request->height,
            'width' => $request->width,
            'note_need' => $request->note_need,
            'quantity'       => 1,
        ];

        if ($request->hasFile('installation_image')) {
            $file = $request->file('installation_image');
            $updateData['installation_image'] = file_get_contents($file->getRealPath());
        }

        $customerNeed->update($updateData);

        return redirect()->back()->with('success', 'แก้ไขรายการความต้องการลูกค้าสำเร็จ');
    }


    public function deletecustomerneed($id)
    {
        $customerneed = CustomerNeed::find($id);

        $customerneed->delete();

        return redirect()->back()->with('success', 'ลบรายการความต้องการสำเร็จ');
    }





    public function formprojectimage($id)
    {
        $project = Project::find($id);
        $imgtypename = ImageTypeName::all();

        return view('admin.projects.survey.formprojectimage', compact('project', 'imgtypename'));
    }

    public function createprojectimage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_path'  => 'nullable|image|max:10240'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'ขนาดรูปภาพใหญ่เกิน 10MB กรุณาเลือกรูปที่มีขนาดเล็กลง');
        }

        $project = Project::find($request->project_id);
        $imageData = null; 

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');

            if (!$file->isValid()) {
                return redirect()->back()->with('error', 'ไฟล์รูปภาพมีปัญหาหรือมีขนาดใหญ่เกินขีดจำกัดของระบบ กรุณาลองใหม่อีกครั้ง');
            }

            $source = @imagecreatefromstring(file_get_contents($file->getRealPath()));
            
            if (!$source) {
                return redirect()->back()->with('error', 'ไฟล์รูปภาพไม่รองรับหรือไฟล์เสีย กรุณาเปลี่ยนรูปใหม่');
            }

            $width  = imagesx($source);
            $height = imagesy($source);

            if ($width > 1920) {
                $newWidth  = 1920;
                $newHeight = (int) (($height / $width) * 1920); 

                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                $source = $resized;
            }

            ob_start();
            imagejpeg($source, null, 80);
            $imageData = ob_get_clean(); 

            imagedestroy($source);
        }

        Projectimages::create([
            'project_id'  => $project->id,
            'image_path'  => $imageData, 
            'image_type'  => $request->image_type,
            'description' => $request->description
        ]);

        return redirect()->route('admin.projects.formsurveying', $project->id)->with('success', 'เพิ่มรูปภาพโครงการสำเร็จ');
    }

    public function formprojectimagedetail($id)
    {
        $project = Project::find($id);
        $imgtypename = ImageTypeName::all();

        return view('admin.projects.survey.formprojectimagedetail', compact('project', 'imgtypename'));
    }


    public function createprojectimagedetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_path'  => 'nullable|image|max:10240'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'ขนาดรูปภาพใหญ่เกิน 10MB กรุณาเลือกรูปที่มีขนาดเล็กลง');
        }

        $project = Project::find($request->project_id);
        $imageData = null; 

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');

            if (!$file->isValid()) {
                return redirect()->back()->with('error', 'ไฟล์รูปภาพมีปัญหาหรือมีขนาดใหญ่เกินขีดจำกัดของระบบ กรุณาลองใหม่อีกครั้ง');
            }

            $source = @imagecreatefromstring(file_get_contents($file->getRealPath()));
            
            if (!$source) {
                return redirect()->back()->with('error', 'ไฟล์รูปภาพไม่รองรับหรือไฟล์เสีย กรุณาเปลี่ยนรูปใหม่');
            }

            $width  = imagesx($source);
            $height = imagesy($source);

            if ($width > 1920) {
                $newWidth  = 1920;
                $newHeight = (int) (($height / $width) * 1920); 

                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                $source = $resized;
            }

            ob_start();
            imagejpeg($source, null, 80);
            $imageData = ob_get_clean(); 

            imagedestroy($source);
        }

        Projectimages::create([
            'project_id'  => $project->id,
            'image_path'  => $imageData, 
            'image_type'  => $request->image_type,
            'description' => $request->description
        ]);

        return redirect()->route('admin.projects.alldetail', $project->id)->with('success', 'เพิ่มรูปภาพโครงการสำเร็จ');
    }

    

    public function deleteprojectimage($id)
    {
        $projectimge = Projectimages::find($id);

        $projectimge->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลภาพสำเร็จ');
    }


    public function formeditprojectimage($id)
    {

        $projectImage = Projectimages::find($id);
        $imgtypename = ImageTypeName::all();

        return view('admin.projects.survey.editprojectimage', compact('projectImage', 'imgtypename'));
    }

    public function updateprojectimage(Request $request, $id)
    {
        $projectImage = Projectimages::find($id);
        $projectImage->image_type = $request->image_type;
        $projectImage->description = $request->description;

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $imageData = file_get_contents($file->getRealPath());
            $projectImage->image_path = $imageData;
        }

        $projectImage->save();

        return redirect()->route('admin.projects.formsurveying', $projectImage->project_id)->with('success', 'แก้ไขรูปภาพสำเร็จ');
    }

    public function productsetdetail()
    {
        $productset = ProductSet::with([
            'productSetName',
            'glasscolouritem',
            'aluminumSurfaceFinish',
            'glasstype'
        ])->get();

        return view('admin.projects.productset.productsetdetail', compact('productset'));
    }




    public function formproductset()
    {
        $productsetname = ProductSetName::all();
        $aluminumsurfacefinish = AluminumSurfaceFinish::all();
        $glasstype = GlassType::all();
        $glasscolouritem = ColourItem::all();

        return view('admin.projects.productset.formproductset', compact('productsetname', 'aluminumsurfacefinish', 'glasstype', 'glasscolouritem'));
    }

    public function createproductset(Request $request)
    {

        $file = $request->file('product_image');

        $imagedata = file_get_contents($file->getRealPath());

        $productset = ProductSet::create([
            'product_set_name_id' => $request->product_set_name_id,
            'product_image' => $imagedata,
            'detail' => $request->detail,
            'aluminum_surface_finish_id' => $request->aluminum_surface_finish_id,
            'glass_colouritem_id' => $request->glass_colouritem_id,
            'glasstype_id' => $request->glasstype_id,
            'created_by' => Auth::user()->id
        ]);

        return redirect()->route('admin.projects.formaddproductsetitem', $productset->id)->with('success', 'บันทึกข้อมูลเบื้องต้นสำเร็จ');
    }


    public function formeditproductset($id)
    {

        $productset = ProductSet::find($id);

        $productsetname = ProductSetName::all();
        $aluminumsurfacefinish = AluminumSurfaceFinish::all();
        $glasstype = GlassType::all();
        $glasscolouritem = ColourItem::all();

        return view('admin.projects.productset.formeditproductset', compact('productset', 'productsetname', 'aluminumsurfacefinish', 'glasstype', 'glasscolouritem'));
    }

    public function editproductset(Request $request, $id)
    {
        $productset = ProductSet::find($id);

        $editproductset = [
            'product_set_name_id' => $request->product_set_name_id,
            'detail' => $request->detail,
            'aluminum_surface_finish_id' => $request->aluminum_surface_finish_id,
            'glass_colouritem_id' => $request->glass_colouritem_id,
            'glasstype_id' => $request->glasstype_id,
        ];

        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $editproductset['product_image'] = file_get_contents($file->getRealPath());
        }

        $productset->update($editproductset);

        return redirect()->route('admin.projects.productsetdetail')->with('success', 'แก้ไขผลิตภัณฑ์เรียบร้อยแล้ว');
    }


    public function deleteproductset($id)
    {
        $productset = ProductSet::find($id);
        $productset->delete();

        return redirect()->back()->with('success', 'ลบผลิตภัณฑ์เรียบร้อยแล้ว');
    }

    public function showdeletproductset()
    {
        $productset = ProductSet::onlyTrashed()->with([
            'productSetName',
            'glasscolouritem',
            'aluminumSurfaceFinish',
            'glasstype'
        ])->get();

        return view('admin.projects.productset.showdeletproductset', compact('productset'));
    }

    public function restoreproductset($id)
    {
        $productset = ProductSet::onlyTrashed()->find($id);
        $productset->restore();

        return back()->with('success', 'กู้คืนผลิตภัณฑ์เรียบร้อยแล้ว');
    }




    public function formaddproductsetitem(Request $request, $id)
    {

        $productset = ProductSet::with([
            'productSetName',
            'productsetitem'
        ])->find($id);


        $aluminiumlist = Material::with([
            'aluminiumItem.aluminiumType',
            'aluminiumItem.aluminiumType',
            'aluminiumItem.aluminumSurfaceFinish'
        ])->where('material_type', 'อลูมิเนียม')->whereHas('aluminiumItem', function ($a) use ($productset) {
            $a->where('aluminum_surface_finish_id', $productset->aluminum_surface_finish_id);
        })->get();

        $accessorylist = Material::with([
            'accessoryItem.accessoryType',
            'accessoryItem.aluminumSurfaceFinish',
            'accessoryItem.unit',
        ])->where('material_type', 'อุปกรณ์เสริม')->whereHas('accessoryItem', function ($as) use ($productset) {
            $as->where('aluminum_surface_finish_id', $productset->aluminum_surface_finish_id);
        })->get();

        $glasslist = Material::with([
            'glassItem.glassType',
            'glassItem.colourItem',
            'glassItem.glassSize',
        ])->where('material_type', 'กระจก')->whereHas('glassItem', function ($gl) use ($productset) {
            $gl->where('glass_type_id', $productset->glasstype_id);
            $gl->where('colouritem_id', $productset->glass_colouritem_id);
        })->get();

        $outhelist = Material::with([
            'consumableItem.unit',
            'consumableItem.consumabletype',
            'toolItem.toolType'
        ])->whereIn('material_type', ['วัสดุสิ้นเปลือง', 'เครื่องมือช่าง'])->get();


        $material = $aluminiumlist->merge($accessorylist)->merge($glasslist)->merge($outhelist);




        $aluminumtype = AluminiumProfileType::all();
        $aluminumSurfaces = AluminumSurfaceFinish::all();
        $glassTypes = GlassType::all();
        $colour = ColourItem::all();
        $accessorytype = AccessoryType::all();
        $consumable = ConsumableType::all();
        $tool = ToolType::all();


        return view('admin.projects.productset.formaddproductsetitem', compact('productset', 'material', 'aluminumSurfaces', 'glassTypes', 'colour', 'aluminumtype', 'consumable', 'accessorytype', 'tool'));
    }

    public function addmaterialproductsetitem(Request $request)
    {
        $material = Material::find($request->material_id);


        ProductSetItem::create([
            'product_set_id' => $request->product_set_id,
            'material_id'  => $request->material_id,
            'created_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'เพิ่มวัสดุเข้าชุดผลิตภัณฑ์สำเร็จ');
    }

    public function deletematerialproductsetitem($id)
    {
        $productsetitem = ProductSetItem::find($id);

        $productsetitem->delete();


        return redirect()->back()->with('success', 'ลบออกจากชุดผลิตภัณฑ์สำเร็จ');
    }

    public function showitemproduct($id)
    {
        $productset = ProductSet::with([
            'productSetName',
            'productsetitem.material',
            'productsetitem.material.aluminiumItem.aluminiumType',
            'productsetitem.material.aluminiumItem.aluminumSurfaceFinish',
            'productsetitem.material.aluminiumItem.aluminiumLengths',
            'productsetitem.material.glassItem.glassType',
            'productsetitem.material.glassItem.colourItem',
            'productsetitem.material.glassItem.glassSize',
            'productsetitem.material.accessoryItem.accessoryType',
            'productsetitem.material.accessoryItem.aluminumSurfaceFinish',
            'productsetitem.material.accessoryItem.unit',
            'productsetitem.material.consumableItem.unit',
            'productsetitem.material.consumableItem.consumabletype',
            'productsetitem.material.toolItem.toolType'
        ])->find($id);

        return view('admin.projects.productset.showitemproduct', compact('productset'));
    }






    public function formproductsetname()
    {
        $productsetnameall = ProductSetName::withTrashed()->get();
        return view('admin.projects.productset.formproductsetname', compact('productsetnameall'));
    }

    public function createproductsetname(Request $request)
    {

        ProductSetName::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'เพิ่มชื่อผลิตภัณฑ์สำเร็จ');
    }


    public function admupdateproductsetname(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $productsetname = ProductSetName::withTrashed()->find($id);

        $productsetname->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'อัปเดตชื่อผลิตภัณฑ์เรียบร้อยแล้ว');
    }

    public function deleteproductsetname($id)
    {
        $productsetname = ProductSetName::find($id);

        $productsetname->delete();

        return redirect()->back()->with('success', 'ระงับการใช้งานชื่อผลิตภัณฑ์นี้แล้ว (สามารถกู้คืนได้)');
    }

    public function restoreproductsetname($id)
    {
        $productsetname = ProductSetName::onlyTrashed()->find($id);

        $productsetname->restore();

        return redirect()->back()->with('success', 'กู้คืนชื่อผลิตภัณฑ์กลับมาใช้งานเรียบร้อยแล้ว');
    }

    public function updatestatussurveying(Request $request, $id)
    {
        $project = Project::find($id);
        $project->update([
            'status' => 'surveying'
        ]);

        return redirect()->route('admin.projects.formsurveying', $project->id)->with('success', 'กำลังสำรวจ');
    }


    public function updatestatuspendingquotation(Request $request)
    {

        $project = Project::find($request->id);
        $project->update([
            'status' => 'pending_quotation'
        ]);

        return redirect()->route('admin.projects.index', $project->id)->with('success', 'รอเสนอราคา');
    }



    public function projectalldetail($id)
    {


        $project = Project::with([
            'customer.province',
            'customer.amphure',
            'customer.tambon',
            'projectname',
            'createdBy',
            'assignedSurveyor',
            'assignedInstaller',
            'projectimage.imagetype',
            'customerneed',
            'customerneed.creator',
            'customerneed.productset.productSetName',
            'projectexpenses',
            'projectexpenses.type',
            'projectexpenses.creator'


        ])->find($id);


        $projectname = ProjectName::all();
        $customerall = Customer::all();
        $technician = User::where('role', 'technician')->get();


        $satatuswaiting1 =[
            'waiting_survey',
            'pending_survey' 
        ];

        $satatuswaiting =[
            'surveying',
            'pending_quotation'   
        ];



        $satatusopen = [
            'waiting_approval',
            'approved',
            'material_planning',
            'waiting_purchase',
            'ready_to_withdraw',
            'materials_withdrawn',
            'installing',
            'completed'
        ];

        $satatusonline = [
            'waiting_approval',
            'approved',
            'material_planning',
            'waiting_purchase',
            'ready_to_withdraw',
            'materials_withdrawn' ,
            'installing',
            'completed',
            'cancelled',
        ];



        $statuspayment = [
            'approved',
            'material_planning',
            'waiting_purchase',
            'ready_to_withdraw',
            'materials_withdrawn' ,
            'installing',
            'completed',
        ];

        $statusmaterialplanningopen = [
            'material_planning',
            'waiting_purchase',
            'ready_to_withdraw',
            'materials_withdrawn',
            'installing',
            'completed'
        ];

        $statusopendatework = [
            'ready_to_withdraw',
            'materials_withdrawn'
        ];




        return view('admin.projects.alldetail.detailpage', compact('project', 'customerall', 'projectname', 'technician', 'statuspayment', 'satatusopen', 'statusmaterialplanningopen','satatuswaiting','satatusonline','statusopendatework','satatuswaiting1'));
    }

    public function addautersurver(Request $request)
    {
        $request->validate([
            'homeimg' => 'nullable|image|max:10240',
        ],[
            'homeimg.mimes' => 'ระบบไม่รองรับไฟล์รูปภาพประเภทนี้ (เช่น WEBP) กรุณาใช้ไฟล์ JPG หรือ PNG เท่านั้น',
            'homeimg.max' => 'ขนาดไฟล์รูปภาพต้องไม่เกิน 10MB',
        ]);

        $project = Project::find($request->id);
        $updateData = [
            'estimated_work_days' => $request->estimated_work_days,
            'daily_labor_rate'    => $request->daily_labor_rate,
        ];

        if ($request->hasFile('homeimg')) {
            $file = $request->file('homeimg');

            $source = @imagecreatefromstring(file_get_contents($file->getRealPath()));
            
            if (!$source) {
                return redirect()->back()->with('error', 'ไฟล์รูปภาพไม่รองรับหรือไฟล์เสีย กรุณาแปลงไฟล์เป็น JPG หรือ PNG ก่อนอัปโหลด');
            }

            $source = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $width  = imagesx($source);
            $height = imagesy($source);

            if ($width > 1920) {
                $newWidth  = 1920;
                $newHeight = (int) (($height / $width) * 1920);

                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                $source = $resized;
            }

            ob_start();
            imagejpeg($source, null, 80);
            $updateData['homeimg'] = ob_get_clean();
            imagedestroy($source);
        }

        $project->update($updateData);

        return redirect()->back()->with('success', 'บันทึกข้อมูลหน้างานและรูปภาพสำเร็จ');
    }



    public function satatuswaitingapproval(Request $request)
    {
        $project = $this->getCalculatedProject($request->id);

        $installerCount = $project->installers->count();
        $installerCount = max($installerCount, 1);

        $sumProductTotal = 0;
        foreach ($project->customerneed as $need) {
            $sumProductTotal += ($need->quantity * $need->calculated_total);
        }

        $totalExpenses = 0;
        foreach ($project->projectexpenses as $expense) {
            $totalExpenses += $expense->amount;
        }

        $laborSurveying     = $project->labor_cost_surveying;
        $laborInstallPerDay = $project->daily_labor_rate * $installerCount;
        $laborInstallTotal  = $project->estimated_work_days * $laborInstallPerDay;
        $totalLabor         = $laborSurveying + $laborInstallTotal;

        $sumtotal    = $sumProductTotal + $totalExpenses + $totalLabor;
        $sevic       = $sumtotal * 0.20;
        $sumincome   = $sumtotal + $sevic;
        $pricevat    = $sumincome * 0.07;
        $grand_total = $sumincome + $pricevat;

        $quotation = Quotation::create([
            'project_id'             => $project->id,
            'total_product_amount'   => $sumProductTotal,
            'total_expense_amount'   => $totalExpenses,
            'total_labor_amount'     => $totalLabor,
            'service_charge_amount'  => $sevic,
            'vat_amount'             => $pricevat,
            'grand_total'            => $grand_total,
        ]);

        foreach ($project->customerneed as $need) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'item_name'    => $need->productset->productSetName->name,
                'description'  => "ขนาด " . $need->width . " x " . $need->height . " ซม.",
                'quantity'     => $need->quantity,
                'unit_price'   => $need->calculated_total,
                'total_price'  => $need->quantity * $need->calculated_total,
                'item_type'    => 'product',
            ]);

            foreach ($need->productset->productsetitem as $item) {
                $mat = $item->material;

                if (!$mat) continue;

                $matDetail = $mat->name ?? '-';

                if ($mat->aluminiumItem) {
                    $matDetail = ($mat->aluminiumItem?->aluminiumType?->name ?? '') . " สี " . ($mat->aluminiumItem?->aluminumSurfaceFinish?->name ?? '');
                } elseif ($mat->glassItem) {
                    $matDetail = ($mat->glassItem?->glassType?->name ?? '') . " สี " . ($mat->glassItem?->colourItem?->name ?? '');
                } elseif ($mat->accessoryItem) {
                    $matDetail = $mat->accessoryItem?->accessoryType?->name ?? '';
                } elseif ($mat->consumableItem) {
                    $matDetail = $mat->consumableItem?->consumabletype?->name ?? '';
                }

                QuotationMaterial::create([
                    'quotation_id'  => $quotation->id,
                    'material_id'   => $mat->id,
                    'material_type' => $mat->material_type,
                    'description'   => $matDetail,
                    'lot_number'    => $item->calculated_lot,
                    'unit_price'    => $item->calculated_unit_price,
                    'quantity'      => $item->calculated_qty,
                    'total_price'   => $item->calculated_total,
                    'remark'        => $item->calculated_remark,
                ]);
            }
        }

        $project->update(['status' => 'waiting_approval']);

        return redirect()->route('admin.projects.alldetail', $project->id)->with('success', 'บันทึกใบเสนอราคาและล็อคข้อมูลสำเร็จ รอลูกค้าอนุมัติ');
    }

    public function reviseQuotation($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลงาน');
        }

        $quotations = Quotation::where('project_id', $project->id)->get();

        foreach ($quotations as $quotation) {
            QuotationItem::where('quotation_id', $quotation->id)->delete();
            QuotationMaterial::where('quotation_id', $quotation->id)->delete();
            $quotation->delete();
        }

        ProjectPurchase::where('project_id', $project->id)->delete();
        $project->update([
            'status' => 'pending_quotation'
        ]);

        return redirect()->route('admin.projects.alldetail', $project->id)->with('success', 'ลบใบเสนอราคาเดิมเรียบร้อยแล้ว สถานะกลับสู่ "รอเสนอราคา" คุณสามารถแก้ไขข้อมูลได้ทันที');
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
            $m_w         = $need->width / 100;
            $m_h         = $need->height / 100;
            $needTotal   = 0;
            $requestlent = max($m_w, $m_h);

            foreach ($need->productset->productsetitem as $item) {
                $material = $item->material;

                if (!$material) continue;

                $type        = $material->material_type;
                $selectprice = 0;
                $selectlot   = '';
                $remark      = '';
                $qtyuse      = 0;
                $description = '';

                if ($type == 'อลูมิเนียม') {
                    $totalneedlen = ($m_w * 2) + ($m_h * 2);

                    $allMatchingStock = Price::where('material_id', $material->id)
                        ->where('quantity', '>', 0)
                        ->whereHas('aluminiumlength', function ($q) use ($requestlent) {
                            $q->where('length_meter', '>=', $requestlent);
                        })
                        ->with('aluminiumlength')
                        ->orderBy('id', 'asc')
                        ->get();

                    $stock = $allMatchingStock->first();

                    $aluName  = $material->aluminiumItem?->aluminiumType?->name ?? '';
                    $aluColor = $material->aluminiumItem?->aluminumSurfaceFinish?->name ?? '';

                    if ($stock) {
                        $len            = $stock->aluminiumlength->length_meter;
                        $qtyuse         = ceil($totalneedlen / $len);
                        $totalAvailable = $allMatchingStock->sum('quantity');
                        $description    = "อลูมิเนียม {$aluName} สี{$aluColor} (ความยาว {$len} ม.)";

                        if ($totalAvailable >= $qtyuse) {
                            $selectprice               = $stock->price;
                            $selectlot                 = $stock->lot;
                            $item->calculated_price_id = $stock->id;
                            $remark                    = "ใช้อลูมิเนียมยาว {$len} ม. จำนวน {$qtyuse} เส้น";
                        } else {
                            $selectprice               = $stock->price;
                            $selectlot                 = 'ไม่มีของ';
                            $item->calculated_price_id = null;
                            $remark                    = "สต็อกมีแค่ {$totalAvailable} เส้น ต้องการ {$qtyuse} เส้น";
                        }
                    } else {
                        $flatlen                   = 6;
                        $qtyuse                    = ceil($totalneedlen / $flatlen);
                        $selectprice               = 300;
                        $selectlot                 = 'ไม่มีของ';
                        $item->calculated_price_id = null;
                        $remark                    = "ใช้ราคาเหมา 300 บ. ต่อเส้น ({$flatlen} ม.) จำนวน {$qtyuse} เส้น";
                        $description               = "อลูมิเนียม {$aluName} (ราคาเหมา ความยาว {$flatlen} ม.)";
                    }

                } elseif ($type == 'กระจก') {
                    $allMatchingStock = Price::where('material_id', $material->id)
                        ->where('quantity', '>', 0)
                        ->whereHas('glassSize', function ($q) use ($m_w, $m_h) {
                            $q->where('width_meter', '>=', $m_w)
                            ->where('length_meter', '>=', $m_h);
                        })
                        ->with('glassSize')
                        ->orderBy('id', 'asc')
                        ->get();

                    $stock  = $allMatchingStock->first();
                    $qtyuse = 2;

                    $glassName  = $material->glassItem?->glassType?->name ?? '';
                    $glassColor = $material->glassItem?->colourItem?->name ?? '';

                    if ($stock && $stock->glassSize) {
                        $totalAvailable = $allMatchingStock->sum('quantity');
                        $sheetW         = $stock->glassSize->width_meter;
                        $sheetH         = $stock->glassSize->length_meter;
                        $description    = "กระจก{$glassName} สี{$glassColor} (ขนาด {$sheetW}×{$sheetH} ม.)";

                        if ($totalAvailable >= $qtyuse) {
                            $selectprice               = $stock->price;
                            $selectlot                 = $stock->lot;
                            $item->calculated_price_id = $stock->id;
                            $remark                    = "ใช้กระจก {$sheetW}×{$sheetH} ม. จำนวน {$qtyuse} แผ่น (×2 กันแตก)";
                        } else {
                            $selectprice               = $stock->price;
                            $selectlot                 = 'ไม่มีของ';
                            $item->calculated_price_id = null;
                            $remark                    = "สต็อกมีแค่ {$totalAvailable} แผ่น ต้องการ {$qtyuse} แผ่น";
                        }
                    } else {
                        $flatW                     = 2;
                        $flatH                     = 2;
                        $selectprice               = 400;
                        $selectlot                 = 'ไม่มีของ';
                        $item->calculated_price_id = null;
                        $remark                    = "ไม่มีกระจกขนาดพอ ใช้ราคาเหมา 400 บ./แผ่น จำนวน {$qtyuse} แผ่น";
                        $description               = "กระจก{$glassName} (ราคาเหมา {$flatW}×{$flatH} ม.)";
                    }

                } else {
                    $allStock       = Price::where('material_id', $material->id)
                        ->where('quantity', '>', 0)
                        ->orderBy('id', 'asc')
                        ->get();

                    $stock          = $allStock->first();
                    $qtyuse         = 1;
                    $totalAvailable = $allStock->sum('quantity');

                    if ($stock && $totalAvailable >= $qtyuse) {
                        $selectprice               = $stock->price;
                        $selectlot                 = $stock->lot;
                        $item->calculated_price_id = $stock->id;
                        $remark                    = '-';
                    } else {
                        $selectprice               = 100;
                        $selectlot                 = 'ไม่มีของ';
                        $item->calculated_price_id = null;
                        $remark                    = 'ใช้ราคาเหมา 100 บ.';
                    }

                    if ($type == 'อุปกรณ์เสริม') {
                        $accName     = $material->accessoryItem?->accessoryType?->name ?? '';
                        $description = "อุปกรณ์เสริม: {$accName}";
                    } elseif ($type == 'วัสดุสิ้นเปลือง') {
                        $conName     = $material->consumableItem?->consumabletype?->name ?? '';
                        $description = "วัสดุสิ้นเปลือง: {$conName}";
                    } elseif ($type == 'เครื่องมือช่าง') {
                        $toolName    = $material->toolItem?->toolType?->name ?? '';
                        $description = "เครื่องมือช่าง: {$toolName}";
                    } else {
                        $description = "วัสดุอื่นๆ";
                    }
                }

                $total_item_price               = $qtyuse * $selectprice;
                $item->calculated_description   = $description;
                $item->calculated_material_type = $type;
                $item->calculated_lot           = $selectlot;
                $item->calculated_unit_price    = $selectprice;
                $item->calculated_qty           = $qtyuse;
                $item->calculated_total         = $total_item_price;
                $item->calculated_remark        = $remark;

                $needTotal += $total_item_price;
            }

            $need->calculated_total = $needTotal;
        }

        return $project;
    }


    public function addbid($id)
    {
        $project = $this->getCalculatedProject($id);
        return view('admin.projects.bid.addbid', compact('project'));
    }

    public function addbiddocument($id)
    {
        $project = $this->getCalculatedProject($id);

        $project->load([
            'customerneed.projectImage.imagetype',
            'quotation.items',
            'quotation.quotationMaterials'
        ]);

        $statusopenqtc = [
            'pending_quotation',
            'approved',
            'waiting_approval'
        ];

        return view('admin.projects.bid.addbiddocument', compact('project', 'statusopenqtc'));
    }

    public function receipt($id)
    {
        $project = $this->getCalculatedProject($id);
        return view('admin.projects.bid.receipt', compact('project'));
    }

    public function taxInvoice($id)
    {
        $project = $this->getCalculatedProject($id);
        return view('admin.projects.bid.tax_invoice', compact('project'));
    }


    public function updatestatusapproved($id)
    {
        $project = Project::find($id);

        $project->update([
            'status' => 'approved'
        ]);

        return redirect()->route('admin.projects.index', $project->id)->with('success', 'ลูกค้าอนุมัติและชำระเงินแล้ว');
    }

    public function updatestatusmaterialplanning($id)
    {
        $project = Project::find($id);
        $project->update([
            'status' => 'material_planning'
        ]);
        return redirect()->route('admin.projects.materialplanningpage', $project->id)->with('success', 'วางแผนวัสดุ');
    }

    public function materialplanningpage($id)
    {
        $project = $this->qerydataproject($id);
        $quotation = Quotation::with('quotationMaterials')->where('project_id', $id)->latest()->first();

        return view('admin.projects.materialplanning.materialplanningpage', compact('project','quotation'));
    }

    public function materialplanningpagedocument($id)
    {
        $project = Project::with([
            'projectname',
            'projectPurchase.items.material.aluminiumItem.aluminiumType',
            'projectPurchase.items.material.aluminiumItem.aluminumSurfaceFinish',
            'projectPurchase.items.material.glassItem.glassType',
            'projectPurchase.items.material.glassItem.colourItem',
            'projectPurchase.items.material.accessoryItem.accessoryType',
            'projectPurchase.items.material.consumableItem.consumabletype',
        ])->find($id);

        return view('admin.projects.materialplanning.materialplanningpagedocument', compact('project'));
    }




    public function  updatestatuswaitingpurchase(Request $request, $id)
    {
        $project = $this->getCalculatedProject($id);
        $itemsToBuy = [];
        $grandTotal = 0;

        foreach ($project->customerneed as $need) {
            foreach ($need->productset->productsetitem as $item) {
                $lot = $item->calculated_lot;
                if (in_array($lot, ['ไม่มีของหรือขนาดไม่พอ', 'ไม่มีของ/ขนาดไม่พอ', 'ไม่มีของ'])) {
                    $itemsToBuy[] = $item;
                    $grandTotal += $item->calculated_total;
                }
            }
        }

        if (count($itemsToBuy) > 0) {
            $purchase = ProjectPurchase::create([
                'project_id' => $project->id,
                'total_amount' => $grandTotal
            ]);

            foreach ($itemsToBuy as $item) {
                ProjectPurchaseItem::create([
                    'project_purchase_id' => $purchase->id,
                    'material_id' => $item->material_id,
                    'description' => $item->calculated_description,
                    'quantity' => $item->calculated_qty,
                    'unit_price' => $item->calculated_unit_price,
                    'total_price' => $item->calculated_total,
                    'remark' => $item->calculated_remark
                ]);
            }

            $project->update(['status' => 'waiting_purchase']);
            return redirect()->route('admin.projects.index', $project->id)->with('success', 'บันทึกรายการสั่งซื้อสำเร็จ');
        } else {
            $project->update(['status' => 'ready_to_withdraw']);
            return redirect()->route('admin.projects.index', $project->id)->with('success', 'วัสดุมีครบในคลังแล้ว เปลี่ยนสถานะเป็นพร้อมเบิก');
        }
    }

    public function  updatestatusreadytowithdraw($id)
    {
        $project = Project::find($id);

        $project->update([
            'status' => 'ready_to_withdraw'
        ]);

        return redirect()->route('admin.projects.index', $project->id)->with('success', 'พร้อมเบิกวัสดุ');
    }










    

    

    public function withdrawpage($id)
    {
        $project = Project::with([
            'projectname',
            'customer',
            'quotation.quotationMaterials',
        ])->find($id);

        $users = User::all();

        $withdrawnSummary = MaterialLog::where('project_id', $id)
            ->where('direction', 'out')
            ->where('source', 'withdraw')
            ->selectRaw('material_id, SUM(quantitylog) as total_out')
            ->groupBy('material_id')
            ->pluck('total_out', 'material_id')
            ->toArray();

        $stockSummary = [];
        $firstPriceSummary = [];
        $quotationMats = $project->quotation?->quotationMaterials ?? collect();

        foreach ($quotationMats as $qmat) {
            if (!$qmat->material_id) continue;

            $allLots = Price::where('material_id', $qmat->material_id)
                ->where('quantity', '>', 0)
                ->orderBy('id', 'asc')
                ->get();

            $stockSummary[$qmat->material_id]     = $allLots->sum('quantity');
            $firstPriceSummary[$qmat->material_id] = $allLots->first();
        }

        return view('admin.projects.withdraw.withdrawpage',
            compact('project', 'withdrawnSummary', 'users', 'stockSummary', 'firstPriceSummary'));
    }

    public function withdrawform(Request $request, $id)
    {
        $selectedPriceIds = $request->input('selected_price_ids', []);
        $customQtys       = $request->input('custom_qty', []);

        if (empty($selectedPriceIds)) {
            return redirect()->back()->with('error', 'กรุณาเลือกวัสดุอย่างน้อย 1 รายการ');
        }

        $project = Project::with(['projectname'])->find($id);
        
        $assignedInstallerIds = AssignedInstaller::where('project_id', $id)->pluck('user_id')->toArray();

        $users = User::where('role', 'admin')->orWhereIn('id', $assignedInstallerIds)->get();

        $itemsToWithdraw = [];
        foreach ($selectedPriceIds as $pid) {
            $price = Price::with([
                'material.aluminiumItem.aluminiumType',
                'material.aluminiumItem.aluminumSurfaceFinish',
                'material.glassItem.glassType',
                'material.glassItem.colourItem',
                'material.accessoryItem.accessoryType',
                'material.consumableItem.consumabletype',
                'material.toolItem.toolType',
            ])->find($pid);

            if (!$price) continue;

            $mat = $price->material;
            $description = match(true) {
                $mat?->aluminiumItem  !== null => ($mat->aluminiumItem->aluminiumType->name ?? '-') . ' สี ' . ($mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-'),
                $mat?->glassItem      !== null => ($mat->glassItem->glassType->name ?? '-') . ' สี ' . ($mat->glassItem->colourItem->name ?? '-'),
                $mat?->accessoryItem  !== null => $mat->accessoryItem->accessoryType->name ?? '-',
                $mat?->consumableItem !== null => $mat->consumableItem->consumabletype->name ?? '-',
                $mat?->toolItem       !== null => $mat->toolItem->toolType->name ?? '-',
                default => '-',
            };

            $itemsToWithdraw[] = [
                'price_id'      => $pid,
                'material_type' => $mat?->material_type ?? '-',
                'description'   => $description,
                'lot'           => $price->lot,
                'qty'           => $customQtys[$pid] ?? 0,
            ];
        }

        return view('admin.projects.withdraw.withdrawform',
            compact('project', 'users', 'selectedPriceIds', 'customQtys', 'itemsToWithdraw'));
    }

    public function withdrawstore(Request $request, $id)
    {
        $selectedPriceIds = $request->input('selected_price_ids', []);
        $withdrawnby      = $request->input('withdrawn_by');
        $customQtys       = $request->input('custom_qty', []);

        if (empty($selectedPriceIds) || empty($withdrawnby)) {
            return redirect()->back()->with('error', 'กรุณาเลือกวัสดุและช่างผู้เบิก');
        }

        $project    = Project::with(['quotation.quotationMaterials'])->find($id);
        $withdrawal = Withdrawal::create([
            'project_id'   => $project->id,
            'withdrawn_by' => $withdrawnby,
            'recorded_by'  => Auth::id(),
        ]);

        foreach ($selectedPriceIds as $price_id) {
            $firstPrice = Price::find($price_id);
            if (!$firstPrice) continue;

            $qtyNeeded = (int)($customQtys[$price_id] ?? 0);
            if ($qtyNeeded <= 0) continue;

            $allLots = Price::where('material_id', $firstPrice->material_id)
                ->where('quantity', '>', 0)
                ->orderBy('id', 'asc')
                ->get();

            foreach ($allLots as $price) {
                if ($qtyNeeded <= 0) break;

                $take = min($price->quantity, $qtyNeeded);
                $price->decrement('quantity', $take);
                $qtyNeeded -= $take;

                MaterialLog::create([
                    'material_id' => $price->material_id,
                    'price_id'    => $price->id,
                    'user_id'     => Auth::id(),
                    'direction'   => 'out',
                    'quantitylog' => $take,
                    'project_id'  => $project->id,
                    'source'      => 'withdraw',
                ]);

                WithdrawalItem::create([
                    'withdrawal_id' => $withdrawal->id,
                    'material_id'   => $price->material_id,
                    'lot'           => $price->lot,
                    'quantity'      => $take,
                ]);
            }
        }

        $quotationMats = $project->quotation?->quotationMaterials ?? collect();
        $withdrawnSummary = MaterialLog::where('project_id', $id)
            ->where('direction', 'out')
            ->where('source', 'withdraw')
            ->selectRaw('material_id, SUM(quantitylog) as total')
            ->groupBy('material_id')
            ->pluck('total', 'material_id');

        $allDone = $quotationMats->every(function ($qmat) use ($withdrawnSummary) {
            if (!$qmat->material_id) return true;
            return ($withdrawnSummary[$qmat->material_id] ?? 0) >= $qmat->quantity;
        });

        if ($allDone) {
            $project->update(['status' => 'materials_withdrawn']);
            return redirect()->route('admin.projects.index', $project->id)->with('success', 'เบิกวัสดุครบแล้ว');
        }

        return redirect()->route('admin.projects.withdrawpage', $project->id)->with('success', 'บันทึกการเบิกบางส่วนเรียบร้อย');
    }






    public function assignInstaller(Request $request, $id)
    {
        $project = Project::find($id);

        $start = Carbon::parse($request->installation_start_date);
        $end   = $start->copy()->addDays($project->estimated_work_days - 1);

        $project->update([
            'installation_start_date'  => $start,
            'installation_end_date'    => $end
        ]);

        return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ');
    }



    public function installingpage($id)
    {
        $project = Project::with([
            'customerneed.productset.productsetitem.material',
            'installers'
        ])->find($id);

        $technician = User::where('role', 'technician')->get();


        return view('admin.projects.installing.installingpage', compact('project', 'technician'));
    }

    public function assignInstalleruser(Request $request, $id)
    {
        $exists = AssignedInstaller::where('project_id', $id)->where('user_id', $request->user_id)->exists();

        if (!$exists) {
            AssignedInstaller::create([
                'project_id' => $id,
                'user_id'    => $request->user_id
            ]);
            return redirect()->back()->with('success', 'เพิ่มช่างติดตั้งเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'ช่างคนนี้ถูกเพิ่มในงานนี้ไปแล้ว');
    }


    public function removeInstaller($id)
    {
        $record = AssignedInstaller::find($id);

        if ($record != null) {
            $record->delete();
            return redirect()->back()->with('success', 'ลบช่างที่เลือกเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'ไม่พบข้อมูล');
    }

    

    public function updatestatuscompleted($id)
    {
        $project = Project::find($id);

        $project->update([
            'status' => 'completed'
        ]);

        return redirect()->route('admin.projects.index', $project->id)->with('success', 'อัปเดตสถานะเป็น เสร็จสมบูรณ์');
    }

    public function updatestatuscancelled($id)
    {
        $project = Project::find($id);

        $project->update([
            'status' => 'cancelled'
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'ยกเลิกงานเรียบร้อย');
    }


    public function updateProjectPendingSurvey(Request $request, $id)
    {
        $project = Project::find($id);
        $project->update([
            'project_name_id'      => $request->project_name_id,
            'customer_id'          => $request->customer_id,
            'survey_date'          => $request->survey_date,
            'assigned_surveyor_id' => $request->assigned_surveyor_id,
            'labor_cost_surveying' => $request->labor_cost_surveying,
            'note'                 => $request->note,
        ]);

        return back()->with('success', 'แก้ไขข้อมูลงานสำเร็จ');
    }


    public function formdetialexpense($id)
    {
        $project = Project::find($id);
        $expense = ExpenseType::all();
        return view('admin.projects.detialexpense.formdetialexpense', compact('expense', 'project'));
    }

    public function createdetialexpense(Request $request)
    {

        $project = Project::find($request->project_id);
        ProjectExpense::create([
            'project_id' => $project->id,
            'expense_type_id' => $request->expense_type_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'created_by' => Auth::user()->id
        ]);

        return redirect()->route('admin.projects.alldetail', $project->id)->with('success', 'เพิ่มรายการค่าใช้จ่ายสำเร็จ');
    }

    public function formeditdetialexpense($id)
    {
        $projectexpense = ProjectExpense::with([
            'type',
            'creator'
        ])->find($id);
        $expense = ExpenseType::all();
        return view('admin.projects.detialexpense.edit.formeditdetialexpense', compact('projectexpense', 'expense'));
    }

    public function editdetialexpense(Request $request)
    {
        $projectExpense = ProjectExpense::find($request->id);

        $project = Project::find($request->project_id);

        $projectExpense->update([
            'project_id' => $project->id,
            'expense_type_id' => $request->expense_type_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.projects.alldetail', $project->id)->with('success', 'แก้ไขรายการค่าใช้จ่ายเรียบร้อยแล้ว');
    }

    public function deletedetialexpense($id)
    {
        $projectexpense = ProjectExpense::find($id);

        $project = Project::find($projectexpense->project_id);

        $projectexpense->delete();

        return redirect()->route('admin.projects.alldetail', $project->id)->with('success', 'ลบข้อมูลค่าใช้จ่ายสำเร็จ');
    }

    public function createIssue($id)
    {
        $project = Project::find($id);

        $withdrawnItems = WithdrawalItem::whereHas('withdrawal', function ($q) use ($id) {
            $q->where('project_id', $id);
        })->with([
            'material.aluminiumItem.aluminiumType',
            'material.aluminiumItem.aluminumSurfaceFinish',
            'material.aluminiumItem.aluminiumLengths',
            'material.glassItem.glassType',
            'material.glassItem.colourItem',
            'material.glassItem.glassSize',
            'material.accessoryItem.accessoryType',
            'material.accessoryItem.aluminumSurfaceFinish',
            'material.consumableItem.consumabletype',
            'material.toolItem.toolType'
        ])->get();
        
        $issues = ProjectIssue::where('project_id', $id)->orderBy('created_at', 'desc')->get();

        return view('admin.projects.issues.issues_create', compact('project', 'withdrawnItems', 'issues'));
    }


    public function storeIssue(Request $request, $project_id)
    {
        if (!$request->hasFile('image_data') || !$request->file('image_data')->isValid()) {
            return redirect()->back()->with('error', 'กรุณาแนบรูปภาพหลักฐานที่ถูกต้อง');
        }

        $withdrawalItem = WithdrawalItem::find($request->withdrawal_item_damaged);
        
        if (!$withdrawalItem) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลรายการวัสดุที่เลือก');
        }

        $belongsToProject = $withdrawalItem->withdrawal->project_id == $project_id;
        if (!$belongsToProject) {
            return redirect()->back()->with('error', 'รายการวัสดุนี้ไม่ได้อยู่ในงานนี้');
        }

        if ($request->damaged_amount > $withdrawalItem->quantity) {
            return redirect()->back()->with('error', 'แจ้งจำนวนมากกว่าที่มีอยู่');
        }

        $file = $request->file('image_data');
        $imageData = file_get_contents($file->getRealPath());

        $issue = ProjectIssue::create([
            'project_id'  => $project_id,
            'reported_by' => Auth::id(),
            'category'    => 'material_problems',
            'description' => $request->description,
            'status'      => 'pending',
            'withdrawal_item_damaged' => $request->withdrawal_item_damaged,
            'damaged_amount' => $request->damaged_amount,
        ]);

        IssueImage::create([
            'issue_id'   => $issue->id,
            'image_data' => $imageData,
        ]);

        $withdrawalItem->quantity -= $request->damaged_amount;
        $withdrawalItem->save();

        return redirect()->back()->with('success', 'รายงานปัญหาและปรับยอดวัสดุเรียบร้อยแล้ว');
    }

    public function adminfulleventcalendarpage()
    {
        $query = Project::with('projectname')->whereNotNull('survey_date');

        $events = $query->withTrashed()->with(['projectname', 'customer'])->get()->flatMap(function ($pj) {
            $customerName = $pj->customer->first_name ?? 'ไม่ระบุลูกค้า';
            $projectName = $pj->projectname->name ?? 'ไม่มีชื่อโครงการ';
            $statusLabel = $this->getStatusLabel($pj->status);
            $color = $this->getStatusColor($pj->status);

            if ($pj->trashed()) {
            $statusLabel = 'ยกเลิก';
            $color = '#DC143C';
            } else {
                $statusLabel = $this->getStatusLabel($pj->status);
                $color = $this->getStatusColor($pj->status);
            }

            $items = [];

            if ($pj->survey_date) {
                $items[] = [
                    'id'    => $pj->id . '_survey',
                    'title' => ($pj->trashed() ? " (ยกเลิก)" : ""). "[สำรวจ] " . $customerName . " - " . $projectName ,
                    'start' => date('Y-m-d', strtotime($pj->survey_date)),
                    'url'   => route('admin.projects.index', $pj->id),
                    'backgroundColor' => $color,
                    'borderColor'     => $color,
                    'allDay'          => true,
                    'textColor'       => '#ffffff'
                ];
            }

            if ($pj->installation_start_date && $pj->installation_end_date) {
                $items[] = [
                    'id'    => $pj->id . '_install',
                    'title' => ($pj->trashed() ? " (ยกเลิก)" : "")."[ติดตั้ง] " . $customerName . " - " . $projectName . " (" . $statusLabel . ")",
                    'start' => date('Y-m-d', strtotime($pj->installation_start_date)),
                    'end'   => date('Y-m-d', strtotime($pj->installation_end_date . ' +1 day')),
                    'url'   => route('admin.projects.index', $pj->id),
                    'backgroundColor' => $color,
                    'borderColor'     => $color,
                    'allDay'          => true,
                    'textColor'       => '#ffffff'
                ];
            }

            return $items;
        });

        $eventCount = $query->count();

        return view('admin.projects.adminfulleventcalendarpage', compact('events','eventCount'));
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'waiting_survey'      => 'รอวันสำรวจ',
            'pending_survey'      => 'นัดสำรวจ',
            'surveying'           => 'กำลังสำรวจ',
            'pending_quotation'   => 'รอเสนอราคา',
            'waiting_approval'    => 'รออนุมัติ',
            'approved'            => 'อนุมัติแล้วและชำระเงินแล้ว',
            'material_planning'   => 'วางแผนวัสดุ',
            'waiting_purchase'    => 'รอสั่งซื้อ',
            'ready_to_withdraw'   => 'พร้อมเบิก',
            'materials_withdrawn' => 'เบิกวัสดุแล้ว',
            'installing'          => 'กำลังติดตั้ง',
            'completed'           => 'เสร็จสิ้น',
            'cancelled'           => 'ยกเลิก',
        ];

        return $labels[$status] ?? 'cancelled';
    }


    private function getStatusColor($status)
    {
        $colors = [
            'waiting_survey'      => '#FF8C00',
            'pending_survey'      => '#D4AF37',
            'surveying'           => '#1E90FF',
            'pending_quotation'   => '#E91E63',
            'waiting_approval'    => '#9C27B0',
            'approved'            => '#78d37b',
            'material_planning'   => '#00CED1',
            'waiting_purchase'    => '#FF4500',
            'ready_to_withdraw'   => '#008080',
            'materials_withdrawn' => '#8B4513',
            'installing'          => '#4CAF50',
            'completed'           => '#708090',
            'cancelled'           => '#DC143C',
        ];

        return $colors[$status] ?? '#DC143C';
    }


    public function formcrateimgtype()
    {

        $imgtype = ImageTypeName::withTrashed()->get();
        return view('admin.projects.survey.formcrateimgtype', compact('imgtype'));
    }

    public function crateimgtype(Request $request)
    {

        ImageTypeName::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'เพิ่มประเภทภาพสำเร็จ');
    }


    public function updateimgtype(Request $request, $id)
    {
        $imgtype = ImageTypeName::find($id);

        if ($imgtype) {
            $imgtype->update([
                'name' => $request->name
            ]);
        }

        return redirect()->back()->with('success', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function deleteimgtype($id)
    {
        $imgtype = ImageTypeName::find($id);
        if ($imgtype) {
            $imgtype->delete();
        }
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อย');
    }

    public function restoreimgtype($id)
    {
        $imgtype = ImageTypeName::withTrashed()->find($id);
        if ($imgtype) {
            $imgtype->restore();
        }
        return redirect()->back()->with('success', 'กู้คืนข้อมูลเรียบร้อย');
    }


    public function restockpage($id)
    {
        $project = Project::with([
            'projectPurchase.items.material.aluminiumItem',
            'projectPurchase.items.material.glassItem',
            'projectPurchase.items.material.accessoryItem',
            'projectPurchase.items.material.consumableItem',
        ])->find($id);

        $purchase = $project->projectPurchase;
        $purchaseItems = $purchase ? $purchase->items : collect();

        $purchaseItems->transform(function ($item) {
            $item->current_stock = Price::where('material_id', $item->material_id)->sum('quantity');
            $item->required_qty = $item->quantity;
            $item->is_complete = $item->current_stock >= $item->required_qty;
            
            return $item;
        });

        $allComplete = $purchaseItems->isNotEmpty() && $purchaseItems->every('is_complete', true);

        return view('admin.projects.materialplanning.restockmaterials', compact('project', 'purchaseItems', 'allComplete'));
    }

    public function restockForm(Request $request, $id)
    {
        $selected_ids = $request->input('selected_materials');

        if (empty($selected_ids)) {
            return redirect()->back()->with('error', 'กรุณาเลือกวัสดุอย่างน้อย 1 รายการ');
        }

        $project = Project::find($id);
        $dealers = Dealer::all();

        $groupedMaterials = Material::whereIn('id', $selected_ids)
            ->get()
            ->map(function ($mat) {
                $mat->display_detail = match ($mat->material_type) {
                    'อลูมิเนียม' => ($mat->aluminiumItem->aluminiumType->name ?? '-') . " (" . ($mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-') . ")",
                    'กระจก' => ($mat->glassItem->glassType->name ?? '-') . " (" . ($mat->glassItem->colourItem->name ?? '-') . ")",
                    'อุปกรณ์เสริม' => $mat->accessoryItem->accessoryType->name ?? '-',
                    'วัสดุสิ้นเปลือง' => $mat->consumableItem->consumabletype->name ?? '-',
                    default => '-'
                };
                return $mat;
            })
            ->groupBy('material_type');

        return view('admin.projects.materialplanning.restockform', compact('project', 'groupedMaterials', 'dealers'));
    }

    public function processrestock(Request $request, $id)
    {
        $restockGroups = $request->input('restock_group', []);

        foreach ($restockGroups as $type => $data) {
            $materialIds = $data['material_ids'] ?? [];

            foreach ($materialIds as $material_id) {
                $material = Material::find($material_id);
                if (!$material) continue;

                $priceColumn = null;
                $priceItemId = null;

                if ($material->material_type == 'อลูมิเนียม') {
                    $aluminiumlength = AluminiumLength::firstOrCreate([
                        'aluminium_item_id' => $material->aluminium_item_id,
                        'length_meter' => $data['length_meter']
                    ]);
                    $priceColumn = 'aluminium_length_id';
                    $priceItemId = $aluminiumlength->id;
                    
                } elseif ($material->material_type == 'กระจก') {
                    $glasssize = GlassSize::firstOrCreate([
                        'glass_item_id' => $material->glass_item_id,
                        'width_meter' => $data['width_meter'],
                        'length_meter' => $data['length_meter'],
                        'thickness' => $data['thickness']
                    ]);
                    $priceColumn = 'glass_size_id';
                    $priceItemId = $glasssize->id;
                    
                } elseif ($material->material_type == 'อุปกรณ์เสริม') {
                    $priceColumn = 'accessory_item_id';
                    $priceItemId = $material->accessory_item_id;
                    
                } elseif ($material->material_type == 'เครื่องมือช่าง') {
                    $priceColumn = 'tool_item_id';
                    $priceItemId = $material->tool_item_id;
                    
                } elseif ($material->material_type == 'วัสดุสิ้นเปลือง') {
                    $priceColumn = 'consumable_item_id';
                    $priceItemId = $material->consumable_item_id;
                }

                if ($priceColumn && $priceItemId) {
                    $lotCount = Price::where($priceColumn, $priceItemId)
                        ->where('dealer_id', $data['dealer_id'])
                        ->count();

                    $price = Price::create([
                        'material_id' => $material->id,
                        $priceColumn  => $priceItemId,
                        'dealer_id'   => $data['dealer_id'],
                        'price'       => $data['price'],
                        'quantity'    => $data['qty'],
                        'lot'         => "ล็อตที่" . ($lotCount + 1)
                    ]);

                    MaterialLog::create([
                        'material_id' => $material->id,
                        'price_id'    => $price->id,
                        'user_id'     => Auth::id(),
                        'direction'   => 'in',
                        'quantitylog'    => $data['qty'],
                        'source'      => 'restock',
                    ]);
                }
            }
        }

        return redirect()->route('admin.projects.restockpage', $id)->with('success', 'เติมสต็อกวัสดุสำเร็จ');
    }

    public function choosetypeissues($id){
        $projects = Project::find($id);
        return view('admin.projects.issues.choosetypeissues',compact('projects'));
    }

    public function generalissues($id){
        $project = Project::find($id);
        $issues = ProjectIssue::where('project_id', $id)->orderBy('created_at', 'desc')->get();
        return view('admin.projects.issues.generalissues',compact('project','issues'));
    }

    public function storegeneralissues(Request $request, $project_id)
    {
        
        ProjectIssue::create([
            'project_id'  => $project_id,
            'reported_by' => Auth::id(),
            'category'    => 'general_problems',
            'description' => $request->description,
            'status'      => 'pending'
        ]);

        
        return redirect()->back()->with('success', 'รายงานปัญหาและบันทึกรูปภาพสำเร็จ');
    }

   public function manageproblemsindex(){
        $issues = ProjectIssue::whereHas('project')->with([
            'project.customer',
            'project.projectname',
        ])->latest()->get();

        $groupedIssues = $issues->groupBy('project_id');

        $statusColors = [
            'pending_survey' => ['#D4AF37', 'นัดสำรวจ'],
            'waiting_survey' => ['#FF8C00', 'รอวันสำรวจ'],
            'surveying' => ['#1E90FF', 'กำลังสำรวจ'],
            'pending_quotation' => ['#E91E63', 'รอเสนอราคา'],
            'waiting_approval' => ['#9C27B0', 'รออนุมัติ'],
            'approved' => ['#78d37b', 'อนุมัติและชำระเงินแล้ว'],
            'material_planning' => ['#00CED1', 'วางแผนวัสดุ'],
            'waiting_purchase' => ['#FF4500', 'รอสั่งซื้อ'],
            'ready_to_withdraw' => ['#008080', 'พร้อมเบิก'],
            'materials_withdrawn' => ['#8B4513', 'เบิกวัสดุแล้ว'],
            'installing' => ['#4CAF50', 'กำลังติดตั้ง'],
            'completed' => ['#708090', 'เสร็จสิ้น'],
            'cancelled' => ['#DC143C', 'ยกเลิก']
        ];

        return view('admin.projects.issues.manageproblemsindex', compact('groupedIssues', 'statusColors'));
    }

    public function issuedetail(Request $request, $project_id)
    {
        $project = Project::with('projectname')->find($project_id);
        $query = ProjectIssue::withTrashed()->with([
                'reporter', 
                'images', 
                'withdrawalitemdamaged.material.aluminiumItem.aluminiumType',
                'withdrawalitemdamaged.material.aluminiumItem.aluminumSurfaceFinish',
                'withdrawalitemdamaged.material.aluminiumItem.aluminiumLengths',
                'withdrawalitemdamaged.material.glassItem.glassType',
                'withdrawalitemdamaged.material.glassItem.colourItem',
                'withdrawalitemdamaged.material.glassItem.glassSize',
                'withdrawalitemdamaged.material.accessoryItem.accessoryType',
                'withdrawalitemdamaged.material.accessoryItem.aluminumSurfaceFinish',
                'withdrawalitemdamaged.material.consumableItem.consumabletype',
                'withdrawalitemdamaged.material.toolItem.toolType'
            ])->where('project_id', $project_id);


        $issues = $query->latest()->get();

        return view('admin.projects.issues.issuedetail', compact('project', 'issues'));
    }

    public function destroyissue($id)
    {
        ProjectIssue::find($id)->delete();
        return back();
    }

    public function restoreissue($id)
    {
        ProjectIssue::withTrashed()->find($id)->restore();
        return back();
    }

    public function showissuedetail($id)
    {
        $issue = ProjectIssue::with([
            'project.projectname',
            'reporter',
            'images',
            'withdrawalitemdamaged.material.aluminiumItem.aluminiumType',
            'withdrawalitemdamaged.material.aluminiumItem.aluminumSurfaceFinish',
            'withdrawalitemdamaged.material.aluminiumItem.aluminiumLengths',
            'withdrawalitemdamaged.material.glassItem.glassType',
            'withdrawalitemdamaged.material.glassItem.colourItem',
            'withdrawalitemdamaged.material.glassItem.glassSize',
            'withdrawalitemdamaged.material.accessoryItem.accessoryType',
            'withdrawalitemdamaged.material.accessoryItem.aluminumSurfaceFinish',
            'withdrawalitemdamaged.material.consumableItem.consumabletype',
            'withdrawalitemdamaged.material.toolItem.toolType'
        ])->find($id);

        return view('admin.projects.issues.showissuedetail', compact('issue'));
    }

    
    public function editIssue($id)
    {
        $issue = ProjectIssue::find($id);
        $project = Project::find($issue->project_id);

        $withdrawnItems = WithdrawalItem::whereHas('withdrawal', function ($q) use ($project) {
            $q->where('project_id', $project->id);
        })->with([
            'material.aluminiumItem.aluminiumType',
            'material.aluminiumItem.aluminumSurfaceFinish',
            'material.aluminiumItem.aluminiumLengths',
            'material.glassItem.glassType',
            'material.glassItem.colourItem',
            'material.glassItem.glassSize',
            'material.accessoryItem.accessoryType',
            'material.accessoryItem.aluminumSurfaceFinish',
            'material.consumableItem.consumabletype',
            'material.toolItem.toolType'
        ])->get();

        return view('admin.projects.issues.issues_edit', compact('issue', 'project', 'withdrawnItems'));
    }

    public function updateIssue(Request $request, $id)
    {
        $issue = ProjectIssue::find($id);
        $withdrawalItem = WithdrawalItem::find($request->withdrawal_item_damaged);

        $difference = $issue->damaged_amount - $request->damaged_amount;
        
        if ($request->damaged_amount > ($withdrawalItem->quantity + $issue->damaged_amount)) {
            return redirect()->back()->with('error', 'จำนวนที่แจ้งเสียมากกว่าจำนวนที่มีอยู่ทั้งหมด');
        }

        $withdrawalItem->quantity += $difference;
        $withdrawalItem->save();

        $issue->update([
            'description' => $request->description,
            'withdrawal_item_damaged' => $request->withdrawal_item_damaged,
            'damaged_amount' => $request->damaged_amount,
        ]);

        if ($request->hasFile('image_data') && $request->file('image_data')->isValid()) {
            $file = $request->file('image_data');
            $imageData = file_get_contents($file->getRealPath());

            $issueImage = IssueImage::where('issue_id', $issue->id)->first();
            if ($issueImage) {
                $issueImage->update(['image_data' => $imageData]);
            } else {
                IssueImage::create([
                    'issue_id'   => $issue->id,
                    'image_data' => $imageData,
                ]);
            }
        }

        return redirect()->route('admin.projects.issues.detail', $issue->project_id)->with('success', 'แก้ไขข้อมูลปัญหาเรียบร้อยแล้ว');
    }

    public function refillIssue($id)
    {
        $issue = ProjectIssue::find($id);
        $project = Project::find($issue->project_id);
        
        return view('admin.projects.issues.issues_refill', compact('issue', 'project'));
    }

    public function storeRefillIssue(Request $request, $id)
    {
        $issue          = ProjectIssue::find($id);
        $withdrawalItem = WithdrawalItem::find($issue->withdrawal_item_damaged);

        $withdrawalItem->quantity += $request->refill_amount;
        $withdrawalItem->save();

        $price = Price::where('material_id', $withdrawalItem->material_id)
            ->where('lot', $withdrawalItem->lot)
            ->first();

        MaterialLog::create([
            'material_id' => $withdrawalItem->material_id,
            'price_id'    => $price?->id,
            'user_id'     => Auth::id(),
            'direction'   => 'in',
            'quantitylog' => $request->refill_amount,
            'project_id'  => $issue->project_id,
            'source'      => 'issue_refill',
            'note'        => 'เติมวัสดุจากปัญหา: ' . $issue->description,
        ]);

        $issue->update([
            'status'          => 'resolved',
            'refilled_amount' => $request->refill_amount
        ]);

        return redirect()->route('admin.projects.issues.detail', $issue->project_id)->with('success', 'เติมวัสดุและอัปเดตสถานะเสร็จสิ้นเรียบร้อยแล้ว');
    }

    public function undoRefillIssue($id)
    {
        $issue          = ProjectIssue::find($id);
        $withdrawalItem = WithdrawalItem::find($issue->withdrawal_item_damaged);

        $withdrawalItem->quantity -= $issue->refilled_amount;
        $withdrawalItem->save();

        MaterialLog::where('project_id', $issue->project_id)
            ->where('source', 'issue_refill')
            ->where('material_id', $withdrawalItem->material_id)
            ->latest()
            ->first()
            ?->delete();

        $issue->update([
            'status'          => 'in_progress',
            'refilled_amount' => null
        ]);

        return redirect()->back()->with('success', 'ยกเลิกการเติมวัสดุเรียบร้อยแล้ว');
    }



    public function updateIssuegeneralproblems(Request $request, $id)
    {
        $issue = ProjectIssue::find($id);

        $issue->update([
            'description' => $request->description
        ]);

        return redirect()->route('admin.projects.issues.detail', $issue->project_id)->with('success', 'แก้ไขข้อมูลปัญหาเรียบร้อยแล้ว');
    }


    public function updateresolved($id){
        $issue = ProjectIssue::find($id);

        $issue->update([
            'status' => 'resolved'
        ]);

        return redirect()->route('admin.projects.issues.detail', $issue->project_id)->with('success', 'แก้ไขข้อมูลปัญหาเรียบร้อยแล้ว');

    }

    public function undoIssuegeneralproblems($id)
    {
        $issue = ProjectIssue::find($id);

        $issue->update([
            'status' => 'in_progress'
        ]);

        return redirect()->back()->with('success', 'ยกเลิกเรียบร้อยแล้ว');
    }


    private function qerydataproject($id){
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
            'quotation.quotationMaterials'
        ])->find($id);
        return $project;
    }




    public function confirmworkcompletedpage($id){
        
        $project = $this->qerydataproject($id);

        return view('admin.projects.installing.confirmworkcompletedpage',compact('project'));
    }

    public function uploadAfterImage(Request $request, $need_id)
    {

        $need = CustomerNeed::find($need_id);

        if ($request->hasFile('imageafter') && $request->file('imageafter')->isValid()) {
            $file = $request->file('imageafter');
            $imageData = file_get_contents($file->getRealPath());

            $need->update([
                'imageafter' => $imageData
            ]);

            return back()->with('success', 'อัปโหลดภาพหลังติดตั้งเรียบร้อยแล้ว');
        }

        return back()->with('error', 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์');
    }

    public function deleteAfterImage($need_id)
    {
        $need = CustomerNeed::find($need_id);
        
        $need->update([
            'imageafter' => null
        ]);

        return back()->with('success', 'ลบภาพหลังติดตั้งเรียบร้อยแล้ว');
    }



    public function updatestatusinstalling($id)
    {
        $project = Project::find($id);

        $project->update([
            'status' => 'installing'
        ]);

        return redirect()->route('admin.projects.index', $project->id)->with('success', 'อัปเดตสถานะเป็น กำลังติดตั้ง');
    }



    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete(); 

        return back()->with('success', 'ลบงานเรียบร้อยแล้ว');
    }

    public function restore($id)
    {
        $project = Project::withTrashed()->find($id);
        $project->restore();

        return back()->with('success', 'กู้คืนงานเรียบร้อยแล้ว');
    }


    public function cancelWithdrawal($id)
    {
        $project = Project::find($id);
        
        if (!$project) {
            return redirect()->back()->with('error', 'ไม่พบงานนี้');
        }

        $withdrawals = Withdrawal::with('items')->where('project_id', $project->id)->get();

        if ($withdrawals->isEmpty()) {
            return redirect()->back()->with('error', 'ไม่พบประวัติการเบิกวัสดุสำหรับงานนี้');
        }

        MaterialLog::where('project_id', $id)->where('source', 'withdraw')->delete();

        foreach ($withdrawals as $withdrawal) {
            foreach ($withdrawal->items as $item) {
                $price = Price::where('material_id', $item->material_id)
                            ->where('lot', $item->lot)
                            ->first();

                if ($price) {
                    $price->increment('quantity', $item->quantity);

                    MaterialLog::create([
                        'material_id' => $item->material_id,
                        'price_id'    => $price->id,
                        'user_id'     => Auth::id(),
                        'direction'   => 'in',
                        'quantitylog' => $item->quantity,
                        'source'      => 'return_material',        
                        'note'        => 'ยกเลิกการเบิก คืนสต็อก',
                    ]);
                }
            }
            
            $withdrawal->items()->delete(); 
            $withdrawal->delete(); 
        }

        $project->update([
            'status' => 'ready_to_withdraw'
        ]);

        return redirect()->back()->with('success', 'ยกเลิกการเบิกวัสดุ คืนสต็อก และเปลี่ยนสถานะเป็นพร้อมเบิกเรียบร้อยแล้ว');
    }

    public function withdrawtoolspage($id)
    {
        $project = Project::find($id);
        $users = User::all();

        $toolsstock = Price::whereHas('material', function($q){
            $q->where('material_type', 'เครื่องมือช่าง');
        })->where('quantity', '>', 0)
        ->with(['material.toolItem.toolType', ])->get();

        return view('admin.projects.withdraw.withdrawtoolspage',compact('project','users','toolsstock'));
    }

    public function withdrawtoolsstore(Request $request,$id)
    {
        $selecteditems = $request->input('selected_items');
        $customqtys = $request->input('custom_qty');
        $withdrawnby = $request->input('withdrawn_by');

        if (empty($selecteditems) or empty($withdrawnby)) {
            return redirect()->back()->with('error', 'กรุณาเลือกเครื่องมือและช่างผู้เบิก');
        }

        $project = Project::find($id);

        $withdrawal = Withdrawal::create([
            'project_id'   => $project->id,
            'withdrawn_by' => $withdrawnby,
            'recorded_by'  => Auth::id(),
        ]);

        foreach($selecteditems as $price_id){
            $price = Price::find($price_id);

            if($price){
                $qtytowithdraw = $customqtys[$price_id] ?? 1;
                
                if($qtytowithdraw > 0 && $price->quantity >= $qtytowithdraw){
                    $price->decrement('quantity', $qtytowithdraw);

                    MaterialLog::create([
                        'material_id' => $price->material_id,
                        'price_id'    => $price->id,
                        'user_id'     => Auth::id(),
                        'direction'   => 'out',
                        'quantitylog' => $qtytowithdraw, 
                        'project_id'  => $project->id,
                        'source'      => 'withdraw',
                    ]);

                    WithdrawalItem::create([
                        'withdrawal_id' => $withdrawal->id,
                        'material_id'   => $price->material_id,
                        'lot'           => $price->lot,
                        'quantity'      => $qtytowithdraw,
                    ]);

                }

            }
        }

        return redirect()->route('admin.projects.withdrawdetails', $project->id)->with('success', 'เบิกเครื่องมือช่างสำเร็จ');
    }


    public function managewithdrawals()
    {
        $withdrawals = Withdrawal::whereHas('project')->with([
            'project.customer',
            'project.projectname',
        ])->latest()->get();


        $groupwithdrawals = $withdrawals->groupBy('project_id');

        $statusColors = [
            'pending_survey' => ['#D4AF37', 'นัดสำรวจ'],
            'waiting_survey' => ['#FF8C00', 'รอวันสำรวจ'],
            'surveying' => ['#1E90FF', 'กำลังสำรวจ'],
            'pending_quotation' => ['#E91E63', 'รอเสนอราคา'],
            'waiting_approval' => ['#9C27B0', 'รออนุมัติ'],
            'approved' => ['#78d37b', 'อนุมัติและชำระเงินแล้ว'],
            'material_planning' => ['#00CED1', 'วางแผนวัสดุ'],
            'waiting_purchase' => ['#FF4500', 'รอสั่งซื้อ'],
            'ready_to_withdraw' => ['#008080', 'พร้อมเบิก'],
            'materials_withdrawn' => ['#8B4513', 'เบิกวัสดุแล้ว'],
            'installing' => ['#4CAF50', 'กำลังติดตั้ง'],
            'completed' => ['#708090', 'เสร็จสิ้น'],
            'cancelled' => ['#DC143C', 'ยกเลิก']
        ];


        return view('admin.projects.withdraw.managewithdrawals', compact('groupwithdrawals', 'statusColors'));
    }

    


    public function withdrawdetails($id)
    {
        $project = Project::with(['projectname', 'customer'])->find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลงานนี้');
        }

        $currentItems = WithdrawalItem::whereHas('withdrawal', function ($q) use ($id) {
                $q->where('project_id', $id);
            })
            ->where('quantity', '>', 0)
            ->with([
                'withdrawal.withdrawnBy',
                'material.aluminiumItem.aluminiumType',
                'material.aluminiumItem.aluminumSurfaceFinish',
                'material.glassItem.glassType',
                'material.glassItem.colourItem',
                'material.accessoryItem.accessoryType',
                'material.consumableItem.consumabletype',
                'material.toolItem.toolType',
            ])->get();

        $materialLogs = MaterialLog::with([
                'material.aluminiumItem.aluminiumType',
                'material.aluminiumItem.aluminumSurfaceFinish',
                'material.glassItem.glassType',
                'material.glassItem.colourItem',
                'material.accessoryItem.accessoryType',
                'material.consumableItem.consumabletype',
                'material.toolItem.toolType',
                'user',
                'price.aluminiumlength',
                'price.glassSize',
            ])
            ->where('project_id', $id)
            ->whereIn('source', ['withdraw', 'return_material', 'return_tool', 'issue_refill', 'manual'])
            ->orderBy('created_at', 'desc')
            ->get();

        $returnedMaterialIds = MaterialLog::where('project_id', $id)
            ->whereIn('source', ['return_material'])
            ->pluck('material_id')
            ->unique()
            ->toArray();

        

        return view('admin.projects.withdraw.withdrawdetails',
            compact('project', 'currentItems', 'materialLogs','returnedMaterialIds'));
    }


    public function returnMaterialsPage($id)
    {
        $project = Project::with(['projectname', 'customer'])->find($id);
        $allItems = WithdrawalItem::whereHas('withdrawal', function ($q) use ($id) {
            $q->where('project_id', $id);
        })->with([
            'material.aluminiumItem.aluminiumType',
            'material.aluminiumItem.aluminumSurfaceFinish',
            'material.glassItem.glassType',
            'material.glassItem.colourItem',
            'material.accessoryItem.accessoryType',
            'material.consumableItem.consumabletype',
        ])->get();
    
        $aluminiumItems  = $allItems->filter(fn($i) => $i->material?->material_type == 'อลูมิเนียม');
        $glassItems      = $allItems->filter(fn($i) => $i->material?->material_type == 'กระจก');
        $accessoryItems  = $allItems->filter(fn($i) => $i->material?->material_type == 'อุปกรณ์เสริม');
        $consumableItems = $allItems->filter(fn($i) => $i->material?->material_type == 'วัสดุสิ้นเปลือง');
    
        return view('admin.projects.withdraw.returnmaterials', compact(
            'project',
            'aluminiumItems',
            'glassItems',
            'accessoryItems',
            'consumableItems'
        ));
    }

    public function storeReturnMaterials(Request $request, $id)
    {
        $project = Project::find($id);

        $groups = $request->input('return_qty', []);

        foreach ($groups as $type => $items) {
            foreach ($items as $withdrawalItemId => $qty) {
                $qty = (int) $qty;

                if ($qty <= 0) {
                    continue;
                }

                $materialId = $request->input("return_material_id.{$type}.{$withdrawalItemId}");
                $lot        = $request->input("return_lot.{$type}.{$withdrawalItemId}");

                $withdrawalItem = WithdrawalItem::find($withdrawalItemId);

                if (!$withdrawalItem) {
                    continue;
                }

                $qty = min($qty, $withdrawalItem->quantity);

                $withdrawalItem->quantity -= $qty;
                $withdrawalItem->save();

                $price = Price::where('material_id', $materialId)
                    ->where('lot', $lot)
                    ->first();

                if ($price) {
                    $price->increment('quantity', $qty);

                    MaterialLog::create([
                        'material_id' => $materialId,
                        'price_id'    => $price->id,
                        'user_id'     => Auth::id(),
                        'direction'   => 'in',
                        'project_id'  => $project->id,
                        'quantitylog' => $qty,
                        'source'      => 'return_material', 
                        'note'        => 'คืนวัสดุที่เหลือจากโครงการ', 
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.projects.withdrawdetails', $project->id)
            ->with('success', 'คืนวัสดุเข้าสต็อกเรียบร้อยแล้ว');
    }
 
    public function returnTool($withdrawalItemId)
    {
        $item = WithdrawalItem::find($withdrawalItemId);

        
    
        if (!$item) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลรายการนี้');
        }
        
        $projectId = $item->withdrawal->project_id;
    
        $price = Price::where('material_id', $item->material_id)
            ->where('lot', $item->lot)
            ->first();
    
        if ($price) {
            $price->increment('quantity', $item->quantity);
    
            MaterialLog::create([
                'material_id' => $item->material_id,
                'price_id'    => $price->id,
                'user_id'     => Auth::id(),
                'direction'   => 'in',
                'quantitylog' => $item->quantity,
                'project_id'  => $projectId,
                'source'      => 'return_tool',         
                'note'        => 'คืนเครื่องมือช่างเข้าคลัง',
            ]);
        }
    
    
        $item->delete();
    
        return redirect()->route('admin.projects.withdrawdetails', $projectId)->with('success', 'คืนเครื่องมือช่างเข้าคลังเรียบร้อยแล้ว');
    }

    
    public function editWithdrawalItemPage($id)
    {
        $item = WithdrawalItem::with([
            'withdrawal.withdrawnBy',
            'material.aluminiumItem.aluminiumType',
            'material.aluminiumItem.aluminumSurfaceFinish',
            'material.glassItem.glassType',
            'material.glassItem.colourItem',
            'material.accessoryItem.accessoryType',
            'material.consumableItem.consumabletype',
            'material.toolItem.toolType',
        ])->find($id);

        $mat    = $item->material;
        $detail = '-';
        if ($mat) {
            if ($mat->aluminiumItem) {
                $detail = ($mat->aluminiumItem->aluminiumType->name ?? '-')
                        . ' สี ' . ($mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-');
            } elseif ($mat->glassItem) {
                $detail = 'กระจก ' . ($mat->glassItem->glassType->name ?? '-')
                        . ' สี ' . ($mat->glassItem->colourItem->name ?? '-');
            } elseif ($mat->accessoryItem) {
                $detail = 'อุปกรณ์เสริม: ' . ($mat->accessoryItem->accessoryType->name ?? '-');
            } elseif ($mat->consumableItem) {
                $detail = 'วัสดุสิ้นเปลือง: ' . ($mat->consumableItem->consumabletype->name ?? '-');
            } elseif ($mat->toolItem) {
                $detail = 'เครื่องมือ: ' . ($mat->toolItem->toolType->name ?? '-');
            }
        }

        $logs = WithdrawalItemLog::with('editor')->where('withdrawal_item_id', $id)->latest()->get();

        return view('admin.projects.withdraw.editwithdrawalitem', compact('item', 'detail', 'logs'));
    }

    public function editWithdrawalItem(Request $request, $id)
    {
        $item      = WithdrawalItem::find($id);
        $projectId = $item->withdrawal->project_id;
        $oldQty    = $item->quantity;
        $newQty    = (int) $request->quantity;
        $diff      = $newQty - $oldQty;

        if ($diff != 0) {
            $price = Price::where('material_id', $item->material_id)
                ->where('lot', $item->lot)
                ->first();

            if ($price) {
                if ($diff > 0) {
                    $price->decrement('quantity', $diff);
                } else {
                    $price->increment('quantity', abs($diff));
                }

                MaterialLog::create([
                    'material_id' => $item->material_id,
                    'price_id'    => $price->id,
                    'user_id'     => Auth::id(),
                    'direction'   => $diff > 0 ? 'out' : 'in',
                    'quantitylog' => abs($diff),
                    'project_id'  => $projectId,
                    'source'      => 'manual',
                    'note'        => 'แก้ไขจำนวน: ' . $request->reason,
                ]);
            }
        }

        WithdrawalItemLog::create([
            'withdrawal_item_id' => $item->id,
            'old_quantity'       => $oldQty,
            'new_quantity'       => $newQty,
            'reason'             => $request->reason,
            'edited_by'          => Auth::id(),
        ]);

        $item->update(['quantity' => $newQty]);

        return redirect()->route('admin.projects.edit_withdrawal_item_page', $id)->with('success', 'แก้ไขจำนวนเรียบร้อยแล้ว');
    }



    public function returnHistory($id)
    {
        $project = Project::with(['projectname', 'customer'])->find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลงานนี้');
        }

        $baseWith = [
            'material.aluminiumItem.aluminiumType',
            'material.aluminiumItem.aluminumSurfaceFinish',
            'material.glassItem.glassType',
            'material.glassItem.colourItem',
            'material.accessoryItem.accessoryType',
            'material.consumableItem.consumabletype',
            'material.toolItem.toolType',
            'user',
            'price',
        ];

        $returnOnlyLogs = MaterialLog::with($baseWith)->where('project_id', $id)->where('direction', 'in')->whereIn('source', ['return_material', 'return_tool'])->latest()->get();

        $issueRefillLogs = MaterialLog::with($baseWith)->where('project_id', $id)->where('source', 'issue_refill')->latest()->get();

        return view('admin.projects.withdraw.returnhistory',compact('project', 'returnOnlyLogs', 'issueRefillLogs'));
    }

    public function editHistory($id)
    {
        $project = Project::with(['projectname', 'customer'])->find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลงานนี้');
        }

        $editLogs = MaterialLog::with([
            'material.aluminiumItem.aluminiumType',
            'material.aluminiumItem.aluminumSurfaceFinish',
            'material.glassItem.glassType',
            'material.glassItem.colourItem',
            'material.accessoryItem.accessoryType',
            'material.consumableItem.consumabletype',
            'material.toolItem.toolType',
            'user',
            'price',
        ])->where('project_id', $id)->where('source', 'manual')->latest()->get();

        return view('admin.projects.withdraw.edithistory',compact('project', 'editLogs'));
    }


    public function cancellinstalling($id)
    {
        $project = Project::find($id);

        $project->update([
            'status' => 'materials_withdrawn' 
        ]);


        return redirect()->route('admin.projects.index',$project->id)->with('success','ยกเลิกสถานะสำเร็จ');

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

        $pdf = Pdf::loadView('admin.pdf.quotation', compact(
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

        $pdf = Pdf::loadView('admin.pdf.receipt', compact(
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

        $pdf = Pdf::loadView('admin.pdf.tax_invoice', compact(
            'project', 'sumvattotal', 'pricevat', 'sumincome'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('ใบกำกับภาษี-' . ($project->tax_invoice_number ?? $project->id) . '.pdf');
    }


    






    

    




}
