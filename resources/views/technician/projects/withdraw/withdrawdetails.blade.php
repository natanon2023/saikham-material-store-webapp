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
        $currentStatus = $statusColors[$project->status] ?? ['#999', 'ไม่ระบุ'];
    @endphp

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">รายการที่เบิกไปทั้งหมด</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('technician.projects.withdrawtoolspage', $project->id) }}" class="btn btn-secondary">
                เบิกเครื่องมือช่าง
            </a>
            <a href="{{ route('technician.projects.managewithdrawals') }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px; display:flex; justify-content:space-between; height: max-content;  ">
        <div >
           <strong>โครงการ:</strong> {{ $project->projectname->name }} &nbsp;|&nbsp; <strong>ลูกค้า:</strong> คุณ {{ $project->customer->first_name }} |
            <strong>สถานะ:</strong>
            <span style="background-color: {{ $currentStatus[0] }}; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 0.85em;">
                {{ $currentStatus[1] }}
            </span> 
        </div>
        
        <div style="margin-top: 12px; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('technician.projects.return_history', $project->id) }}" class="btn btn-secondary btn-full-text" style="font-size: 0.85em;">
                ดูประวัติการคืนวัสดุ
            </a>
            <a href="{{ route('technician.projects.edit_history', $project->id) }}" class="btn btn-secondary btn-full-text" style="font-size: 0.85em;">
                ดูประวัติการแก้ไขจำนวน
            </a>
        </div>
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0;">รายการวัสดุ</h3>
            <span style="color: #666;">จำนวน <b>{{ $withdrawals->flatMap->items->count() }}</b> รายการ</span>
        </div>
        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
            <thead style="background: #333; color: #fff;">
                <tr align="center">
                    <th width="10%">วันที่เบิก</th>
                    <th width="13%">ผู้เบิก</th>
                    <th width="10%">ประเภท</th>
                    <th width="22%">รายละเอียด</th>
                    <th width="15%">ขนาด</th>
                    <th width="7%">ล็อต</th>
                    <th width="10%">คงเหลือ</th>
                    <th width="13%">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($withdrawals as $withdrawal)
                    @foreach ($withdrawal->items as $item)
                        @php
                            $mat    = $item->material;
                            $isTool = ($mat && $mat->material_type == 'เครื่องมือช่าง');

                            $detail = '-';
                            if ($mat) {
                                if ($mat->aluminiumItem) {
                                    $detail = ($mat->aluminiumItem->aluminiumType->name ?? '-') . ' สี ' . ($mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-');
                                } elseif ($mat->glassItem) {
                                    $detail = ($mat->glassItem->glassType->name ?? '-') . ' สี ' . ($mat->glassItem->colourItem->name ?? '-');
                                } elseif ($mat->accessoryItem) {
                                    $detail = $mat->accessoryItem->accessoryType->name ?? '-';
                                } elseif ($mat->consumableItem) {
                                    $detail = $mat->consumableItem->consumabletype->name ?? '-';
                                } elseif ($mat->toolItem) {
                                    $detail = $mat->toolItem->toolType->name ?? '-';
                                } else {
                                    $detail = $mat->name ?? '-';
                                }
                            }

                            $sizeText = 'ไม่มีขนาด';
                            if ($mat) {
                                $priceRecord = \App\Models\Price::where('material_id', $mat->id)
                                    ->where('lot', $item->lot)
                                    ->with(['aluminiumlength', 'glassSize'])
                                    ->first();
                                if ($priceRecord) {
                                    if ($priceRecord->aluminiumlength) {
                                        $sizeText = $priceRecord->aluminiumlength->length_meter . ' (ม./เส้น)';
                                    } elseif ($priceRecord->glassSize) {
                                        $gs = $priceRecord->glassSize;
                                        $sizeText = $gs->width_meter . ' × ' . $gs->length_meter . ' ม.';
                                        if (!empty($gs->thickness)) {
                                            $sizeText .= ' หนา ' . $gs->thickness . ' มม. (ต่อแผ่น)';
                                        }
                                    }
                                }
                            }

                            $hasReturned = $mat && in_array($mat->id, $returnedMaterialIds);
                        @endphp
                        <tr style="border-bottom: 1px solid #eee;">
                            <td align="center">
                                {{ \Carbon\Carbon::parse($withdrawal->created_at)->locale('th')->addYears(543)->isoFormat('D MMM YY') }}
                            </td>
                            <td align="center">{{ $withdrawal->withdrawnBy->name ?? 'ไม่ระบุ' }}</td>
                            <td align="center"><b>{{ $mat->material_type ?? '-' }}</b></td>
                            <td>{{ $detail }}</td>
                            <td align="center">{{ $sizeText }}</td>
                            <td align="center">{{ $item->lot }}</td>
                            <td align="center">
                                    <b>{{ $item->quantity }}</b>
                            </td>
                            <td align="center">
                                @if($isTool)
                                    <form action="{{ route('technician.projects.return_tool', $item->id) }}" method="POST"
                                          onsubmit="return confirm('ยืนยันการคืนเครื่องมือช่างนี้เข้าคลัง?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" style="padding: 4px 10px; font-size: 0.85em;">
                                            คืนเข้าคลัง
                                        </button>
                                    </form>
                                @elseif($hasReturned)
                                    <a href="{{ route('technician.projects.edit_withdrawal_item_page', $item->id) }}"
                                       class="btn-icon btn-edit" title="แก้ไขจำนวนคืน">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @else
                                    <span style="color: #ccc; font-size: 0.8em;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="8" align="center" style="color: #999; padding: 20px;">ยังไม่มีประวัติการเบิก</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="boxmaterial" style="text-align: center; padding: 20px;">
        @if($project->status == 'completed')
            <p style="color: #1e8e3e; margin-bottom: 15px;">โครงการเสร็จสิ้นแล้ว สามารถคืนวัสดุที่เหลือเข้าสต็อกได้</p>
            <a href="{{ route('technician.projects.return_materials_page', $project->id) }}"
               class="btn btn-secondary" style="padding: 12px 30px; font-size: 1em;">
                คืนวัสดุที่เหลือเข้าสต็อก
            </a>
        @else
            <p style="color: #888;">ปุ่มคืนวัสดุจะแสดงเมื่อสถานะโครงการเป็น <b>เสร็จสิ้น</b> เท่านั้น</p>
        @endif
    </div>

</div>
@endsection