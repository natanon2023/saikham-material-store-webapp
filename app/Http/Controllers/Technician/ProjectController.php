<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Projectimages;
use App\Models\CustomerNeed;
use App\Models\ProductSet;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
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
            'projectimage',
            'customerneed.creator',
            'customerneed.productset.productSetName'

        ])->find($id);

        return view('technician.projects.survey.surveying', compact('project'));
    }



    public function updatestatussurveying(Request $request, $id)
    {
        $project = Project::find($id);
        $project->update([
            'status' => 'surveying'
        ]);

        return redirect()->route('technician.projects.formsurveying', $project->id)->with('success', 'กำลังสำรวจ');
    }

    public function updatestatuspendingquotation(Request $request)
    {

        $project = Project::find($request->id);
        $project->update([
            'status' => 'pending_quotation'
        ]);
        return redirect()->route('technician.projects.show',$project->id)->with('success', 'รอเสนอราคา');
    }

    public function formprojectimage($id)
    {
        $project = Project::find($id);
        return view('technician.projects.survey.formprojectimage', compact('project'));
    }

    public function createprojectimage(Request $request)
    {

        $project = Project::find($request->project_id);

        $file = $request->file('image_path');
        $imageData = file_get_contents($file->getRealPath());

        Projectimages::create([
            'project_id' => $project->id,
            'image_path' => $imageData,
            'image_type' => $request->image_type,
            'description' => $request->description
        ]);

        return redirect()->route('technician.projects.formsurveying', $project->id)->with('success', 'เพิ่มรูปภาพโครงการสำเร็จ');
    }

    public function deleteprojectimage($id){
           $projectimge = Projectimages::find($id);

           $projectimge->delete();

           return redirect()->back()->with('success','ลบข้อมูลภาพสำเร็จ');
    }

    public function formcustomerneed($id)
    {
        $project = Project::find($id);
        $productset = ProductSet::with([
            'productSetName'
        ])->get();

        return view('technician.projects.survey.formcustomerneed', compact('project', 'productset'));
    }


    public function addcustomerneed(Request $request)
    {

        $project = Project::find($request->project_id);

        CustomerNeed::create([
            'project_id' => $project->id,
            'product_set_id' => $request->product_set_id,
            'location' => $request->location,
            'quantity' => $request->quantity,
            'high' => $request->high,
            'width' => $request->width,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('technician.projects.formsurveying', $project->id)->with('success', 'เพิ่มรายการความต้องการลูกค้าสำเร็จ');
    }


    public function deletecustomerneed($id){
        $customerneed = CustomerNeed::find($id);

        $customerneed->delete();

        return redirect()->back()->with('success','ลบรายการความต้องการสำเร็จ');
    }
}
