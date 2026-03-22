@extends('layouts.technician')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    @php
        $statusColors = [
            'pending_survey'      => ['#D4AF37', 'นัดสำรวจ'],
            'waiting_survey'      => ['#FF8C00', 'รอวันสำรวจ'],
            'surveying'           => ['#1E90FF', 'กำลังสำรวจ'],
            'pending_quotation'   => ['#E91E63', 'รอเสนอราคา'],
            'waiting_approval'    => ['#9C27B0', 'รออนุมัติ'],
            'approved'            => ['#78d37b', 'อนุมัติและชำระเงินแล้ว'],
            'material_planning'   => ['#00CED1', 'วางแผนวัสดุ'],
            'waiting_purchase'    => ['#FF4500', 'รอสั่งซื้อ'],
            'ready_to_withdraw'   => ['#008080', 'พร้อมเบิก'],
            'materials_withdrawn' => ['#8B4513', 'เบิกวัสดุแล้ว'],
            'installing'          => ['#4CAF50', 'กำลังติดตั้ง'],
            'completed'           => ['#708090', 'เสร็จสิ้น'],
            'cancelled'           => ['#DC143C', 'ยกเลิก'],
        ];
        $cs = $statusColors[$project->status] ?? ['#999', 'ไม่ระบุ'];

        $sourceConfig = [
            'withdraw'        => ['label' => 'เบิก',           'bg' => '#fdecea', 'color' => '#c0392b', 'sign' => '-'],
            'return_material' => ['label' => 'คืนวัสดุ',        'bg' => '#e8f5e9', 'color' => '#1e8e3e', 'sign' => '+'],
            'return_tool'     => ['label' => 'คืนเครื่องมือ',   'bg' => '#e3f2fd', 'color' => '#1565c0', 'sign' => '+'],
            'issue_refill'    => ['label' => 'เติมจากปัญหา',    'bg' => '#fff3e0', 'color' => '#e65100', 'sign' => '+'],
            'manual'          => ['label' => 'แก้ไขจำนวน',      'bg' => '#f3e5f5', 'color' => '#6a1b9a', 'sign' => '±'],
        ];

        $totalWithdraw = $materialLogs->where('source', 'withdraw')->sum('quantitylog');
        $totalReturn   = $materialLogs->whereIn('source', ['return_material', 'return_tool'])->sum('quantitylog');
        $totalRefill   = $materialLogs->where('source', 'issue_refill')->sum('quantitylog');
        
    @endphp

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h3 style="margin: 0 0 6px 0;">ประวัติการเบิก-คืนวัสดุ</h3>
            <div style="font-size: 0.85em; color: #666;">
                {{ $project->projectname->name }} &nbsp;|&nbsp;
                คุณ {{ $project->customer->first_name }}
                &nbsp;|&nbsp;
                <span style="background: {{ $cs[0] }}; color:#fff; padding:3px 10px; border-radius:20px; font-size:0.9em;">{{ $cs[1] }}</span>
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('technician.projects.withdrawtoolspage', $project->id) }}" class="btn btn-secondary">เบิกเครื่องมือช่าง</a>
            <a href="{{ route('technician.projects.managewithdrawals') }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
    </div>

    

    <div class="boxmaterial" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h4 style="margin: 0;">วัสดุที่เหลืออยู่กับช่างตอนนี้</h4>
            <span style="font-size: 0.85em; color: #666;">จำนวนวัสดุทั้งหมด {{ $currentItems->sum('quantity') }} ชิ้น</span>
        </div>

        @if($currentItems->isEmpty())
            <p style="text-align: center; color: #1e8e3e; padding: 15px;">
                ไม่มีวัสดุค้างอยู่กับช่าง — คืนหมดแล้วทั้งหมด
            </p>
        @else
        <table width="100%" cellpadding="10" cellspacing="0"
               style="border-collapse: collapse; border: 1px solid #ddd; font-size: 0.9em;">
            <thead style="background: #fff8f0;">
                <tr>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: center">วัสดุ</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; width: 70px; text-align: center;">ล็อต</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; width: 100px; text-align: center;">คงเหลือกับช่าง</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; width: 120px; text-align: center;">ผู้เบิก</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; width: 120px; text-align: center;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($currentItems as $item)
                    @php
                        $mat    = $item->material;
                        $isTool = ($mat?->material_type == 'เครื่องมือช่าง');

                        $detail = '-';
                        if ($mat?->aluminiumItem) {
                            $detail = ($mat->aluminiumItem->aluminiumType->name ?? '-') . ' สี ' . ($mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-');
                        } elseif ($mat?->glassItem) {
                            $detail = ($mat->glassItem->glassType->name ?? '-') . ' สี ' . ($mat->glassItem->colourItem->name ?? '-');
                        } elseif ($mat?->accessoryItem) {
                            $detail = $mat->accessoryItem->accessoryType->name ?? '-';
                        } elseif ($mat?->consumableItem) {
                            $detail = $mat->consumableItem->consumabletype->name ?? '-';
                        } elseif ($mat?->toolItem) {
                            $detail = $mat->toolItem->toolType->name ?? '-';
                        }
                    @endphp
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">
                            <div style="font-size: 0.8em; color: #888;">{{ $mat?->material_type ?? '-' }}</div>
                            <div style="font-weight: 500;">{{ $detail }}</div>
                        </td>
                        <td align="center" style="padding: 10px; color: #666; font-size: 0.85em;">{{ $item->lot }}</td>
                        <td align="center" style="padding: 10px;">
                            <span style="font-weight: bold; font-size: 1.1em; color: #c0392b;">{{ $item->quantity }}</span>
                        </td>
                        <td align="center" style="padding: 10px; font-size: 0.85em; color: #666;">
                            {{ $item->withdrawal->withdrawnBy->name ?? 'ไม่ระบุ' }}
                        </td>
                        <td align="center" style="padding: 10px;">
                            @if($isTool)
                                <form action="{{ route('technician.projects.return_tool', $item->id) }}" method="POST"
                                    onsubmit="return confirm('ยืนยันการคืนเครื่องมือช่างนี้เข้าคลัง?');" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" style="padding: 4px 12px; font-size: 0.8em;">
                                        คืนเข้าคลัง
                                    </button>
                                </form>
                            @else
                                @php
                                    $hasReturnLog = \App\Models\MaterialLog::where('project_id', $item->withdrawal->project_id)
                                        ->where('material_id', $item->material_id)
                                        ->whereIn('source', ['return_material'])
                                        ->exists();
                                @endphp
                                @if($hasReturnLog)
                                    <a href="{{ route('technician.projects.edit_withdrawal_item_page', $item->id) }}"
                                    class="btn btn-secondary" style="padding: 4px 12px; font-size: 0.8em;">
                                        แก้ไขจำนวน
                                    </a>
                                @else
                                    <span style="font-size: 0.8em; color: #999;">คืนผ่านหน้าคืนวัสดุ</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($project->status == 'completed')
        <div style="margin-top: 15px; text-align: center; padding: 15px; background: #f0faf0; border-radius: 8px;">
            <p style="color: #1e8e3e; margin-bottom: 10px;">โครงการเสร็จสิ้นแล้ว สามารถคืนวัสดุที่เหลือเข้าสต็อกได้</p>
            <a href="{{ route('technician.projects.return_materials_page', $project->id) }}"
               class="btn btn-secondary" style="padding: 8px 24px;">
                คืนวัสดุที่เหลือเข้าสต็อก
            </a>
        </div>
        @endif
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px;">
        <h4 style="margin: 0 0 15px 0;">ประวัติทั้งหมด</h4>

        @if($materialLogs->isEmpty())
            <p style="text-align: center; color: #999; padding: 20px;">ยังไม่มีประวัติ</p>
        @else
        <table width="100%" cellpadding="10" cellspacing="0"
               style="border-collapse: collapse; border: 1px solid #ddd; font-size: 0.9em;">
            <thead style="background: #f5f5f5;">
                <tr align="center"> 
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; width: 120px;">วันที่ / เวลา</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; width: 110px;">ประเภทรายการ</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">วัสดุ</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; width: 60px; text-align: center;">ล็อต</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; width: 80px; text-align: center;">จำนวน</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; width: 100px;">ผู้ดำเนินการ</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">หมายเหตุ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materialLogs as $log)
                    @php
                        $cfg   = $sourceConfig[$log->source] ?? ['label' => $log->source, 'bg' => '#eee', 'color' => '#333', 'sign' => ''];
                        $mat   = $log->material;
                        $isOut = ($log->direction == 'out');

                        $detail = '-';
                        if ($mat?->aluminiumItem) {
                            $detail = ($mat->aluminiumItem->aluminiumType->name ?? '-') . ' สี ' . ($mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-');
                        } elseif ($mat?->glassItem) {
                            $detail = ($mat->glassItem->glassType->name ?? '-') . ' สี ' . ($mat->glassItem->colourItem->name ?? '-');
                        } elseif ($mat?->accessoryItem) {
                            $detail = $mat->accessoryItem->accessoryType->name ?? '-';
                        } elseif ($mat?->consumableItem) {
                            $detail = $mat->consumableItem->consumabletype->name ?? '-';
                        } elseif ($mat?->toolItem) {
                            $detail = $mat->toolItem->toolType->name ?? '-';
                        }

                        $sizeText = '';
                        if ($log->price?->aluminiumlength) {
                            $sizeText = $log->price->aluminiumlength->length_meter . ' ม./เส้น';
                        } elseif ($log->price?->glassSize) {
                            $gs = $log->price->glassSize;
                            $sizeText = $gs->width_meter . '×' . $gs->length_meter . ' ม.';
                            if (!empty($gs->thickness)) $sizeText .= ' หนา '.$gs->thickness.' มม.';
                        }
                    @endphp
                    <tr style="border-bottom: 1px solid #eee; {{ $isOut ? '' : 'background:#fafffe;' }}">
                        <td align="center" style="padding: 10px; font-size: 0.85em; color: #666;">
                            {{ \Carbon\Carbon::parse($log->created_at)->locale('th')->addYears(543)->isoFormat('D MMM YY') }}
                            <div style="color: #999;">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }} น.</div>
                        </td>
                        <td align="center" style="padding: 10px;">
                            <span style="background: {{ $cfg['bg'] }}; color: {{ $cfg['color'] }}; padding: 4px 10px; border-radius: 20px; font-size: 0.85em; white-space: nowrap;">
                                {{ $cfg['label'] }}
                            </span>
                        </td>
                        <td style="padding: 10px;">
                            <div style="font-size: 0.8em; color: #888;">{{ $mat?->material_type ?? '-' }}</div>
                            <div style="font-weight: 500;">{{ $detail }}</div>
                            @if($sizeText)
                                <div style="font-size: 0.8em; color: #999; margin-top: 2px;">{{ $sizeText }}</div>
                            @endif
                        </td>
                        <td align="center" style="padding: 10px; color: #666; font-size: 0.85em;">
                            {{ $log->price?->lot ?? '-' }}
                        </td>
                        <td align="center" style="padding: 10px; font-weight: bold;
                            color: {{ $isOut ? '#c0392b' : '#1e8e3e' }};">
                            {{ $cfg['sign'] }}{{ $log->quantitylog }}
                        </td>
                        <td style="padding: 10px; font-size: 0.85em;">
                            {{ $log->user?->name ?? 'ระบบ' }}
                        </td>
                        <td align="center" style="padding: 10px; font-size: 0.85em; color: #666;">
                            {{ $log->note ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
@endsection