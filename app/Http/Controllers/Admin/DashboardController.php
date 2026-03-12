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

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    
}
