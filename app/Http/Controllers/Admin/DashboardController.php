<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialLog;
use App\Models\Dealer;
use App\Models\AluminiumLength;
use App\Models\GlassSize;
use App\Models\AccessoryItem;
use App\Models\ToolItem;
use App\Models\ConsumableItem;
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
use App\Models\ProductSet;
use App\Models\ProductSetItem;
use App\Models\ToolType;
use App\Models\Withdrawal;
use App\Models\WithdrawalItem;
use App\Models\ProjectIssue;
use App\Models\IssueImage;
use App\Models\AluminiumItem;
use App\Models\GlassItem;
use App\Models\AssignedInstaller;
use App\Models\MaterialPrice;
use App\Models\Unit;
use App\Models\ProjectPurchase;
use App\Models\ProjectPurchaseItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\QuotationMaterial;
use App\Models\WithdrawalItemLog;
use Illuminate\Support\Facades\DB;
use App\Models\Price;
use Carbon\Carbon;

class DashboardController extends Controller
{
    

    public function index()
    {
        return view('admin.dashboard');
    }


    public function revenue(Request $request){

        $type = $request->get('type', 'month');

        $query = DB::table('quotations');

        if($type == 'month'){
            $query->selectRaw('MONTH(created_at) as label, SUM(grand_total) as total')->groupBy('label');
        }

        if($type == 'year'){
            $query->selectRaw('YEAR(created_at) as label, SUM(grand_total) as total')->groupBy('label');
        }

        $data = $query->get();

        return response()->json($data);
    }


    public function topproduct()
    {
        $data = DB::table('customer_needs')
            ->join('product_sets', 'customer_needs.product_set_id', '=', 'product_sets.id')
            ->join('product_set_names', 'product_sets.product_set_name_id', '=', 'product_set_names.id')
            ->select('product_set_names.name', DB::raw('SUM(customer_needs.quantity) as total'))
            ->whereNull('customer_needs.deleted_at') 
            ->groupBy('product_set_names.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
            
        return response()->json($data);
    }

    public function issies(){
        $data = DB::table('project_issues')->select('category',DB::raw('COUNT(*) as total'))->groupBy('category')->get();
        return response()->json($data);
    }

    public function cost(){
        $data = DB::table('project_purchases')->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
        ->groupBy('month')->get();

        return response()->json($data);
    }

    public function materialcompare(){
        $withdrawn = DB::table('material_logs')
            ->where('direction', 'out')
            ->where('source', 'withdraw')
            ->sum('quantitylog');

        $returned = DB::table('material_logs')
            ->where('direction', 'in')
            ->whereIn('source', ['return_material', 'return_tool'])
            ->sum('quantitylog');

        return response()->json([
            'withdrawn' => $withdrawn ?? 0, 
            'returned'  => $returned ?? 0
        ]);
    }

    public function topreporters(){
        $data = DB::table('project_issues')
            ->join('users', 'project_issues.reported_by', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(*) as total'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
            
        return response()->json($data);
    }


    public function area(Request $request)
    {
        $amphureId = $request->get('amphure_id');

        $query = DB::table('customers')
            ->join('thai_amphures', 'customers.amphure_id', '=', 'thai_amphures.id')
            ->select(
                'thai_amphures.id',
                'thai_amphures.name_th as name',
                DB::raw('COUNT(customers.id) as total')
            )
            ->where('customers.province_id', 23)
            ->groupBy('thai_amphures.id', 'thai_amphures.name_th')
            ->orderByDesc('total');

        if ($amphureId) {
            $query->where('thai_amphures.id', $amphureId);
        }

        return response()->json($query->get());
    }


    public function topArea()
    {
        $data = DB::table('customers')
            ->join('thai_amphures', 'customers.amphure_id', '=', 'thai_amphures.id')
            ->select(
                'thai_amphures.name_th as name',
                DB::raw('COUNT(customers.id) as total')
            )
            ->where('customers.province_id', 23)
            ->groupBy('thai_amphures.name_th')
            ->orderByDesc('total')
            ->first();

        return response()->json($data);
    }



    public function amphures()
    {
        $data = DB::table('thai_amphures')
            ->where('province_id', 23)
            ->select('id', 'name_th as name')
            ->orderBy('name_th')
            ->get();

        return response()->json($data);
    }

    public function summary()
    {
        $revenue = DB::table('quotations')->sum('grand_total');

        $cost = DB::table('project_purchases')->sum('total_amount');

        $profit = $revenue - $cost;

        $projects = DB::table('projects')->count();

        $avg = DB::table('quotations')->avg('grand_total');

        return response()->json([
            'revenue' => $revenue,
            'profit' => $profit,
            'projects' => $projects,
            'avg' => round($avg, 2),
            'margin' => $revenue > 0 ? round(($profit / $revenue) * 100, 1) : 0
        ]);
    }

    public function costBreakdown()
    {
        $revenue = DB::table('quotations')->sum('grand_total');

        $material = DB::table('project_purchase_items')->sum(DB::raw('quantity * price'));

        $labor = 3000; 

        $service = DB::table('project_expenses')->sum('amount');

        $other = DB::table('expenses')->sum('amount') ?? 0;

        $vat = $revenue * 0.07;

        return response()->json([
            'revenue' => $revenue,
            'labor' => $labor,
            'material' => $material,
            'service' => $service,
            'other' => $other,
            'vat' => $vat,
            'profit' => $revenue - ($labor + $material + $service + $other + $vat)
        ]);
    }

    public function revenueSource()
    {
        $material = DB::table('project_purchase_items')->sum(DB::raw('quantity * price'));

        $labor = 3000;

        $service = DB::table('project_expenses')->sum('amount');

        $other = DB::table('expenses')->sum('amount') ?? 0;

        $total = $material + $labor + $service + $other;

        return response()->json([
            'labor' => $labor,
            'material' => $material,
            'service' => $service,
            'other' => $other,
            'total' => $total
        ]);
    }

    public function latestProject()
    {
        $data = DB::table('projects')
            ->join('customers', 'projects.customer_id', '=', 'customers.id')
            ->select(
                'projects.name',
                'customers.name as customer',
                'projects.status',
                'projects.created_at'
            )
            ->latest()
            ->first();

        return response()->json($data);
    }


    public function pipeline()
    {
        return response()->json([
            'survey' => DB::table('projects')->where('status', 'survey')->count(),
            'install' => DB::table('projects')->where('status', 'install')->count(),
            'done' => DB::table('projects')->where('status', 'done')->count(),
        ]);
    }


    public function topProducts()
    {
        return DB::table('customer_needs')
            ->join('product_set_names', 'customer_needs.product_set_id', '=', 'product_set_names.id')
            ->select(
                'product_set_names.name',
                DB::raw('SUM(customer_needs.quantity) as total')
            )
            ->groupBy('product_set_names.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }


    public function suppliers()
    {
        return DB::table('dealers')->select('name', 'status')->get();
    }
    

    




}
