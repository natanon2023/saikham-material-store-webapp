@extends('layouts.admin')

@section('content')
<div class="main-content">



    <div class="boxmaterial">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>จัดการวัสดุและอุปกรณ์</h3>
            <a href="{{ route('admin.materials.showselecttypematerials') }}" class="btn btn-secondary">+ เพิ่มวัสดุใหม่</a>
        </div>
    </div>

    <div style="display: flex; justify-content: center; width: 100%; margin-top:20px;" class="boxmaterial">

        <div class=" box-control" style="width: fit-content;">

            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; justify-content: center;">
                <a href="{{ route('admin.materials.index') }}"
                    class="btn {{ request('material_type') == '' ? 'btn-primary' : 'btn-secondary' }}">ทั้งหมด</a>

                <a href="{{ route('admin.materials.index', ['material_type' => 'aluminum']) }}"
                    class="btn {{ request('material_type') == 'aluminum' ? 'btn-primary' : 'btn-secondary' }}">อลูมิเนียม</a>

                <a href="{{ route('admin.materials.index', ['material_type' => 'glass']) }}"
                    class="btn {{ request('material_type') == 'glass' ? 'btn-primary' : 'btn-secondary' }}">กระจก</a>

                <a href="{{ route('admin.materials.index', ['material_type' => 'accessory']) }}"
                    class="btn {{ request('material_type') == 'accessory' ? 'btn-primary' : 'btn-secondary' }}">อุปกรณ์เสริม</a>

                <a href="{{ route('admin.materials.index', ['material_type' => 'consumable']) }}"
                    class="btn {{ request('material_type') == 'consumable' ? 'btn-primary' : 'btn-secondary' }}">วัสดุสิ้นเปลือง</a>

                <a href="{{ route('admin.materials.index', ['material_type' => 'tool']) }}"
                    class="btn {{ request('material_type') == 'tool' ? 'btn-primary' : 'btn-secondary' }}">อุปกรณ์ช่าง</a>
            </div>

        </div>

    </div>

   <div style="margin-top: 20px;">
    @include('components.successanderror')

   </div> 
    <div style="margin-top:20px;">
        จำนวนรายการวัสดุและอุปกรณ์ทั้งหมด : <span>{{ $material->count() }}</span> รายการ
    </div>

    <div class="control-position">
        @foreach ($material as $mrl)
        @if ($mrl->material_type == 'อลูมิเนียม')

        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('admin.materials.showdetailmaterial',$mrl->id) }}" class="btn-icon btn-show" title="ดูรายละเอียด">
                    <i class="fas fa-eye"></i>
                </a>

                @if($mrl->price->sum('quantity') > 0)
                <div class="boxstatus boxstatusadd">
                    {{ $mrl->price->sum('quantity') }} เส้น
                </div>
                @else
                <div class="boxstatus boxstatusnotadd">
                    <strong>หมดสต็อก</strong>
                </div>
                @endif

            </div>
            <div>
                <img src="data:image/jpeg;base64,{{ base64_encode($mrl->aluminiumItem->image_aluminium_item) }}" class="imgposition">
            </div>
            <div class="boxdetail" style="display: flex; flex-direction: column; gap: 5px;">
                <div style="font-weight: bold; font-size: 14px;">
                    ALM-{{ $mrl->aluminiumItem->created_at }} - {{ $mrl->material_type }}
                </div>
                <div style="font-size: 13px;">
                    {{ $mrl->aluminiumItem->aluminiumType->name }} | {{ $mrl->aluminiumItem->aluminumSurfaceFinish->name }}
                </div>
            </div>
        </div>


        @elseif ($mrl->material_type == 'กระจก')
        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('admin.materials.showdetailmaterial',$mrl->id) }}" class="btn-icon btn-show" title="ดูรายละเอียด">
                    <i class="fas fa-eye"></i>
                </a>
                @if ($mrl->price->sum('quantity') > 0)
                <div class="boxstatus boxstatusadd">
                    {{ $mrl->price->sum('quantity') }} แผ่น
                </div>
                @else
                <div class="boxstatus boxstatusnotadd">
                    <strong>หมดสต็อก</strong>
                </div>
                @endif


            </div>
            <div>
                <img src="data:image/jpeg;base64,{{ base64_encode($mrl->glassItem->image_glass_item) }}" class="imgposition">
            </div>
            <div class="boxdetail" style="display: flex; flex-direction: column; gap: 5px;">
                <div style="font-weight: bold; font-size: 14px;">
                    GLS-{{ $mrl->glassItem->created_at }} - {{ $mrl->material_type }}
                </div>
                <div style="font-size: 13px;">
                    {{ $mrl->glassItem->glassType->name }} | {{ $mrl->glassItem->colourItem->name }}
                </div>
            </div>
        </div>


        @elseif($mrl->material_type == 'อุปกรณ์เสริม')
        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('admin.materials.showdetailmaterial',$mrl->id) }}" class="btn-icon btn-show" title="ดูรายละเอียด">
                    <i class="fas fa-eye"></i>
                </a>
                @if ($mrl->price->sum('quantity') > 0)
                <div class="boxstatus boxstatusadd">
                    {{ $mrl->price->sum('quantity') }} {{ $mrl->accessoryItem->unit->name}}
                </div>
                @else
                <div class="boxstatus boxstatusnotadd">
                    <strong>หมดสต็อก</strong>
                </div>
                @endif

            </div>
            <div>
                <img src="data:image/jpeg;base64,{{ base64_encode($mrl->accessoryItem->image_accessory_item) }}" class="imgposition">
            </div>
            <div class="boxdetail" style="display: flex; flex-direction: column; gap: 5px;">
                <div style="font-weight: bold; font-size: 14px;">
                    ASS-{{ $mrl->accessoryItem->created_at }} - {{ $mrl->material_type }}
                </div>
                <div style="font-size: 13px;">
                    {{ $mrl->accessoryItem->accessoryType->name}} | {{ $mrl->accessoryItem->aluminumSurfaceFinish->name }}
                </div>
            </div>
        </div>

        @elseif($mrl->material_type == 'เครื่องมือช่าง')
        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('admin.materials.showdetailmaterial',$mrl->id) }}" class="btn-icon btn-show" title="ดูรายละเอียด">
                    <i class="fas fa-eye"></i>
                </a>
                @if ($mrl->price->sum('quantity') > 0 )
                <div class="boxstatus boxstatusadd">
                    {{ $mrl->price->sum('quantity') }} {{ $mrl->toolItem->unit->name}}
                </div>
                @else
                <div class="boxstatus boxstatusnotadd">
                    <strong>หมดสต็อก</strong>
                </div>
                @endif

            </div>
            <div>
                <img src="data:image/jpeg;base64,{{ base64_encode($mrl->toolItem->image_tool_item) }}" class="imgposition">
            </div>
            <div class="boxdetail" style="display: flex; flex-direction: column; gap: 5px;">
                <div style="font-weight: bold; font-size: 14px;">
                    TOOL-{{ $mrl->toolItem->created_at }} - {{ $mrl->material_type }}
                </div>
                <div style="font-size: 13px;">
                    {{ $mrl->toolItem->toolType->name}}
                </div>

                <div style="font-size: 12px;">
                    หมายเหตุ : {{ $mrl->toolItem->description }}
                </div>
            </div>
        </div>
        @elseif($mrl->material_type == ('วัสดุสิ้นเปลือง'))
        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('admin.materials.showdetailmaterial',$mrl->id) }}" class="btn-icon btn-show" title="ดูรายละเอียด">
                    <i class="fas fa-eye"></i>
                </a>
                @if ($mrl->price->sum('quantity') > 0)
                <div class="boxstatus boxstatusadd">
                    {{ $mrl->price->sum('quantity') }} {{ $mrl->consumableItem->unit->name}}
                </div>
                @else
                <div class="boxstatus boxstatusnotadd">
                    <strong>หมดสต็อก</strong>
                </div>
                @endif

            </div>
            <div>
                <img src="data:image/jpeg;base64,{{ base64_encode($mrl->consumableItem->image_consumable_item) }}" class="imgposition">
            </div>
            <div class="boxdetail" style="display: flex; flex-direction: column; gap: 5px;">
                <div style="font-weight: bold; font-size: 14px;">
                    SMB-{{ $mrl->consumableItem->created_at }} - {{ $mrl->material_type }}
                </div>
                <div style="font-size: 13px;">
                    {{ $mrl->consumableItem->consumabletype->name}}
                </div>
            </div>
        </div>

        @endif






        @endforeach
    </div>
</div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('material_type').addEventListener('change', function() {
            const subBox = document.getElementById('subtype');
            if (this.value === 'อลูมิเนียม' || this.value === 'กระจก') {
                subBox.style.display = 'block';
            } else {
                subBox.style.display = 'none';
            }
        });
    });
</script>
@endsection