<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductSet;
use App\Models\Project;
use App\Models\Customer;

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

    public function projectDetail($id){
        $project = Project::with([
            'customer.province',
            'customer.amphure',
            'customer.tambon',
            'projectname',
            'projectimage',
            'customerneed.productset.productSetName',
            'projectexpenses.type'
        ])->find($id);

        $statusesthiname = $this->getStatusName($project->status);

        return view('customer.projectdetail', compact('project','statusesthiname'));
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



    
}
