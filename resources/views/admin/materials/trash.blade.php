@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')


    <div class="boxmaterial">
       <h3>ข้อมูลวัสดุและอุปกรณ์ที่ถูกลบ</h3> 
    </div>


        <table>
            <thead>
                <tr class="text-center">
                    <th>ภาพ</th>
                    <th>ประเภทวัสดุ</th>
                    <th>รายละเอียด</th>
                    <th>ผู้ลบ</th>
                    <th>วันที่ลบ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($material as $m)
                    <tr>
                        <td class="text-center" style="width:150px">
                            @if ($m->material_type == 'อลูมิเนียม' && $m->aluminiumItem)
                                <img src="data:image/jpeg;base64,{{ base64_encode($m->aluminiumItem->image_aluminium_item) }}" width="120">
                            @elseif ($m->material_type == 'กระจก' && $m->glassItem)
                                <img src="data:image/jpeg;base64,{{ base64_encode($m->glassItem->image_glass_item) }}" width="120">
                            @elseif ($m->material_type == 'อุปกรณ์เสริม' && $m->accessoryItem)
                                <img src="data:image/jpeg;base64,{{ base64_encode($m->accessoryItem->image_accessory_item) }}" width="120">
                            @elseif ($m->material_type == 'เครื่องมือช่าง' && $m->toolItem)
                                <img src="data:image/jpeg;base64,{{ base64_encode($m->toolItem->image_tool_item) }}" width="120">
                            @elseif ($m->material_type == 'วัสดุสิ้นเปลือง' && $m->consumableItem)
                                <img src="data:image/jpeg;base64,{{ base64_encode($m->consumableItem->image_consumable_item) }}" width="120">
                            @else
                                <span>-</span>
                            @endif
                        </td>

                        <td class="text-center">
                            {{ $m->material_type }}
                        </td>

                        <td>
                            @if($m->material_type == 'อลูมิเนียม')
                                {{ $m->aluminiumItem->aluminiumType->name ?? '-' }} |
                                {{ $m->aluminiumItem->aluminumSurfaceFinish->name ?? '-' }} 
                            @elseif($m->material_type == 'กระจก')
                                {{ $m->glassItem->glassType->name ?? '-' }} |
                                {{ $m->glassItem->colourItem->name ?? '-' }}
                            @elseif($m->material_type == 'อุปกรณ์เสริม')
                                {{ $m->accessoryItem->accessoryType->name ?? '-' }}
                            @elseif($m->material_type == 'เครื่องมือช่าง')
                                {{ $m->toolItem->toolType->name ?? '-' }}
                            @elseif($m->material_type == 'วัสดุสิ้นเปลือง')
                                {{ $m->consumableItem->consumabletype->name ?? '-' }}
                            @endif
                        </td>

                        <td class="text-center">{{ $m->user->name ?? '-' }}</td>
                        <td class="text-center">{{ $m->deleted_at->format('d/m/Y H:i') }}</td>

                        <td class="text-center">
                            <form action="{{ route('admin.materials.restore', $m->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-primary">กู้คืน
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">ไม่มีข้อมูลที่ถูกลบ</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
