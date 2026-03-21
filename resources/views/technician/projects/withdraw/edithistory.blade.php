@extends('layouts.technician')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">ประวัติการแก้ไขจำนวน</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('technician.projects.return_history', $project->id) }}" class="btn btn-secondary" style="font-size: 0.85em;">
                ดูประวัติการคืนวัสดุและอุปกรณ์
            </a>
            <a href="{{ route('technician.projects.withdrawdetails', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px; background: #f9f9f9;">
        <p><strong>โครงการ:</strong> {{ $project->projectname->name }} &nbsp;|&nbsp; <strong>ลูกค้า:</strong> คุณ {{ $project->customer->first_name }}</p>
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0;">ประวัติการแก้ไข</h3>
            <span style="color: #666;">จำนวน <b>{{ $editLogs->count() }}</b> รายการ</span>
        </div>

        @if($editLogs->isNotEmpty())
        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
            <thead style="background: #333; color: #fff;">
                <tr align="center">
                    <th width="15%">วันที่แก้ไข</th>
                    <th width="13%">ผู้แก้ไข</th>
                    <th width="10%">ประเภท</th>
                    <th width="20%">รายละเอียด</th>
                    <th width="17%">ขนาด</th>
                    <th width="13%">ผลการแก้ไข</th>
                    <th width="12%">จำนวนที่เปลี่ยน</th>
                </tr>
            </thead>
            <tbody>
                @foreach($editLogs as $log)
                @php
                    $mat    = $log->material;
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
                            ->where('lot', $log->price->lot ?? '')->with(['aluminiumlength', 'glassSize'])->first();
                        if ($priceRecord) {
                            if ($priceRecord->aluminiumlength) {
                                $sizeText = $priceRecord->aluminiumlength->length_meter . ' (ม./เส้น)';
                            } elseif ($priceRecord->glassSize) {
                                $gs = $priceRecord->glassSize;
                                $sizeText = $gs->width_meter . ' × ' . $gs->length_meter . ' ม.';
                                if (!empty($gs->thickness)) $sizeText .= ' หนา ' . $gs->thickness . ' มม. (ต่อแผ่น)';
                            }
                        }
                    }
                @endphp
                <tr style="border-bottom: 1px solid #eee;">
                    <td align="center">{{ \Carbon\Carbon::parse($log->created_at)->locale('th')->addYears(543)->isoFormat('D MMM YY HH:mm น.') }}</td>
                    <td align="center">{{ $log->user->name ?? '-' }}</td>
                    <td><b>{{ $mat->material_type ?? '-' }}</b></td>
                    <td>{{ $detail }}</td>
                    <td align="center">{{ $sizeText }}</td>
                    <td align="center">
                        @if($log->direction == 'in')
                            <span style="background:#1e8e3e; color:#fff; padding:3px 8px; border-radius:12px; font-size:0.8em;">ลดจำนวน</span>
                        @else
                            <span style="background:#d93025; color:#fff; padding:3px 8px; border-radius:12px; font-size:0.8em;">เพิ่มจำนวน</span>
                        @endif
                    </td>
                    <td align="center">
                        <b style="color: {{ $log->direction == 'in' ? '#1e8e3e' : '#d93025' }};">
                            {{ $log->direction == 'in' ? '-' : '+' }}{{ $log->quantitylog }}
                        </b>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p style="color: #999; text-align: center; padding: 20px;">ยังไม่มีประวัติการแก้ไขจำนวน</p>
        @endif
    </div>

</div>
@endsection