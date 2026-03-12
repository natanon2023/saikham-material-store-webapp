@php
    $currentStep = (int) request('step', 1);

    $backUrl = null;
    if($currentStep > 1){
        $backParams = ['step' => $currentStep - 1];

        if(request()->has('material_type')) $backParams['material_type'] = request('material_type');
        if(request()->has('sub_type')) $backParams['sub_type'] = request('sub_type');
        if(request()->has('item_id')) $backParams['item_id'] = request('item_id');
        if(request()->has('action')) $backParams['action'] = request('action');

        $backUrl = route('admin.materials.stock', $backParams);
    }
@endphp

<div style="background-color: #fff; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h4>ขั้นตอนการจัดการสต็อกวัสดุและอุปกรณ์</h4>

        @if($backUrl)
            <a href="{{ $backUrl }}" class="btn btn-secondary">ย้อนกลับ</a>
        @endif
    </div>

    <div style="display: flex; flex-direction: row; text-align: center;">
        @for($i=1; $i<=5; $i++)
            @php
                $stepText = [
                    1 => 'เลือกประเภทวัสดุหลัก',
                    2 => 'เลือกประเภทย่อย',
                    3 => 'เลือกรายการวัสดุ',
                    4 => 'เลือกการจัดการสต็อก',
                    5 => 'จัดการสต็อก',
                ][$i];
            @endphp
            <div style="flex: 1; padding: 10px; {{ $currentStep == $i ? 'background-color:#79c4f6; color:#fff;' : 'border:1px solid #79c4f6;' }}">
                ขั้นตอนที่ {{ $i }} {{ $stepText }}
            </div>
        @endfor
    </div>
</div>
