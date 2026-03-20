@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #333;">เลือกวัสดุเพื่อเบิก</h3>
        <a href="{{ route('admin.projects.index', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    @php
        $remainingWithdrawn = $withdrawnSummary ?? [];
        $allComplete = true;
        $hasItems = false;
    @endphp

    <div style="display: none;">
        @foreach ($project->customerneed as $need)
            @foreach ($need->productset->productsetitem as $item)
                @php
                    $hasItems = true;
                    $reqQty = $item->calculated_qty;
                    $matId  = $item->material_id;
                    $already = 0;

                    if (isset($remainingWithdrawn[$matId]) && $remainingWithdrawn[$matId] > 0) {
                        if ($remainingWithdrawn[$matId] >= $reqQty) {
                            $already = $reqQty;
                            $remainingWithdrawn[$matId] -= $reqQty;
                        } else {
                            $already = $remainingWithdrawn[$matId];
                            $remainingWithdrawn[$matId] = 0;
                        }
                    }

                    if ($already < $reqQty) {
                        $allComplete = false;
                    }
                @endphp
            @endforeach
        @endforeach
    </div>

    @php $remainingWithdrawn = $withdrawnSummary ?? []; @endphp

    @if($allComplete && $hasItems)
        <div style="text-align: center; padding: 30px; background-color: #f6fdf6; border: 1px solid #cce8d4; margin-bottom: 20px;">
            <h4 style="margin-bottom: 0; color: #1e8e3e;">วัสดุในโครงการนี้ถูกเบิกครบตามจำนวนแล้วทั้งหมด</h4>
        </div>
    @endif

    <form action="{{ route('admin.projects.withdrawform', $project->id) }}" method="POST">
        @csrf
        <div class="boxmaterial">
            <h3 style="margin-bottom: 15px;">รายการวัสดุ</h3>
            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
                <thead style="background: #333; color: #fff;">
                    <tr>
                        <th width="5%"  style="text-align: center;">
                            <input type="checkbox" id="select-all" style="transform: scale(1.2); cursor: pointer;" title="เลือกทั้งหมด">
                        </th>
                        <th width="10%" style="text-align: center;">ชุดงาน</th>
                        <th width="15%" style="text-align: center;">ประเภท</th>
                        <th width="25%">รายละเอียด</th>
                        <th width="10%" style="text-align: center;">ล็อตที่จะเบิก</th>
                        <th width="10%" style="text-align: center;">ต้องการ</th>
                        <th width="12%" style="text-align: center;">จำนวนที่จะเบิก</th>
                        <th width="13%" style="text-align: center;">สถานะ</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($project->customerneed as $need)
                        @foreach ($need->productset->productsetitem->sortBy('material.material_type') as $item)
                            @php
                                $mat    = $item->material;
                                $reqQty = $item->calculated_qty;
                                $canWithdrawStock = !in_array($item->calculated_lot, [
                                    'ไม่มีของหรือขนาดไม่พอ',
                                    'ไม่มีของ/ขนาดไม่พอ',
                                    'ไม่มีของ'
                                ]);

                                $already = 0;
                                if (isset($remainingWithdrawn[$mat->id]) && $remainingWithdrawn[$mat->id] > 0) {
                                    if ($remainingWithdrawn[$mat->id] >= $reqQty) {
                                        $already = $reqQty;
                                        $remainingWithdrawn[$mat->id] -= $reqQty;
                                    } else {
                                        $already = $remainingWithdrawn[$mat->id];
                                        $remainingWithdrawn[$mat->id] = 0;
                                    }
                                }

                                $isComplete = ($already >= $reqQty);
                                $pendingQty = $reqQty - $already;

                                $stockQty = 0;
                                if ($item->calculated_price_id) {
                                    $priceRecord = \App\Models\Price::find($item->calculated_price_id);
                                    $stockQty = $priceRecord ? $priceRecord->quantity : 0;
                                }

                                $maxQty = min($pendingQty, $stockQty);
                                $minQty = $maxQty > 0 ? 1 : 0;
                            @endphp

                            <tr style="border-bottom: 1px solid #eee;">
                                <td align="center">
                                    @if($isComplete)
                                        <i class="fa-solid fa-circle-check" style="color: #1e8e3e; font-size: 1.2rem;" title="เบิกครบแล้ว"></i>
                                    @elseif($canWithdrawStock && $maxQty > 0)
                                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                               class="item-checkbox" style="transform: scale(1.2); cursor: pointer;">
                                    @else
                                        <i class="fas fa-times-circle" style="color: red;" title="ไม่มีของให้เบิก"></i>
                                    @endif
                                </td>

                                <td align="center">
                                    <small style="color: #666;">{{ $need->productset->productSetName->name }}</small>
                                </td>

                                <td align="center"><b>{{ $mat->material_type }}</b></td>

                                <td>
                                    {{ $item->calculated_description ?? 'ไม่มีรายละเอียด' }}
                                    <br><small style="color: #888;">{{ $item->calculated_remark ?? '' }}</small>
                                    @if($canWithdrawStock)
                                        <br><small style="color: #1e8e3e;">สต็อกคงเหลือ: <b>{{ $stockQty }}</b></small>
                                    @endif
                                </td>

                                <td align="center"><span>{{ $item->calculated_lot }}</span></td>

                                <td align="center">
                                    <span style="color: #333;">{{ $pendingQty }}</span>
                                    @if($already > 0)
                                        <br><small style="color: #999;">(เบิกไปแล้ว {{ $already }})</small>
                                    @endif
                                </td>

                                <td align="center">
                                    @if($isComplete)
                                        <span style="color: #999;">-</span>
                                    @elseif($canWithdrawStock && $maxQty > 0)
                                        <input type="number"
                                               name="custom_qty[{{ $item->id }}]"
                                               value="{{ $maxQty }}"
                                               min="{{ $minQty }}"
                                               max="{{ $maxQty }}"
                                               class="qty-input"
                                               data-item-id="{{ $item->id }}"
                                               style="width: 70px; padding: 4px 6px; border: 1px solid #ccc; border-radius: 4px; text-align: center;"
                                               oninput="validateQty(this)">
                                        <br>
                                        <small style="color: #888;">เบิกได้สูงสุด {{ $maxQty }}</small>
                                    @else
                                        <span style="color: #999;">{{ $pendingQty }}</span>
                                        <input type="hidden" name="custom_qty[{{ $item->id }}]" value="0">
                                    @endif
                                </td>

                                <td align="center">
                                    @if($isComplete)
                                        <span style="color:#fff; font-size:0.85em; font-weight:bold; background:#1e8e3e; padding:6px 12px; border-radius:20px;">ครบแล้ว</span>
                                    @elseif(!$canWithdrawStock || $maxQty == 0)
                                        <span style="color:#fff; font-size:0.85em; font-weight:bold; background:#999; padding:6px 12px; border-radius:20px;">ไม่มีของ</span>
                                    @else
                                        <span style="color:#fff; font-size:0.85em; font-weight:bold; background:#d93025; padding:6px 12px; border-radius:20px;">ยังไม่ครบ</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            @if(!$allComplete)
                <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-secondary">ไปหน้าถัดไป</button>
                </div>
            @endif
        </div>
    </form>
</div>

<script>
    document.getElementById('select-all').onclick = function () {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
    };

    function validateQty(input) {
        const min = parseInt(input.min) || 0;
        const max = parseInt(input.max) || 0;
        let val = parseInt(input.value) || 0;

        if (val < min) {
            input.value = min;
            input.style.borderColor = '#d93025';
        } else if (val > max) {
            input.value = max;
            input.style.borderColor = '#d93025';
        } else {
            input.style.borderColor = '#ccc';
        }
    }
</script>
@endsection