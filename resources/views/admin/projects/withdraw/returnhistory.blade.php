@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">ประวัติการคืนวัสดุ</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.projects.edit_history', $project->id) }}" class="btn btn-secondary" style="font-size: 0.85em;">
                ดูประวัติการแก้ไขจำนวน
            </a>
            <a href="{{ route('admin.projects.withdrawdetails', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px; background: #f9f9f9;">
        <p><strong>โครงการ:</strong> {{ $project->projectname->name }} &nbsp;|&nbsp; <strong>ลูกค้า:</strong> คุณ {{ $project->customer->first_name }}</p>
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0;">การคืนวัสดุ</h3>
            <span style="color: #666;">จำนวน <b>{{ $returnOnlyLogs->count() }}</b> รายการ</span>
        </div>

        @if($returnOnlyLogs->isNotEmpty())
        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
            <thead style="background: #333; color: #fff;">
                <tr align="center">
                    <th width="15%">วันที่คืน</th>
                    <th width="13%">ผู้คืน</th>
                    <th width="10%">ประเภท</th>
                    <th width="20%">รายละเอียด</th>
                    <th width="17%">ขนาด</th>
                    <th width="13%">ประเภทการคืน</th>
                    <th width="12%">จำนวนที่คืน</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returnOnlyLogs as $log)
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
                        @if($log->source == 'return_tool')
                            <span style="background:#3498db; color:#fff; padding:3px 8px; border-radius:12px; font-size:0.8em;">คืนเครื่องมือ</span>
                        @else
                            <span style="background:#1e8e3e; color:#fff; padding:3px 8px; border-radius:12px; font-size:0.8em;">คืนวัสดุ</span>
                        @endif
                    </td>
                    <td align="center"><b style="color: #1e8e3e;">+{{ $log->quantitylog }}</b></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p style="color: #999; text-align: center; padding: 20px;">ยังไม่มีประวัติการคืนวัสดุ</p>
        @endif
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0;">การเติมวัสดุจากปัญหา</h3>
            <span style="color: #666;">จำนวน <b>{{ $issueRefillLogs->count() }}</b> รายการ</span>
        </div>

        @if($issueRefillLogs->isNotEmpty())
        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
            <thead style="background: #333; color: #fff;">
                <tr align="center">
                    <th width="15%">วันที่เติม</th>
                    <th width="13%">ผู้เติม</th>
                    <th width="10%">ประเภท</th>
                    <th width="20%">รายละเอียด</th>
                    <th width="17%">ขนาด</th>
                    <th width="13%">หมายเหตุ</th>
                    <th width="12%">จำนวนที่เติม</th>
                </tr>
            </thead>
            <tbody>
                @foreach($issueRefillLogs as $log)
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
                        <span style="background:#e67e22; color:#fff; padding:3px 8px; border-radius:12px; font-size:0.8em;">เติมจากปัญหา</span>
                    </td>
                    <td align="center"><b style="color: #e67e22;">+{{ $log->quantitylog }}</b></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p style="color: #999; text-align: center; padding: 20px;">ยังไม่มีประวัติการเติมวัสดุจากปัญหา</p>
        @endif
    </div>

</div>
@endsection