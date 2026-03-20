<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

class ProjectController extends Controller
{
   //หน้าเริ่ม
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



        return view("technician.projects.index", compact('project', 'statusColors', 'currentStatus'));
    }

    //สำรวจ
    public function updatestatussurveying(Request $request, $id)
    {
        $project = Project::find($id);
        $project->update([
            'status' => 'surveying'
        ]);

        return redirect()->route('technician.projects.formsurveying', $project->id)->with('success', 'กำลังสำรวจ');
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

       


        return view('technician.projects.survey.surveying', compact('project'));
    }

    public function formprojectimage($id)
    {
        $project = Project::find($id);
        $imgtypename = ImageTypeName::all();

        return view('technician.projects.survey.formprojectimage', compact('project', 'imgtypename'));
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

        return redirect()->route('technician.projects.formsurveying', $project->id)->with('success', 'เพิ่มรูปภาพโครงการสำเร็จ');
    }


    public function formcrateimgtype($id)
    {
        $project = Project::find($id);
        $imgtype = ImageTypeName::withTrashed()->get();
        return view('technician.projects.survey.formcrateimgtype', compact('imgtype','project'));
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


    public function formeditprojectimage($id)
    {

        $projectImage = Projectimages::find($id);
        $imgtypename = ImageTypeName::all();

        return view('technician.projects.survey.editprojectimage', compact('projectImage', 'imgtypename'));
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

        return redirect()->route('technician.projects.formsurveying', $projectImage->project_id)->with('success', 'แก้ไขรูปภาพสำเร็จ');
    }

    public function deleteprojectimage($id)
    {
        $projectimge = Projectimages::find($id);

        $projectimge->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลภาพสำเร็จ');
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

        return view('technician.projects.survey.formcustomerneed', compact('project', 'productset', 'projectimg'));
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

        return redirect()->route('technician.projects.formsurveying', $project->id)->with('success', 'เพิ่มรายการความต้องการลูกค้าสำเร็จ');
    }

    public function deletecustomerneed($id)
    {
        $customerneed = CustomerNeed::find($id);

        $customerneed->delete();

        return redirect()->back()->with('success', 'ลบรายการความต้องการสำเร็จ');
    }

    public function editformcustomerneed($id)
    {

        $customerNeed = CustomerNeed::find($id);

        $project = Project::find($customerNeed->project_id);

        $productset = ProductSet::all();
        $projectimg = Projectimages::where('project_id', $project->id)->get();

        return view('technician.projects.survey.editformcustomerneed', compact('customerNeed', 'project', 'productset', 'projectimg'));
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




    //ไปจากสำรวจ
    public function updatestatuspendingquotation(Request $request)
    {

        $project = Project::find($request->id);
        $project->update([
            'status' => 'pending_quotation'
        ]);

        return redirect()->route('technician.projects.index', $project->id)->with('success', 'รอเสนอราคา');
    }













    public function show($id)
    {
        $project = Project::with([
            'projectname',
            'customer.province',
            'customer.amphure',
            'customer.tambon',
            'projectimage',
            'customerneed.productset.productSetName'
        ])->where('id', $id)->where('assigned_surveyor_id', Auth::id())->first(); 

        $statuses = [
            'waiting_survey',
            'pending_survey',
            'surveying',
            'pending_quotation',
            'waiting_approval',
            'approved',
            'material_planning',
            'waiting_purchase',
            'ready_to_withdraw',
            'materials_withdrawn',
            'installing',
            'completed',
            'cancelled'
        ];

        $statusesthiname = $this->getStatusName($project->status);
        $statusColor = $this->getStatusColor($project->status);

        return view('technician.projects.show', compact('project','statusesthiname','statusColor'));
    }


    private function getStatusName($status) {
        return match ($status){
            'waiting_survey'      => 'รอวันสำรวจ',
            'pending_survey'      => 'นัดสำรวจ',
            'surveying'           => 'กำลังสำรวจ',
            'pending_quotation'   => 'รอเสนอราคา',
            'waiting_approval'    => 'รออนุมัติ',
            'approved'            => 'อนุมัติและชำระเงินแล้ว',
            'material_planning'   => 'วางแผนวัสดุ',
            'waiting_purchase'    => 'รอสั่งซื้อ',
            'ready_to_withdraw'   => 'พร้อมเบิก',
            'materials_withdrawn' => 'เบิกวัสดุแล้ว',
            'installing'          => 'กำลังติดตั้ง',
            'completed'           => 'เสร็จสิ้น',
            'cancelled'           => 'ยกเลิก',
            default               => 'อื่นๆ'
        };
    }

    private function getStatusColor($status)
    {
        return match ($status) {
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
            default                            => '#2196F3'  
        };
    }

    public function close($id)
    {
        $project = Project::where('id', $id)
            ->where('assigned_surveyor_id', Auth::id())
            ->first();

        $project->update([
            'status' => 'completed'
        ]);

        return redirect()
            ->route('technician.dashboard')
            ->with('success', 'ปิดงานแล้ว');
    }


    





    

  

    

    

    

    


}
