<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function revenue(Request $request)
    {
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

    public function issies()
    {
        $data = DB::table('project_issues')->select('category', DB::raw('COUNT(*) as total'))->groupBy('category')->get();
        return response()->json($data);
    }

    public function cost()
    {
        $data = DB::table('project_purchases')->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
        ->groupBy('month')->get();

        return response()->json($data);
    }

    public function materialcompare()
    {
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

    public function topreporters()
    {
        $data = DB::table('project_issues')
            ->join('users', 'project_issues.reported_by', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.role', DB::raw('count(*) as total'))
            ->groupBy('users.id', 'users.name','users.role')
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
}