<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
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

        $query = Project::where('assigned_surveyor_id', Auth::id())->whereIn('status', $statuses)->whereNotNull('survey_date');

        $eventCount = $query->count();

        $events = $query->with('projectname')
            ->get()
            ->map(function ($pj) {
                return [
                    'id'    => $pj->id,
                    'title' => "[" . $this->getStatusLabel($pj->status) . "] " . ($pj->projectname->name ?? 'ไม่มีชื่อโครงการ'),
                    'start' => date('Y-m-d', strtotime($pj->survey_date)),
                    'url'   => route('technician.projects.show', $pj->id),
                    'backgroundColor' => $this->getStatusColor($pj->status),
                    'borderColor'     => $this->getStatusColor($pj->status),
                    'allDay'          => true
                ];
            });

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
