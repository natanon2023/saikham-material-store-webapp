@extends('layouts.admin')

@section('content')
<div class="main-content">

    <div class="boxmaterial control-section">
        <h3>ชุดผลิตภัณฑ์ : {{ $productset->productSetName->name }}</h3>
        <div style="height: max-content;">
            <a href="{{ route('admin.projects.productsetdetail') }}" class="btn btn-primary">
                ไปยังหน้าผลิตภัณฑ์ทั้งหมด
            </a>
            <a href="{{ route('admin.projects.showitemproduct', $productset->id) }}" class="btn btn-secondary">
                รายการวัสดุทั้งหมดในชุดผลิตภัณฑ์
            </a>
        </div>
        
    </div>

    

    @include('components.successanderror')

    

    

    <div class="control-position">
        @foreach ($material as $mrl)

        @if ($mrl->material_type == 'อลูมิเนียม')
        <div class="boxcarde" style="margin-top: 20px;">

            @php
            $item = $productset->productsetitem->where('material_id',$mrl->id)->first();
            @endphp

            

            @if ($item == false)
            <form action="{{ route('admin.projects.addmaterialproductsetitem') }}" method="post">
                @csrf
                <input type="hidden" name="product_set_id" value="{{ $productset->id }}">
                <input type="hidden" name="material_id" value="{{ $mrl->id }}">

                <div class="boxdetialproductset-control1">
                    <div class="boxstatus">
                        @if ($item && $item->status == 'เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว')
                            <div class="boxstatusadd">เพิ่มวัสดุแล้ว</div>
                        @else
                            <div class="boxstatusnotadd">ยังไม่ถูกเพิ่ม</div>
                        @endif
                    </div>

                    <div style="display: flex; justify-content: end;">
                        <button type="submit" title="เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว" class="btn-additem">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
                    
            </form>
            @else
                <div class="boxdetialproductset-control1">
                    <div class="boxstatus">
                        @if ($item && $item->status == 'เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว')
                            <div class="boxstatusadd">เพิ่มวัสดุแล้ว</div>
                        @else
                            <div class="boxstatusnotadd">ยังไม่ถูกเพิ่ม</div>
                        @endif
                    </div>

                    <form action="{{ route('admin.projects.deletematerialproductsetitem', $item->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="ลบวัสดุในชุดผลิตภัณฑ์แล้ว" class="btn-delectitem">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                    
            @endif

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
            @php
                $item = $productset->productsetitem->where('material_id',$mrl->id)->first();
            @endphp

            @if ($item == false)
            <form action="{{ route('admin.projects.addmaterialproductsetitem') }}" method="post">
                @csrf
                <input type="hidden" name="product_set_id" value="{{ $productset->id }}">
                <input type="hidden" name="material_id" value="{{ $mrl->id }}">

                <div class="boxdetialproductset-control1">
                    <div class="boxstatus">
                        @if ($item && $item->status == 'เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว')
                            <div class="boxstatusadd">เพิ่มวัสดุแล้ว</div>
                        @else
                            <div class="boxstatusnotadd">ยังไม่ถูกเพิ่ม</div>
                        @endif
                    </div>

                    <div style="display: flex; justify-content: end;">
                        <button type="submit" title="เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว" class="btn-additem">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
                    
            </form>
            @else
                <div class="boxdetialproductset-control1">
                    <div class="boxstatus">
                        @if ($item && $item->status == 'เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว')
                            <div class="boxstatusadd">เพิ่มวัสดุแล้ว</div>
                        @else
                            <div class="boxstatusnotadd">ยังไม่ถูกเพิ่ม</div>
                        @endif
                    </div>

                    <form action="{{ route('admin.projects.deletematerialproductsetitem', $item->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="ลบวัสดุในชุดผลิตภัณฑ์แล้ว" class="btn-delectitem">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                    
            @endif

            

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
            @php
                $item = $productset->productsetitem->where('material_id',$mrl->id)->first();
            @endphp

            @if ($item == false)
            <form action="{{ route('admin.projects.addmaterialproductsetitem') }}" method="post">
                @csrf
                <input type="hidden" name="product_set_id" value="{{ $productset->id }}">
                <input type="hidden" name="material_id" value="{{ $mrl->id }}">

                <div class="boxdetialproductset-control1">
                    <div class="boxstatus">
                        @if ($item && $item->status == 'เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว')
                            <div class="boxstatusadd">เพิ่มวัสดุแล้ว</div>
                        @else
                            <div class="boxstatusnotadd">ยังไม่ถูกเพิ่ม</div>
                        @endif
                    </div>

                    <div style="display: flex; justify-content: end;">
                        <button type="submit" title="เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว" class="btn-additem">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
                    
            </form>
            @else
                <div class="boxdetialproductset-control1">
                    <div class="boxstatus">
                        @if ($item && $item->status == 'เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว')
                            <div class="boxstatusadd">เพิ่มวัสดุแล้ว</div>
                        @else
                            <div class="boxstatusnotadd">ยังไม่ถูกเพิ่ม</div>
                        @endif
                    </div>

                    <form action="{{ route('admin.projects.deletematerialproductsetitem', $item->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="ลบวัสดุในชุดผลิตภัณฑ์แล้ว" class="btn-delectitem">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                    
            @endif

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

        @elseif($mrl->material_type == 'วัสดุสิ้นเปลือง')
        <div class="boxcarde" style="margin-top: 20px;">
            @php
                $item = $productset->productsetitem->where('material_id',$mrl->id)->first();
            @endphp

            @if ($item == false)
            <form action="{{ route('admin.projects.addmaterialproductsetitem') }}" method="post">
                @csrf
                <input type="hidden" name="product_set_id" value="{{ $productset->id }}">
                <input type="hidden" name="material_id" value="{{ $mrl->id }}">

                <div class="boxdetialproductset-control1">
                    <div class="boxstatus">
                        @if ($item && $item->status == 'เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว')
                            <div class="boxstatusadd">เพิ่มวัสดุแล้ว</div>
                        @else
                            <div class="boxstatusnotadd">ยังไม่ถูกเพิ่ม</div>
                        @endif
                    </div>

                    <div style="display: flex; justify-content: end;">
                        <button type="submit" title="เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว" class="btn-additem">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
                    
            </form>
            @else
                <div class="boxdetialproductset-control1">
                    <div class="boxstatus">
                        @if ($item && $item->status == 'เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว')
                            <div class="boxstatusadd">เพิ่มวัสดุแล้ว</div>
                        @else
                            <div class="boxstatusnotadd">ยังไม่ถูกเพิ่ม</div>
                        @endif
                    </div>

                    <form action="{{ route('admin.projects.deletematerialproductsetitem', $item->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="ลบวัสดุในชุดผลิตภัณฑ์แล้ว" class="btn-delectitem">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                    
            @endif

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
        @elseif ($mrl->material_type == 'เครื่องมือช่าง')
        <div class="boxcarde" style="margin-top: 20px;">
            @php
                $item = $productset->productsetitem->where('material_id',$mrl->id)->first();
            @endphp

            @if ($item == false)
            <form action="{{ route('admin.projects.addmaterialproductsetitem') }}" method="post">
                @csrf
                <input type="hidden" name="product_set_id" value="{{ $productset->id }}">
                <input type="hidden" name="material_id" value="{{ $mrl->id }}">

                <div class="boxdetialproductset-control1">
                    <div class="boxstatus">
                        @if ($item && $item->status == 'เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว')
                            <div class="boxstatusadd">เพิ่มวัสดุแล้ว</div>
                        @else
                            <div class="boxstatusnotadd">ยังไม่ถูกเพิ่ม</div>
                        @endif
                    </div>

                    <div style="display: flex; justify-content: end;">
                        <button type="submit" title="เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว" class="btn-additem">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
                    
            </form>
            @else
                <div class="boxdetialproductset-control1">
                    <div class="boxstatus">
                        @if ($item && $item->status == 'เพิ่มวัสดุลงในชุดผลิตภัณฑ์แล้ว')
                            <div class="boxstatusadd">เพิ่มวัสดุแล้ว</div>
                        @else
                            <div class="boxstatusnotadd">ยังไม่ถูกเพิ่ม</div>
                        @endif
                    </div>

                    <form action="{{ route('admin.projects.deletematerialproductsetitem', $item->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="ลบวัสดุในชุดผลิตภัณฑ์แล้ว" class="btn-delectitem">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                    
            @endif

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
            </div>
        </div>
        @endif

        @endforeach
    </div>

</div>


@endsection