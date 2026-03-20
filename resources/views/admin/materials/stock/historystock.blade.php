@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="boxmaterial">
        <h3>ประวัติสต็อก</h3>
    </div>
    <div class="box">
        <form method="GET" action="">
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">วันที่เริ่มต้น :</label>
                    <input class="form-input" type="date" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">วันที่สิ้นสุด :</label>
                    <input class="form-input" type="date" name="date_to" value="{{ request('date_to') }}">
                </div>

            </div>
            <div style="display: flex; flex-direction: row-reverse;  gap: 5px; margin-top: 15px;">
                <button class="btn btn-secondary" type="submit" style="margin-right: 5px;">
                    <i class="fa fa-search" aria-hidden="true" style="margin-right: 5px;"></i>
                    ค้นหา
                </button>
                <a class="btn btn-primary" href="{{ route('admin.materials.historystock') }}">
                    <i class="fa fa-refresh" aria-hidden="true" style="margin-right: 5px"></i>
                    ล้างการค้นหา
                </a>
            </div>
        </form>


    </div>

    <table style="text-align: center;">
        <tr>
            <th>ล็อต</th>
            <th>รายการ</th>
            <th>วันที่และเวลา</th>
            <th>ภาพวัสดุ</th>
            <th>รายละเอียดวัสดุ</th>
            <th>จำนวน</th>
            <th>ราคาต้นทุนต่อหน่วย</th>
            <th>ผู้ดำเนินการ</th>
            <th>จัดการ</th>
        </tr>
        @foreach ($materiallog as $log)
        <tr align="center">
            <td style=" height:max-content;">{{ $log->price->lot }}</td>
            <td>
                @if ($log->direction == 'out')
                    @if($log->source == 'withdraw')
                        <div style="background-color: #ffafafff; padding: 5px; width: fit-content; border-radius: 20px;">
                            เบิก
                        </div>
                    @else
                        <div style="background-color: #ffafafff; padding: 5px; width: fit-content; border-radius: 20px;">
                            ออก
                        </div>
                    @endif
                @else
                    @php
                        $sourceLabel = match($log->source ?? '') {
                            'restock'         => ['label' => 'เติมสต็อก',       'color' => '#bcffaf'],
                            'return_material' => ['label' => 'คืนวัสดุ',         'color' => '#bcffaf'],
                            'return_tool'     => ['label' => 'คืนเครื่องมือ',    'color' => '#bcffaf'],
                            'issue_refill'    => ['label' => 'เติมจากปัญหา',     'color' => '#ffe0b2'],
                            'manual'          => ['label' => 'แก้ไขจำนวน',       'color' => '#e1d5ff'],
                            default           => ['label' => 'เข้า',             'color' => '#bcffaf'],
                        };
                    @endphp
                    <div style="background-color: {{ $sourceLabel['color'] }}; padding: 5px; width: fit-content; border-radius: 20px; white-space: nowrap;">
                        {{ $sourceLabel['label'] }}
                    </div>
                @endif
            </td>
            <td>
                {{ $log->created_at->locale('th')->translatedFormat('d F') }} {{ $log->created_at->year + 543 }} เวลา {{ $log->created_at->format('H:i') }} น.
            </td>
            <td>
                @if ($log->material->material_type == 'อลูมิเนียม')
                <img src="data:image/jpeg;base64,{{ base64_encode($log->material->aluminiumItem->image_aluminium_item) }}" class="imgposition4">
                @elseif ($log->material->material_type == 'กระจก')
                <img src="data:image/jpeg;base64,{{ base64_encode($log->material->glassItem->image_glass_item) }}" class="imgposition4">
                @elseif ($log->material->material_type == 'อุปกรณ์เสริม')
                <img src="data:image/jpeg;base64,{{ base64_encode($log->material->accessoryItem->image_accessory_item) }}" class="imgposition4">
                @elseif ($log->material->material_type == 'วัสดุสิ้นเปลือง')
                <img src="data:image/jpeg;base64,{{ base64_encode($log->material->consumableItem->image_consumable_item) }}" class="imgposition4">
                @elseif ($log->material->material_type == 'เครื่องมือช่าง')
                <img src="data:image/jpeg;base64,{{ base64_encode($log->material->toolItem->image_tool_item) }}" class="imgposition4">
                @endif
            </td>
            <td>
                @if ($log->material->material_type == 'อลูมิเนียม')
                <small>
                    อลูมิเนียม -
                    {{ $log->material->aluminiumItem->aluminiumType->name }} -
                    {{ $log->material->aluminiumItem->aluminumSurfaceFinish->name }} -
                    {{ $log->material->aluminiumItem->aluminiumLengths->length_meter.' เมตร' }} 
                </small>
                @elseif ($log->material->material_type == 'กระจก')
                <small>
                    กระจก -
                    {{ $log->material->glassItem->glassType->name }} -
                    {{ 'สี'.$log->material->glassItem->colourItem->name }} -
                    {{ 'ขนาด(กว้าง*สูง) '.'('.$log->material->glassItem->glassSize->width_meter.'*'.$log->material->glassItem->glassSize->length_meter.')'.' ซม.' }} -
                    {{ $log->material->glassItem->glassSize->thickness.' มิลลิเมตร' }}
                </small>

                @elseif ($log->material->material_type == 'อุปกรณ์เสริม')
                <small>
                    อุปกรณ์เสริม -
                    {{ $log->material->accessoryItem->accessoryType->name }} -
                    {{ $log->material->accessoryItem->aluminumSurfaceFinish->name }}
                </small>

                @elseif ($log->material->material_type == 'วัสดุสิ้นเปลือง')
                <small>
                    วัสดุสิ้นเปลือง -
                    {{ $log->material->consumableItem->consumabletype->name }}
                </small>

                @elseif ($log->material->material_type == 'เครื่องมือช่าง')
                <small>
                    เครื่องมือช่าง -
                    {{ $log->material->toolItem->toolType->name }}
                    <p style="font-size: xx-small;">
                        {{ $log->material->toolItem->description }}
                    </p>
                </small>
                @endif
                ({{ $log->price->dealer->name }})
            </td>
            <td>
                {{ $log->quantitylog }}
            </td>
            <td>
                {{ $log->price->price }}
            </td>
            <td style="font-size: xx-small;">{{ $log->user->name }}</td>
            <td>
               @if(($log->source ?? 'restock') == 'restock')
                    <a href="{{ route('admin.materials.formeditsatock',$log->price_id) }}" class="btn-icon btn-edit" title="แก้ไข">
                        <i class="fas fa-edit"></i>
                    </a>
                @else
                    <span style="color: #ccc;">-</span>
                @endif
            </td>
        </tr>
        @endforeach
        @if ($materiallog->isEmpty())
        <tr>
            <td colspan="9" style="padding:20px;">
                ไม่พบประวัติสต็อกในช่วงวันที่ที่เลือก
            </td>
        </tr>
        @endif


    </table>
</div>

@endsection