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

    public function formprojectimagedetail($id)
    {
        $project = Project::find($id);
        $imgtypename = ImageTypeName::all();

        return view('technician.projects.survey.formprojectimagedetail', compact('project', 'imgtypename'));
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

        return redirect()->route('technician.projects.alldetail', $project->id)->with('success', 'เพิ่มรูปภาพโครงการสำเร็จ');
    }


    public function formcustomerneeddetial($id)
    {
        $project = Project::find($id);
        $productset = ProductSet::with([
            'productSetName'
        ])->get();

        $projectimg = Projectimages::where('project_id', $id)->get();

        return view('technician.projects.survey.formcustomerneeddetial', compact('project', 'productset', 'projectimg'));
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

        return redirect()->route('technician.projects.alldetail', $project->id)->with('success', 'เพิ่มรายการความต้องการลูกค้าสำเร็จ');
    }




    public function updatestatuspendingquotation(Request $request)
    {

        $project = Project::find($request->id);
        $project->update([
            'status' => 'pending_quotation'
        ]);

        return redirect()->route('technician.projects.index', $project->id)->with('success', 'รอเสนอราคา');
    }


    public function updatestatusinstalling($id)
    {
        $project = Project::find($id);

        $project->update([
            'status' => 'installing'
        ]);

        return redirect()->route('technician.projects.index', $project->id)->with('success', 'อัปเดตสถานะเป็น กำลังติดตั้ง');
    }

    public function choosetypeissues($id){
        $projects = Project::find($id);
        return view('technician.projects.issues.choosetypeissues',compact('projects'));
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

        return view('technician.projects.issues.issues_create', compact('project', 'withdrawnItems', 'issues'));
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
            return redirect()->back()->with('error', 'จำนวนที่แจ้งเสียมากกว่าจำนวนที่มีอยู่');
        }
    
        $file = $request->file('image_data');
        $imageData = file_get_contents($file->getRealPath());
    
        $issue = ProjectIssue::create([
            'project_id'              => $project_id,
            'reported_by'             => Auth::id(),
            'category'                => 'material_problems',
            'description'             => $request->description,
            'status'                  => 'pending',
            'withdrawal_item_damaged' => $request->withdrawal_item_damaged,
            'damaged_amount'          => $request->damaged_amount,
        ]);
    
        IssueImage::create([
            'issue_id'   => $issue->id,
            'image_data' => $imageData,
        ]);
    
        $withdrawalItem->quantity -= $request->damaged_amount;
        $withdrawalItem->save();
    
        return redirect()->back()->with('success', 'รายงานปัญหาและปรับยอดวัสดุเรียบร้อยแล้ว');
    }



    public function generalissues($id){
        $project = Project::find($id);
        $issues = ProjectIssue::where('project_id', $id)->orderBy('created_at', 'desc')->get();
        return view('technician.projects.issues.generalissues',compact('project','issues'));
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
        $issues = ProjectIssue::whereHas('project')
            ->where('reported_by', Auth::id())
            ->with([
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

        return view('technician.projects.issues.manageproblemsindex', compact('groupedIssues', 'statusColors'));
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
            ])->where('project_id', $project_id)->where('reported_by', Auth::id());


        $issues = $query->latest()->get();

        return view('technician.projects.issues.issuedetail', compact('project', 'issues'));
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

        return view('technician.projects.issues.showissuedetail', compact('issue'));
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

        return view('technician.projects.issues.issues_edit', compact('issue', 'project', 'withdrawnItems'));
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

        return redirect()->route('technician.projects.issues.detail', $issue->project_id)->with('success', 'แก้ไขข้อมูลปัญหาเรียบร้อยแล้ว');
    }

    public function refillIssue($id)
    {
        $issue = ProjectIssue::find($id);
        $project = Project::find($issue->project_id);
        
        return view('technician.projects.issues.issues_refill', compact('issue', 'project'));
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
            'refilled_amount' => $request->refill_amount,
        ]);
    
        return redirect()->route('technician.projects.issues.detail', $issue->project_id)
            ->with('success', 'เติมวัสดุและอัปเดตสถานะเสร็จสิ้นเรียบร้อยแล้ว');
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
            'refilled_amount' => null,
        ]);
    
        return redirect()->back()->with('success', 'ยกเลิกการเติมวัสดุเรียบร้อยแล้ว สามารถกดเติมใหม่ได้');
    }



    public function updateIssuegeneralproblems(Request $request, $id)
    {
        $issue = ProjectIssue::find($id);

        $issue->update([
            'description' => $request->description
        ]);

        return redirect()->route('technician.projects.issues.detail', $issue->project_id)->with('success', 'แก้ไขข้อมูลปัญหาเรียบร้อยแล้ว');
    }


    public function updateresolved($id){
        $issue = ProjectIssue::find($id);

        $issue->update([
            'status' => 'resolved'
        ]);

        return redirect()->route('technician.projects.issues.detail', $issue->project_id)->with('success', 'แก้ไขข้อมูลปัญหาเรียบร้อยแล้ว');

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

        return view('technician.projects.installing.confirmworkcompletedpage',compact('project'));
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


    public function updatestatuscompleted($id)
    {
        $project = Project::find($id);

        $project->update([
            'status' => 'completed'
        ]);

        return redirect()->route('technician.projects.index', $project->id)->with('success', 'อัปเดตสถานะเป็น เสร็จสมบูรณ์');
    }



    public function cancellinstalling($id)
    {
        $project = Project::find($id);

        $project->update([
            'status' => 'materials_withdrawn' 
        ]);


        return redirect()->route('technician.projects.index',$project->id)->with('success','ยกเลิกสถานะสำเร็จ');

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


        $myId          = Auth::id();
        $isSurveyor    = ($project->assigned_surveyor_id == $myId);
        $installerCount = $project->installers->count();
        $installerCount = max($installerCount, 1);
        $isInstaller   = $project->installers->contains('id', $myId);

        $surveyPay      = $isSurveyor ? ($project->labor_cost_surveying ?? 0) : 0;
        $installPay     = $isInstaller
            ? (($project->daily_labor_rate ?? 0) * ($project->estimated_work_days ?? 0))
            : 0;
        $totalPay       = $surveyPay + $installPay;




        return view('technician.projects.alldetail.detailpage', compact(
            'project', 'customerall',
             'projectname', 'technician', 'statuspayment',
              'satatusopen', 'statusmaterialplanningopen',
              'satatuswaiting','satatusonline','statusopendatework','myId'
              ,'isSurveyor','installerCount','isInstaller','surveyPay','totalPay','installPay'
        ));
    }


    public function managewithdrawals()
    {
        $withdrawals = Withdrawal::whereHas('project')
            ->where('withdrawn_by', Auth::id()) 
            ->with([
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


        return view('technician.projects.withdraw.managewithdrawals', compact('groupwithdrawals', 'statusColors'));
    }

    

    public function withdrawdetails($id)
    {
        $project = Project::with(['projectname', 'customer'])->find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลงานนี้');
        }

        $currentItems = WithdrawalItem::whereHas('withdrawal', function ($q) use ($id) {
                $q->where('project_id', $id)->where('withdrawn_by', Auth::id()); 
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
            ->where('user_id', Auth::id()) 
            ->whereIn('source', ['return_material'])
            ->pluck('material_id')
            ->unique()
            ->toArray();

        return view('technician.projects.withdraw.withdrawdetails',
            compact('project', 'currentItems', 'materialLogs','returnedMaterialIds'));
    }

    public function withdrawtoolspage($id)
    {
        $project = Project::find($id);
        $users = User::all();

        $toolsstock = Price::whereHas('material', function($q){
            $q->where('material_type', 'เครื่องมือช่าง');
        })->where('quantity', '>', 0)
        ->with(['material.toolItem.toolType', ])->get();

        return view('technician.projects.withdraw.withdrawtoolspage',compact('project','users','toolsstock'));
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

        return view('technician.projects.withdraw.returnhistory',compact('project', 'returnOnlyLogs', 'issueRefillLogs'));
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

        return view('technician.projects.withdraw.edithistory',compact('project', 'editLogs'));
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
    
        return view('technician.projects.withdraw.returnmaterials', compact(
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

        return redirect()->route('technician.projects.withdrawdetails', $project->id)->with('success', 'คืนวัสดุเข้าสต็อกเรียบร้อยแล้ว');
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

        return view('technician.projects.withdraw.editwithdrawalitem', compact('item', 'detail', 'logs'));
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
    
        return redirect()->route('technician.projects.edit_withdrawal_item_page', $id)
            ->with('success', 'แก้ไขจำนวนเรียบร้อยแล้ว');
    }

    public function withdrawtoolsstore(Request $request, $id)
    {
        $selecteditems = $request->input('selected_items');
        $customqtys    = $request->input('custom_qty', []);

        if (empty($selecteditems)) {
            return redirect()->back()->with('error', 'กรุณาเลือกเครื่องมืออย่างน้อย 1 รายการ');
        }

        $project = Project::find($id);

        $withdrawal = Withdrawal::create([
            'project_id'   => $project->id,
            'withdrawn_by' => Auth::id(),
            'recorded_by'  => Auth::id(),
        ]);

        foreach ($selecteditems as $price_id) {
            $price = Price::find($price_id);

            if ($price) {
                $qtytowithdraw = $customqtys[$price_id] ?? 1;

                if ($qtytowithdraw > 0 && $price->quantity >= $qtytowithdraw) {
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

        return redirect()->route('technician.projects.withdrawdetails', $project->id)->with('success', 'เบิกเครื่องมือช่างสำเร็จ');
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
    
        return redirect()->route('technician.projects.withdrawdetails', $projectId)->with('success', 'คืนเครื่องมือช่างเข้าคลังเรียบร้อยแล้ว');
    }
















   


   
    


    





    

  

    

    

    

    


}
