@extends('layouts.admin')

@section('content')

<div class="main-content">
    @include('components.successanderror')

    <div style="margin-top: 20px;">
        <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;  margin-bottom: 20px;">
            <h3 style="margin: 0;">เติมสต็อกวัสดุ</h3>
            <a href="{{ route('admin.projects.index',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
        <form action="{{ route('admin.projects.processrestock', $project->id) }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            @php 
            $hasMissingItems = false; 
            @endphp

            @foreach ($project->customerneed as $need)
                @php
                    $missingItems = $need->productset->productsetitem->whereIn('calculated_lot', ['ไม่มีของหรือขนาดไม่พอ', 'ไม่มีของ/ขนาดไม่พอ', 'ไม่มีของ'])->sortBy('material.material_type');
                @endphp

                @if($missingItems->isNotEmpty())
                    @php 
                    $hasMissingItems = true; 
                    @endphp
                    

                    <table border="1" width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin-bottom: 20px;">
                        <thead style="background:#333; color:#fff;">
                            <tr align="center">
                                <th width="10%">รูปภาพ</th>
                                <th width="20%">ประเภท / รายละเอียด</th>
                                <th width="10%">ต้องใช้</th>
                                <th width="60%">จัดการข้อมูลสต็อกใหม่ (รับเข้า)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($missingItems as $item)
                                @php $mat = $item->material; @endphp
                                <tr>
                                    <td align="center">
                                        @php
                                            $shown = false;
                                        @endphp

                                        @if(!empty($mat->image))
                                            <img src="data:image/png;base64,{{ base64_encode($mat->image) }}" class="imgposition4">
                                            @php $shown = true; @endphp
                                        @endif

                                        @if(!$shown && optional($mat->aluminiumItem)->image_aluminium_item)
                                            <img src="data:image/jpeg;base64,{{ base64_encode(optional($mat->aluminiumItem)->image_aluminium_item) }}" class="imgposition4">
                                            @php $shown = true; @endphp
                                        @endif

                                        @if(!$shown && optional($mat->glassItem)->image_glass_item)
                                            <img src="data:image/jpeg;base64,{{ base64_encode(optional($mat->glassItem)->image_glass_item) }}" class="imgposition4">
                                            @php $shown = true; @endphp
                                        @endif

                                        @if(!$shown && optional($mat->accessoryItem)->image_accessory_item)
                                            <img src="data:image/jpeg;base64,{{ base64_encode(optional($mat->accessoryItem)->image_accessory_item) }}" class="imgposition4">
                                            @php $shown = true; @endphp
                                        @endif

                                        @if(!$shown && optional($mat->consumableItem)->image_consumable_item)
                                            <img src="data:image/jpeg;base64,{{ base64_encode(optional($mat->consumableItem)->image_consumable_item) }}" class="imgposition4">
                                            @php $shown = true; @endphp
                                        @endif

                                        @if(!$shown && optional($mat->toolItem)->image_tool_item)
                                            <img src="data:image/jpeg;base64,{{ base64_encode(optional($mat->toolItem)->image_tool_item) }}" class="imgposition4">
                                            @php $shown = true; @endphp
                                        @endif

                                        @if(!$shown)
                                            <div style="width: 60px; height: 60px; background-color: #eee; display: flex; justify-content: center; align-items: center; border-radius: 4px; color: #aaa; font-size: 0.8em;">ไม่มีรูป</div>
                                        @endif
                                    </td>

                                    <td>
                                        <b>{{ $mat->material_type }}</b><br>
                                        @if($mat->aluminiumItem)
                                            <small>{{ $mat->aluminiumItem->aluminiumType->name ?? '-' }} (สี {{ $mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-' }})</small>
                                        @elseif($mat->glassItem)
                                            <small>{{ $mat->glassItem->glassType->name ?? '-' }} (สี {{ $mat->glassItem->colourItem->name ?? '-' }})</small>
                                        @elseif($mat->accessoryItem)
                                            <small>{{ $mat->accessoryItem->accessoryType->name ?? '-' }}</small>
                                        @elseif($mat->consumableItem)
                                            <small>{{ $mat->consumableItem->consumabletype->name ?? '-' }}</small>
                                        @endif
                                    </td>

                                    <td align="center">
                                        <span>{{ $item->calculated_qty }}
                                            @if ($mat->aluminiumItem)
                                                <small>เส้น</small>
                                            @elseif($mat->glassItem)
                                                <small>แผ่น</small>
                                            @elseif($mat->accessoryItem)
                                                <small>{{ $mat->accessoryItem->unit->name ?? '-' }}</small>
                                            @elseif($mat->consumableItem)
                                                <small>{{ $mat->consumableItem->unit->name ?? '-' }}</small>
                                            @endif
                                        </span>
                                    </td>

                                    <td>
                                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                            
                                            <div style="flex: 1; min-width: 120px;">
                                                <label style="font-size: 0.8em; color: gray;">ร้านตัวแทนจำหน่าย</label>
                                                <select name="restock[{{ $mat->id }}][dealer_id]" class="form-select" style="width: 100%; padding: 5px;">
                                                    <option value="">เลือกร้าน</option>
                                                    @foreach($dealers as $dealer)
                                                        <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div style="width: 80px;">
                                                <label style="font-size: 0.8em; color: gray;">จำนวน</label>
                                                <input type="number" name="restock[{{ $mat->id }}][qty]" class="form-input" min="1" style="width: 100%; padding: 5px;">
                                            </div>
                                            <div style="width: 100px;">
                                                <label style="font-size: 0.8em; color: gray;">ต้นทุน/หน่วย</label>
                                                <input type="number" name="restock[{{ $mat->id }}][price]" step="0.01" class="form-input" min="0" style="width: 100%; padding: 5px;">
                                            </div>

                                            @if($mat->material_type == 'อลูมิเนียม')
                                                <div style="width: 100px;">
                                                    <label style="font-size: 0.8em; color: gray;">ความยาว (เมตร)</label>
                                                    <input type="number" step="0.01" name="restock[{{ $mat->id }}][length_meter]" class="form-input" style="width: 100%; padding: 5px;">
                                                </div>
                                            @elseif($mat->material_type == 'กระจก')
                                                <div style="width: 70px;">
                                                    <label style="font-size: 0.8em; color: gray;">กว้าง (ม.)</label>
                                                    <input type="number" step="0.01" name="restock[{{ $mat->id }}][width_meter]" class="form-input" style="width: 100%; padding: 5px;">
                                                </div>
                                                <div style="width: 70px;">
                                                    <label style="font-size: 0.8em; color: gray;">ยาว (ม.)</label>
                                                    <input type="number" step="0.01" name="restock[{{ $mat->id }}][length_meter]" class="form-input" style="width: 100%; padding: 5px;">
                                                </div>
                                                <div style="width: 70px;">
                                                    <label style="font-size: 0.8em; color: gray;">หนา (มม.)</label>
                                                    <input type="number" step="0.1" name="restock[{{ $mat->id }}][thickness]" class="form-input" style="width: 100%; padding: 5px;">
                                                </div>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach

            @if($hasMissingItems)
                <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="submit" class="btn btn-secondary" >
                        บันทึกสต็อกวัสดุ
                    </button>
                </div>
            </form>
            @else
            </form>
                <div style="text-align: center; padding: 40px; background-color: #e9fce9; color: #28a745; border-radius: 8px;">
                    <h4>วัสดุครบแล้ว</h4>
                </div>
                <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                    <form action="{{ route('admin.projects.updatestatusreadytowithdraw', $project->id) }}" method="post">
                        @csrf
                        <button class="btn btn-secondary " style="height: max-content;">ยืนยันวัสดุครบและเตรียมการเบิก</button>
                    </form>

                </div>
            @endif

        
    </div>
</div>
@endsection