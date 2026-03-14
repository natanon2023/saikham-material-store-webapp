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
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Price;
use Carbon\Carbon;

class DashboardController extends Controller
{
    

    public function index()
    {
        $currentMonth = Carbon::now()->month;

        $monthlyProfit = Project::whereMonth('created_at', $currentMonth)->sum('total_profit'); // กำไรเดือนนี้
        $activeProjects = Project::whereIn('status', ['pending', 'surveying', 'installing'])->count(); // งานที่กำลังทำ
        $lowStockCount = Price::where('quantity', '<=', 5)->count(); // ของใกล้หมด

        $statusData = [
            'รอดำเนินการ' => Project::where('status', 'pending')->count(),
            'กำลังติดตั้ง' => Project::where('status', 'installing')->count(),
            'เสร็จสิ้น' => Project::where('status', 'completed')->count(),
        ];

        $recentFinancialProjects = Project::whereNotNull('total_profit')->orderBy('created_at', 'desc')->take(5)->get(['project_code', 'actual_material_cost', 'labor_cost', 'total_profit']);

        $lowStockItems = Price::where('quantity', '<=', 10)->with('material')->take(5)->get();

        return view('admin.dashboard', compact(
            'monthlyProfit',
            'activeProjects',
            'lowStockCount',
            'statusData',
            'recentFinancialProjects',
            'lowStockItems'
        ));
    }
}
