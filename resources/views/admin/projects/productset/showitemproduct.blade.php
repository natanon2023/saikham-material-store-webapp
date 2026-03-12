@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="boxmaterial control-section">
        <h3>รายการวัสดุในชุดผลิตภัณฑ์{{ $productset->productSetName->name }}ทั้งหมด</h3>
        <a href="{{ route('admin.projects.formaddproductsetitem',$productset->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div style="margin-top: 10px;">
        @include('components.successanderror')
    </div>


    <div style="margin-top:20px; display: flex ; justify-content: space-between;">
        <p> {{ 'จำนวนรายการวัสดุและอุปกรณ์ทั้งหมด : '.$productset->productsetitem->count().' รายการ' }} </p>
    </div>

    <div class="control-position">
        @foreach ($productset->productsetitem as $item)

        @if ($item->material->material_type == 'อลูมิเนียม')
        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: end;">
                <form action="{{ route('admin.projects.deletematerialproductsetitem', $item->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="ลบวัสดุในชุดผลิตภัณฑ์แล้ว" class="btn-delectitem">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>

            <div>
                <img src="data:image/jpeg;base64,{{ base64_encode($item->material->aluminiumItem->image_aluminium_item) }}" class="imgposition">
            </div>
            <div class="boxdetail" style="display: flex; flex-direction: column; gap: 5px;">
                <div style="font-weight: bold; font-size: 14px;">
                    ALM-{{ $item->material->aluminiumItem->created_at }} - {{ $item->material->material_type }}
                </div>
                <div style="font-size: 13px;">
                    {{ $item->material->aluminiumItem->aluminiumType->name }} | {{ $item->material->aluminiumItem->aluminumSurfaceFinish->name }}
                </div>
            </div>



        </div>


        @elseif ($item->material->material_type == 'กระจก')
        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: end;">
                <form action="{{ route('admin.projects.deletematerialproductsetitem', $item->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="ลบวัสดุในชุดผลิตภัณฑ์แล้ว" class="btn-delectitem">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
            <div>
                <img src="data:image/jpeg;base64,{{ base64_encode($item->material->glassItem->image_glass_item) }}" class="imgposition">
            </div>
            <div class="boxdetail" style="display: flex; flex-direction: column; gap: 5px;">
                <div style="font-weight: bold; font-size: 14px;">
                    GLS-{{ $item->material->glassItem->created_at }} - {{ $item->material->material_type }}
                </div>
                <div style="font-size: 13px;">
                    {{ $item->material->glassItem->glassType->name }} | {{ $item->material->glassItem->colourItem->name }}
                </div>
            </div>

        </div>
        @elseif($item->material->material_type == 'อุปกรณ์เสริม')
        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: end;">
                <form action="{{ route('admin.projects.deletematerialproductsetitem', $item->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="ลบวัสดุในชุดผลิตภัณฑ์แล้ว" class="btn-delectitem">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
            <div>
                <img src="data:image/jpeg;base64,{{ base64_encode($item->material->accessoryItem->image_accessory_item) }}" class="imgposition">
            </div>
            <div class="boxdetail" style="display: flex; flex-direction: column; gap: 5px;">
                <div style="font-weight: bold; font-size: 14px;">
                    ASS-{{ $item->material->accessoryItem->created_at }} - {{ $item->material->material_type }}
                </div>
                <div style="font-size: 13px;">
                    {{ $item->material->accessoryItem->accessoryType->name}} | {{ $item->material->accessoryItem->aluminumSurfaceFinish->name }}
                </div>
            </div>

        </div>
        @elseif($item->material->material_type == 'วัสดุสิ้นเปลือง')
        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: end;">
                <form action="{{ route('admin.projects.deletematerialproductsetitem', $item->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="ลบวัสดุในชุดผลิตภัณฑ์แล้ว" class="btn-delectitem">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
            <div>
                <img src="data:image/jpeg;base64,{{ base64_encode($item->material->consumableItem->image_consumable_item) }}" class="imgposition">
            </div>
            <div class="boxdetail" style="display: flex; flex-direction: column; gap: 5px;">
                <div style="font-weight: bold; font-size: 14px;">
                    SMB-{{ $item->material->consumableItem->created_at }} - {{ $item->material->material_type }}
                </div>
                <div style="font-size: 13px;">
                    {{ $item->material->consumableItem->consumabletype->name}}
                </div>
            </div>

        </div>
        @elseif ($item->material->material_type == 'เครื่องมือช่าง')
        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: end;">
                <form action="{{ route('admin.projects.deletematerialproductsetitem', $item->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="ลบวัสดุในชุดผลิตภัณฑ์แล้ว" class="btn-delectitem">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
            <div>
                <img src="data:image/jpeg;base64,{{ base64_encode($item->material->toolItem->image_tool_item) }}" class="imgposition">
            </div>
            <div class="boxdetail" style="display: flex; flex-direction: column; gap: 5px;">
                <div style="font-weight: bold; font-size: 14px;">
                    TOOL-{{ $item->material->toolItem->created_at }} - {{ $item->material->material_type }}
                </div>
                <div style="font-size: 13px;">
                    {{ $item->material->toolItem->toolType->name}}
                </div>
            </div>

        </div>
        @endif
        @endforeach
    </div>

</div>

@endsection