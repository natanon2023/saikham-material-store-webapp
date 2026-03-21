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



    
}
