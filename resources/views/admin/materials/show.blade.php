@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')


    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>รายละเอียดวัสดุ</h3>
        <a href="{{ route('admin.materials.index') }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>



    <div>
        <div class="box" style="margin-top: 20px; padding : 20px;">
            <strong>ประเภทวัสดุ :</strong>{{ $material->material_type }} |
            <strong>ผู้เพิ่มข้อมูล :</strong> {{ $material->user->name }} |
            <strong>วันที่เพิ่มข้อมูล : </strong> {{ $material->created_at->locale('th')->translatedFormat('d F') }} {{ $material->created_at->year + 543 }}
        </div>



        <div>
            @if ($material->material_type === 'อลูมิเนียม')
            <div class="card-dt">
                <div class="card-dt-img">

                    <div class="card-dt-img-control">
                        <img src="data:image/jpeg;base64,{{ base64_encode($material->aluminiumItem->image_aluminium_item) }}" class="imgposition1">
                    </div>
                </div>

                <div class="dt-text">
                    <div class="dt-text1">
                        <div style="display: block;">
                            <strong>ประเภทย่อย : </strong>{{ $material->aluminiumItem->aluminiumType->name }} <br>
                            <strong>สี : </strong>{{ $material->aluminiumItem->aluminumSurfaceFinish->name }} <br>
                        </div>


                        <div>
                            @if ($material->price->sum('quantity') > 0)
                            <p class="stockbox1">{{ $material->price->sum('quantity') }} เส้น</p>
                            @elseif ($material->price->sum('quantity') <= 0)
                            <div class="stockbox1" style="background-color: #C94A4A;">
                                หมดสต็อก
                            </div>
                            @endif
                            
                        </div>

                    </div>

                    <div class="dt-text2">
                        <a href="{{ route('admin.materials.editmaterial',$material->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST"
                            style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
            @elseif ($material->material_type === 'กระจก')
            <div class="card-dt">
                <div class="card-dt-img">

                    <div class="card-dt-img-control">
                        <img src="data:image/jpeg;base64,{{ base64_encode($material->glassItem->image_glass_item) }}" class="imgposition1">
                    </div>
                </div>

                <div class="dt-text">
                    <div class="dt-text1">
                        <div style="display: block;">
                            <strong>ประเภทย่อย : </strong>{{ $material->glassItem->glassType->name }} <br>
                            <strong>สี : </strong>{{ $material->glassItem->colourItem->name }} <br>
                        </div>

                        <div>
                            @if ($material->price->sum('quantity') > 0)
                            <p class="stockbox1">{{ $material->price->sum('quantity') }} แผ่น</p>
                            @elseif ($material->price->sum('quantity') <= 0)
                            <div class="stockbox1" style="background-color: #C94A4A;">
                                หมดสต็อก
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="dt-text2">
                        <a href="{{ route('admin.materials.editmaterial',$material->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST"
                            style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @elseif($material->material_type === 'อุปกรณ์เสริม')
            <div class="card-dt">
                <div class="card-dt-img">

                    <div class="card-dt-img-control">
                        <img src="data:image/jpeg;base64,{{ base64_encode($material->accessoryItem->image_accessory_item) }}" class="imgposition1">
                    </div>
                </div>

                <div class="dt-text">
                    <div class="dt-text1">
                        <div style="display: block;">
                            <strong>ประเภทย่อย : </strong>{{ $material->accessoryItem->accessoryType->name }} <br>
                            <strong>สี : </strong>{{ $material->accessoryItem->aluminumSurfaceFinish->name }} <br>
                        </div>

                        <div>
                            
                            @if ($material->price->sum('quantity') > 0)
                            <p class="stockbox1">{{ $material->price->sum('quantity') }} {{ $material->accessoryItem->unit->name }}</p>
                            @elseif ($material->price->sum('quantity') <= 0)
                            <div class="stockbox1" style="background-color: #C94A4A;">
                                หมดสต็อก
                            </div>
                            @endif
                        </div>

                    </div>

                    <div class="dt-text2">
                        <a href="{{ route('admin.materials.editmaterial',$material->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST"
                            style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @elseif($material->material_type === 'เครื่องมือช่าง')
            <div class="card-dt">
                <div class="card-dt-img">
                    <div class="card-dt-img-control">
                        <img src="data:image/jpeg;base64,{{ base64_encode($material->toolItem->image_tool_item) }}" class="imgposition1">
                    </div>
                </div>

                <div class="dt-text">
                    <div class="dt-text1">
                        <div style="display: block;">
                            <strong>ชื่อเครื่องมือช่าง : </strong>{{ $material->toolItem->toolType->name }} <br>
                            <strong>หมายเหตุ : </strong>{{ $material->toolItem->description }}
                        </div>

                        <div>
                            @if ($material->price->sum('quantity') > 0)
                            <p class="stockbox1">{{ $material->price->sum('quantity') }} {{ $material->toolItem->unit->name }}</p>
                            @elseif ($material->price->sum('quantity') <= 0)
                            <div class="stockbox1" style="background-color: #C94A4A;">
                                หมดสต็อก
                            </div>
                            @endif
                            
                        </div>
                    </div>

                    <div class="dt-text2">
                        <a href="{{ route('admin.materials.editmaterial',$material->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST"
                            style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @elseif($material->material_type === 'วัสดุสิ้นเปลือง')
            <div class="card-dt">
                <div class="card-dt-img">

                    <div class="card-dt-img-control">
                        <img src="data:image/jpeg;base64,{{ base64_encode($material->consumableItem->image_consumable_item) }}" class="imgposition1">
                    </div>
                </div>

                <div class="dt-text">
                    <div class="dt-text1">
                        <div style="display: block;">
                            <strong>ชื่อวัสดุสิ้นเปลือง : </strong>{{ $material->consumableItem->consumabletype->name }} <br>
                        </div>

                        <div>
                            @if ($material->price->sum('quantity') > 0)
                            <p class="stockbox1">{{ $material->price->sum('quantity') }} {{ $material->consumableItem->unit->name }}</p>
                            @elseif ($material->price->sum('quantity') <= 0)
                            <div class="stockbox1" style="background-color: #C94A4A;">
                                หมดสต็อก
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="dt-text2">
                        <a href="{{ route('admin.materials.editmaterial',$material->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST"
                            style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    


    <div style="margin-top: 20px; padding : 20px; background-color: white; display: flex; justify-content: space-between; align-items:center;">
        <strong>รายการการเคลื่อนไหวของวัสดุ</strong>
        <div style="display:flex; gap:10px; align-items:center;">
                <a href="{{ route('admin.materials.showdetailmaterial', $material->id) }}"
                    class="btn {{ request('direction') == '' ? 'btn-primary' : 'btn-secondary' }}">
                    ดูทั้งหมด
                </a>

                <a href="{{ route('admin.materials.showdetailmaterial', ['id' => $material->id, 'direction' => 'in']) }}"
                    class="btn {{ request('direction') == 'in' ? 'btn-primary' : 'btn-secondary' }}"
                    style=" {{ request('direction') == 'in' ? 'background-color: #bcffafff; color: #000000' : '' }}">
                    รายการรับเข้า
                </a>

                <a href="{{ route('admin.materials.showdetailmaterial', ['id' => $material->id, 'direction' => 'out']) }}"
                    class="btn {{ request('direction') == 'out' ? 'btn-primary' : 'btn-secondary' }}"
                    style=" {{ request('direction') == 'out' ? 'background-color: #ffafafff; color: #040404;' : '' }}">
                    รายการเบิกออก
                </a>
            </div>
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
        @foreach ($material->materialLogs as $log)
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
                    {{ $log->price->aluminiumLength->length_meter.' เมตร' }}
                </small>
                @elseif ($log->material->material_type == 'กระจก')
                <small>
                    กระจก -
                    {{ $log->material->glassItem->glassType->name }} -
                    {{ 'สี'.$log->material->glassItem->colourItem->name }} -
                    {{ 'ขนาด(กว้าง*สูง) '.'('.$log->price->glassSize->width_meter.'*'.$log->price->glassSize->length_meter.')'.' ซม.' }} -
                    {{ $log->price->glassSize->thickness.' มิลลิเมตร' }}
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
        @if ($material->materialLogs->isEmpty())
        <tr>
            <td colspan="9" style="padding:20px;">
                ยังไม่มีประวัติการเคลื่นไหวสต็อก
            </td>
        </tr>
        @endif


    </table>

</div>

@endsection