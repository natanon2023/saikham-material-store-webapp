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
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index($id)
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

        $currentStatus = $statusColors[$project->status] ?? ['#ccc', 'ไม่ระบุ'];



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
            'customerneed.productset.productSetName',
        ])->find($id);

        return view('admin.projects.survey.surveying', compact('project'));
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


        $satatuswaiting =[
            'waiting_survey',
            'pending_survey',
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




        return view('admin.projects.alldetail.detailpage', compact('project', 'customerall', 'projectname', 'technician', 'statuspayment', 'satatusopen', 'statusmaterialplanningopen','satatuswaiting','satatusonline','statusopendatework'));
    }

    public function addautersurver(Request $request)
    {
        $request->validate([
            'homeimg' => 'nullable|image|max:10240',
        ]);

        $project = Project::find($request->id);
        $updateData = [
            'estimated_work_days' => $request->estimated_work_days,
            'daily_labor_rate'    => $request->daily_labor_rate,
        ];

        if ($request->hasFile('homeimg')) {
            $file = $request->file('homeimg');

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

        $sumProductTotal = 0;
        foreach ($project->customerneed as $need) {
            $sumProductTotal += ($need->quantity * $need->calculated_total);
        }

        $totalExpenses = 0;
        foreach ($project->projectexpenses as $expense) {
            $totalExpenses += $expense->amount;
        }

        $totalLabor = $project->labor_cost_surveying + ($project->estimated_work_days * $project->daily_labor_rate);

        $sumtotal = $sumProductTotal + $totalExpenses + $totalLabor;
        $sevic = $sumtotal * 0.20;
        $sumincome = $sumtotal + $sevic;
        $pricevat = $sumincome * 0.07;
        $grand_total = $sumincome + $pricevat;

        $quotation = Quotation::create([
            'project_id'    => $project->id,
            'total_product_amount' => $sumProductTotal,
            'total_expense_amount' => $totalExpenses,
            'total_labor_amount'   => $totalLabor,
            'service_charge_amount' => $sevic,
            'vat_amount'    => $pricevat,
            'grand_total'   => $grand_total,
        ]);

        foreach ($project->customerneed as $need) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'item_name'    => $need->productset->productSetName->name,
                'description'  => "ขนาด " . $need->width . " x " . $need->height . " ซม.",
                'qty'          => $need->quantity,
                'unit_price'   => $need->calculated_total,
                'total_price'  => $need->quantity * $need->calculated_total,
                'item_type'    => 'product'
            ]);

            foreach ($need->productset->productsetitem as $item) {
                $mat = $item->material;
                $matDetail = $mat->name ?? '-';
                if ($mat->aluminiumItem) {
                    $matDetail = ($mat->aluminiumItem->aluminiumType->name ?? '') . " สี " . ($mat->aluminiumItem->aluminumSurfaceFinish->name ?? '');
                } elseif ($mat->glassItem) {
                    $matDetail = ($mat->glassItem->glassType->name ?? '') . " สี " . ($mat->glassItem->colourItem->name ?? '');
                } elseif ($mat->accessoryItem) {
                    $matDetail = $mat->accessoryItem->accessoryType->name ?? '';
                } elseif ($mat->consumableItem) {
                    $matDetail = $mat->consumableItem->consumabletype->name ?? '';
                }

                QuotationMaterial::create([
                    'quotation_id'  => $quotation->id,
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

        $project->update([
            'status' => 'waiting_approval'
        ]);

        return redirect()->route('admin.projects.alldetail', $project->id)->with('success', 'บันทึกใบเสนอราคาและล็อคข้อมูลสำเร็จ รอลูกค้าอนุมัติ');
    }

    public function reviseQuotation($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลโปรเจกต์');
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
            'quotation'
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
                    } else {
                        $flatlen = 6;
                        $qtyuse = ceil($totalneedlen / $flatlen);
                        $selectprice = 300;
                        $selectlot = 'ไม่มีของหรือขนาดไม่พอ';
                        $remark = "ใช้ราคาเหมา 300 บ. ต่อ เส้น ({$flatlen} ม.) จำนวน {$qtyuse} เส้น";
                        $item->calculated_price_id = null;
                    }
                } elseif ($type == 'กระจก') {
                    $needArea = $m_w * $m_h;
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
                        $sheetArea = $sheetW * $sheetH;

                        $qtyuse = ceil($needArea / $sheetArea) * 2;
                        $selectprice = $stock->price;
                        $selectlot   = $stock->lot;
                        $item->calculated_price_id = $stock->id;
                        $remark = "ใช้กระจก {$sheetW}×{$sheetH} ม. จำนวน {$qtyuse} แผ่น";
                    } else {
                        $flatW = 2;
                        $flatH = 2;
                        $flatArea = $flatW * $flatH;
                        $qtyuse = ceil($needArea / $flatArea) * 2;
                        $selectprice = 400;
                        $selectlot   = 'ไม่มีของหรือขนาดไม่พอ';
                        $item->calculated_price_id = null;
                        $remark = "ใช้กระจกเหมา {$flatW}×{$flatH} ม. 400 บ. ต่อ แผ่น จำนวน {$qtyuse} แผ่น";
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
                }

                $total_item_price = $qtyuse * $selectprice;

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


    public function addbid($id)
    {
        $project = $this->getCalculatedProject($id);
        return view('admin.projects.bid.addbid', compact('project'));
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

        return view('admin.projects.bid.addbiddocument', compact('project','statusopenqtc'));
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
        $project = $this->getCalculatedProject($id);

        return view('admin.projects.materialplanning.materialplanningpage', compact('project'));
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
                'total_amount' => $grandTotal,
                'status' => 'pending'
            ]);

            foreach ($itemsToBuy as $item) {
                ProjectPurchaseItem::create([
                    'project_purchase_id' => $purchase->id,
                    'material_id' => $item->material_id,
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
        $project = $this->getCalculatedProject($id);

        $users = User::all();

        return view('admin.projects.withdraw.withdrawpage', compact('project', 'users'));
    }

    public function withdrawstore(Request $request, $id)
    {
        $project = $this->getCalculatedProject($id);

        $withdrawal = Withdrawal::create([
            'project_id'   => $project->id,
            'withdrawn_by' => $request->withdrawn_by,
            'recorded_by'  => Auth::id(),
        ]);

        foreach ($project->customerneed as $need) {
            foreach ($need->productset->productsetitem as $item) {

                $qtyToWithdraw = $item->calculated_qty;

                if ($qtyToWithdraw <= 0 || empty($item->calculated_price_id)) {
                    continue;
                }

                $price = Price::find($item->calculated_price_id);

                if ($price) {
                    $actualDeduct = min($price->sumquantity, $qtyToWithdraw);

                    if ($actualDeduct > 0) {

                        $price->decrement('sumquantity', $actualDeduct);

                        MaterialLog::create([
                            'material_id' => $item->material->id,
                            'price_id'    => $price->id,
                            'user_id'     => Auth::id(),
                            'direction'   => 'out',
                        ]);

                        WithdrawalItem::create([
                            'withdrawal_id' => $withdrawal->id,
                            'material_id'   => $item->material->id,
                            'lot'           => $price->lot,
                            'quantity'      => $actualDeduct,
                        ]);
                    }
                }
            }
        }

        $project->update([
            'status' => 'materials_withdrawn'
        ]);

        return redirect()->route('admin.projects.index', $project->id)->with('success', 'เบิกวัสดุสำเร็จเรียบร้อย');
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

        if ($request->damaged_amount > $withdrawalItem->quantity) {
            return redirect()->back()->with('error', 'จำนวนที่แจ้งเสียมากกว่าจำนวนที่มีอยู่');
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

        $events = $query->with(['projectname', 'customer'])->get()->flatMap(function ($pj) {
            $customerName = $pj->customer->first_name ?? 'ไม่ระบุลูกค้า';
            $projectName = $pj->projectname->name ?? 'ไม่มีชื่อโครงการ';
            $statusLabel = $this->getStatusLabel($pj->status);
            $color = $this->getStatusColor($pj->status);

            $items = [];

            if ($pj->survey_date) {
                $items[] = [
                    'id'    => $pj->id . '_survey',
                    'title' => "[สำรวจ] " . $customerName . " - " . $projectName,
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
                    'title' => "[ติดตั้ง] " . $customerName . " - " . $projectName . " (" . $statusLabel . ")",
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

        return view('admin.projects.adminfulleventcalendarpage', compact('events'));
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

        return $labels[$status] ?? 'ไม่ระบุสถานะ';
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

        return $colors[$status] ?? '#2196F3';
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

        $purchaseItems = [];
        $allComplete = true;

        if ($project != null && $project->projectPurchase != null) {
            foreach ($project->projectPurchase->items as $item) {

                $material = $item->material;

                if ($material != null) {
                    $sumInStock = Price::where('material_id', $material->id)->sum('quantity');
                    $item->current_stock = $sumInStock;
                    $item->required_qty = $item->quantity;

                    if ($sumInStock >= $item->quantity) {
                        $item->is_complete = true;
                    } else {
                        $item->is_complete = false;
                        $allComplete = false; 
                    }

                    $purchaseItems[] = $item;
                }
            }
        }

        if (count($purchaseItems) == 0) {
            $allComplete = false;
        }

        return view('admin.projects.materialplanning.restockmaterials', compact('project', 'purchaseItems', 'allComplete'));
    }

    public function processrestock(Request $request, $id)
    {
        $restockGroups = $request->input('restock_group');

        if ($restockGroups != null) {

            foreach ($restockGroups as $type => $data) {

                if (isset($data['material_ids'])) {

                    foreach ($data['material_ids'] as $material_id) {

                        $material = Material::find($material_id);

                        if ($material == null) {
                            continue;
                        }

                        $priceColumn = null;
                        $priceItemId = null;

                        if ($material->material_type == 'อลูมิเนียม') {

                            $aluminiumitem = AluminiumItem::find($material->aluminium_item_id);

                            if ($aluminiumitem != null) {
                                $length_meter = $data['length_meter'];

                                $aluminiumlength = AluminiumLength::where('aluminium_item_id', $aluminiumitem->id)->where('length_meter', $length_meter)->first();

                                if ($aluminiumlength == null) {
                                    $aluminiumlength = AluminiumLength::create([
                                        'aluminium_item_id' => $aluminiumitem->id,
                                        'length_meter'      => $length_meter
                                    ]);
                                }

                                $priceColumn = 'aluminium_length_id';
                                $priceItemId = $aluminiumlength->id;
                            }
                        } else if ($material->material_type == 'กระจก') {

                            $glassitem = GlassItem::find($material->glass_item_id);

                            if ($glassitem != null) {
                                $width_meter  = $data['width_meter'];
                                $length_meter = $data['length_meter'];
                                $thickness    = $data['thickness'];

                                $glasssize = GlassSize::where('glass_item_id', $glassitem->id)->where('width_meter', $width_meter)->where('length_meter', $length_meter)->where('thickness', $thickness)->first();

                                if ($glasssize == null) {
                                    $glasssize = GlassSize::create([
                                        'glass_item_id' => $glassitem->id,
                                        'width_meter'   => $width_meter,
                                        'length_meter'  => $length_meter,
                                        'thickness'     => $thickness
                                    ]);
                                }

                                $priceColumn = 'glass_size_id';
                                $priceItemId = $glasssize->id;
                            }
                        } else if ($material->material_type == 'อุปกรณ์เสริม') {
                            $priceColumn = 'accessory_item_id';
                            $priceItemId = $material->accessory_item_id;
                        } else if ($material->material_type == 'เครื่องมือช่าง') {
                            $priceColumn = 'tool_item_id';
                            $priceItemId = $material->tool_item_id;
                        } else if ($material->material_type == 'วัสดุสิ้นเปลือง') {
                            $priceColumn = 'consumable_item_id';
                            $priceItemId = $material->consumable_item_id;
                        }

                        if ($priceColumn != null && $priceItemId != null) {

                            $lotCount = Price::where($priceColumn, $priceItemId)
                                ->where('dealer_id', $data['dealer_id'])
                                ->count();

                            $lotNumber = $lotCount + 1;
                            $lotName = "ล็อตที่" . $lotNumber;

                            $currentStock = Price::where('material_id', $material->id)->sum('quantity');
                            $newSumQuantity = $currentStock + $data['qty'];

                            $price = Price::create([
                                'material_id' => $material->id,
                                $priceColumn  => $priceItemId,
                                'dealer_id'   => $data['dealer_id'],
                                'price'       => $data['price'],
                                'quantity'    => $data['qty'],
                                'lot'         => $lotName,
                                'sumquantity' => $newSumQuantity
                            ]);

                            MaterialLog::create([
                                'material_id' => $material->id,
                                'price_id'    => $price->id,
                                'user_id'     => Auth::id(),
                                'direction'   => 'in'
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.projects.restockpage', $id)->with('success', 'เติมสต็อกวัสดุสำเร็จ');
    }


    public function restockForm(Request $request, $id)
    {
        $selected_ids = $request->input('selected_materials');

        if ($selected_ids == null) {
            return redirect()->back()->with('error', 'กรุณาเลือกวัสดุอย่างน้อย 1 รายการ');
        }

        $project = Project::find($id);
        $dealers = Dealer::all();

        $materials_list = Material::whereIn('id', $selected_ids)->get();

        $groupedMaterials = [];

        foreach ($materials_list as $mat) {
            $name = "-";

            if ($mat->aluminiumItem != null) {
                $name = ($mat->aluminiumItem->aluminiumType->name ?? '-') . " (" . ($mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-') . ")";
            } else if ($mat->glassItem != null) {
                $name = ($mat->glassItem->glassType->name ?? '-') . " (" . ($mat->glassItem->colourItem->name ?? '-') . ")";
            } else if ($mat->accessoryItem != null) {
                $name = $mat->accessoryItem->accessoryType->name ?? '-';
            } else if ($mat->consumableItem != null) {
                $name = $mat->consumableItem->consumabletype->name ?? '-';
            }

            $mat->display_detail = $name;

            $type = $mat->material_type;

            if (!isset($groupedMaterials[$type])) {
                $groupedMaterials[$type] = [];
            }

            $groupedMaterials[$type][] = $mat;
        }

        return view('admin.projects.materialplanning.restockform', compact('project', 'groupedMaterials', 'dealers'));
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
        $issues = ProjectIssue::with([
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
        $issue = ProjectIssue::find($id);
        $withdrawalItem = WithdrawalItem::find($issue->withdrawal_item_damaged);

        $withdrawalItem->quantity += $request->refill_amount;
        $withdrawalItem->save();

        $issue->update([
            'status' => 'resolved',
            'refilled_amount' => $request->refill_amount
        ]);

        return redirect()->route('admin.projects.issues.detail', $issue->project_id)->with('success', 'เติมวัสดุและอัปเดตสถานะเสร็จสิ้นเรียบร้อยแล้ว');
    }

    public function undoRefillIssue($id)
    {
        $issue = ProjectIssue::find($id);
        $withdrawalItem = WithdrawalItem::find($issue->withdrawal_item_damaged);

        $withdrawalItem->quantity -= $issue->refilled_amount;
        $withdrawalItem->save();

        $issue->update([
            'status' => 'in_progress',
            'refilled_amount' => null
        ]);

        return redirect()->back()->with('success', 'ยกเลิกการเติมวัสดุเรียบร้อยแล้ว สามารถกดเติมใหม่ได้');
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
            'quotation'
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


    




}
