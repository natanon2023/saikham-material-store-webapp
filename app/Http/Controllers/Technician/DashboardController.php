<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\AccessoryType;
use App\Models\AluminiumProfileType;
use App\Models\Customer;
use App\Models\ExpenseType;
use App\Models\ProductSetName;
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
use App\Models\WithdrawalItemLog;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        $query = Project::with(['projectname', 'customer', 'installers'])->where(function($q) use ($userId) {
                
                $q->where(function($q1) use ($userId) {
                    $q1->where('assigned_surveyor_id', $userId)
                    ->whereNotNull('survey_date');
                })
                ->orWhere(function($q2) use ($userId) {
                    $q2->whereHas('installers', function($q3) use ($userId) {
                        $q3->where('users.id', $userId); 
                    })
                    ->whereNotNull('installation_start_date')
                    ->whereNotNull('installation_end_date');
                });
                
            });

        $events = $query->withTrashed()->get()->flatMap(function ($pj) use ($userId) {
            $customerName = $pj->customer->first_name ?? 'ไม่ระบุลูกค้า';
            $projectName  = $pj->projectname->name ?? 'ไม่มีชื่องาน';

            if ($pj->trashed()) {
                $statusLabel = 'ยกเลิก';
                $color       = '#DC143C';
            } else {
                $statusLabel = $this->getStatusLabel($pj->status);
                $color       = $this->getStatusColor($pj->status);
            }

            $isSurveyor  = ($pj->assigned_surveyor_id == $userId);
            
            $isInstaller = $pj->installers->contains('id', $userId);

            $items = [];

            if ($isSurveyor && $pj->survey_date) {
                $items[] = [
                    'id'              => $pj->id . '_survey',
                    'title'           => ($pj->trashed() ? "(ยกเลิก) " : "") . "[สำรวจ] " . $customerName . " - " . $projectName,
                    'start'           => date('Y-m-d', strtotime($pj->survey_date)),
                    'url'             => route('technician.projects.index', $pj->id),
                    'backgroundColor' => $color,
                    'borderColor'     => $color,
                    'allDay'          => true,
                    'textColor'       => '#ffffff'
                ];
            }

            if ($isInstaller && $pj->installation_start_date && $pj->installation_end_date) {
                $items[] = [
                    'id'              => $pj->id . '_install',
                    'title'           => ($pj->trashed() ? "(ยกเลิก) " : "") . "[ติดตั้ง] " . $customerName . " - " . $projectName . " (" . $statusLabel . ")",
                    'start'           => date('Y-m-d', strtotime($pj->installation_start_date)),
                    'end'             => date('Y-m-d', strtotime($pj->installation_end_date . ' + 1 day')), 
                    'url'             => route('technician.projects.index', $pj->id),
                    'backgroundColor' => $color,
                    'borderColor'     => $color,
                    'allDay'          => true,
                    'textColor'       => '#ffffff'
                ];
            }

            return $items;
        });

        $eventCount = $query->count();

        return view('technician.dashboard', compact('events', 'eventCount'));
    }

    private function getStatusLabel($status)
    {
        return match ($status) {
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
}
