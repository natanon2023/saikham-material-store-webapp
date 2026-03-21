@extends('layouts.technician')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">เลือกวัสดุเพื่อเบิก</h3>
        <a href="{{ route('technician.projects.index', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    @php
        $quotationMats      = $project->quotation?->quotationMaterials ?? collect();
        $remainingWithdrawn = $withdrawnSummary ?? [];
        $allComplete        = true;
        $hasItems           = false;

        foreach ($quotationMats as $qmat) {
            if (!$qmat->material_id) continue;
            $hasItems = true;
            $already  = min($remainingWithdrawn[$qmat->material_id] ?? 0, $qmat->quantity);
            if ($already < $qmat->quantity) $allComplete = false;
        }
    @endphp

    @if($allComplete && $hasItems)
        <div style="text-align:center; padding:30px; background:#f6fdf6; border:1px solid #cce8d4; margin-bottom:20px; border-radius:8px;">
            <h4 style="margin:0; color:#1e8e3e;">วัสดุในโครงการนี้ถูกเบิกครบตามจำนวนแล้วทั้งหมด</h4>
        </div>
    @endif

    <form action="{{ route('technician.projects.withdrawform', $project->id) }}" method="POST">
        @csrf
        <div class="boxmaterial">
            <h3 style="margin-bottom:15px;">รายการวัสดุตามใบเสนอราคา</h3>

            @if($quotationMats->isEmpty())
                <p style="color:#999; text-align:center; padding:20px;">ไม่พบรายการวัสดุในใบเสนอราคา</p>
            @else
            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse; border:1px solid #ddd;">
                <thead style="background:#333; color:#fff;">
                    <tr>
                        <th width="5%"  style="text-align:center;">
                            <input type="checkbox" id="select-all" style="transform:scale(1.2); cursor:pointer;">
                        </th>
                        <th width="12%" style="text-align:center;">ประเภท</th>
                        <th width="30%" style="text-align:left; padding-left:10px;">รายละเอียด</th>
                        <th width="10%" style="text-align:center;">ล็อต</th>
                        <th width="10%" style="text-align:center;">ต้องการ</th>
                        <th width="12%" style="text-align:center;">จำนวนที่จะเบิก</th>
                        <th width="11%" style="text-align:center;">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotationMats as $qmat)
                        @php
                            $priceRecord = $qmat->material_id
                                ? \App\Models\Price::where('material_id', $qmat->material_id)
                                    ->where('quantity', '>', 0)
                                    ->orderBy('id', 'asc')
                                    ->first()
                                : null;

                            $stockQty    = $priceRecord ? $priceRecord->quantity : 0;
                            $reqQty      = $qmat->quantity;
                            $already     = $qmat->material_id
                                ? min($remainingWithdrawn[$qmat->material_id] ?? 0, $reqQty)
                                : 0;
                            $pendingQty  = $reqQty - $already;
                            $isComplete  = ($already >= $reqQty);
                            $maxQty      = min($pendingQty, $stockQty);
                            $canWithdraw = $priceRecord && $maxQty > 0;
                        @endphp
                        <tr style="border-bottom:1px solid #eee;">
                            <td align="center">
                                @if($isComplete)
                                    <i class="fa-solid fa-circle-check" style="color:#1e8e3e; font-size:1.2rem;"></i>
                                @elseif($canWithdraw)
                                    <input type="checkbox" name="selected_price_ids[]" value="{{ $priceRecord->id }}"
                                           class="item-checkbox" style="transform:scale(1.2); cursor:pointer;">
                                @else
                                    <i class="fas fa-times-circle" style="color:#d93025;"></i>
                                @endif
                            </td>
                            <td align="center"><b>{{ $qmat->material_type }}</b></td>
                            <td>
                                {{ $qmat->description }}
                                @if($qmat->remark)
                                    <br><small style="color:#888;">{{ $qmat->remark }}</small>
                                @endif
                                @if($priceRecord)
                                    <br><small style="color:#1e8e3e;">สต็อกคงเหลือ: <b>{{ $stockQty }}</b></small>
                                @else
                                    <br><small style="color:#d93025;">ไม่มีของในสต็อก</small>
                                @endif
                            </td>
                            <td align="center">
                                {{ $priceRecord ? $priceRecord->lot : '-' }}
                            </td>
                            <td align="center">
                                <b>{{ $reqQty }}</b>
                                @if($already > 0)
                                    <br><small style="color:#999;">(เบิกแล้ว {{ $already }})</small>
                                @endif
                            </td>
                            <td align="center">
                                @if($isComplete)
                                    <span style="color:#999;">-</span>
                                @elseif($canWithdraw)
                                    <input type="number"
                                           name="custom_qty[{{ $priceRecord->id }}]"
                                           value="{{ $maxQty }}"
                                           min="1" max="{{ $maxQty }}"
                                           style="width:70px; padding:4px; text-align:center; border:1px solid #ccc; border-radius:4px;"
                                           oninput="validateQty(this)">
                                    <br><small style="color:#888;">สูงสุด {{ $maxQty }}</small>
                                @else
                                    <span style="color:#999;">-</span>
                                @endif
                            </td>
                            <td align="center">
                                @if($isComplete)
                                    <span style="background:#1e8e3e; color:#fff; padding:5px 10px; border-radius:20px; font-size:0.8em;">ครบแล้ว</span>
                                @elseif(!$canWithdraw)
                                    <span style="background:#999; color:#fff; padding:5px 10px; border-radius:20px; font-size:0.8em;">ไม่มีของ</span>
                                @else
                                    <span style="background:#d93025; color:#fff; padding:5px 10px; border-radius:20px; font-size:0.8em;">ยังไม่ครบ</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(!$allComplete)
                <div style="margin-top:20px; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn btn-secondary">ไปหน้าถัดไป</button>
                </div>
            @endif
            @endif
        </div>
    </form>
</div>

<script>
document.getElementById('select-all').onclick = function() {
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
};
function validateQty(input) {
    let val = parseInt(input.value) || 0;
    if (val < 1) input.value = 1;
    if (val > parseInt(input.max)) input.value = input.max;
}
</script>
@endsection