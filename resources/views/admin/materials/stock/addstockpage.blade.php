@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="boxmaterial control-section">
        <h3>เพิ่มสต็อก</h3>
    </div>

    <div class="box">
        <form action="{{ route('admin.materials.addstockpage') }}" method="get">
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ค้นหาจาก:</label>
                    <select id="searchtype" class="form-select" name="material_type">
                        <option value="">ทั้งหมด</option>
                        <option value="aluminum">อลูมิเนียม</option>
                        <option value="glass">กระจก</option>
                        <option value="accessory">อุปกรณ์เสริม</option>
                        <option value="consumable">วัสดุสิ้นเปลือง</option>
                        <option value="tool">อุปกรณ์ช่าง</option>
                    </select>
                </div>

                <div id="aluminum" style="display: none;">
                    <div class="box-control">
                        <div class="form-group">
                            <label class="form-label">ประเภทอลูมิเนียม:</label>
                            <select name="aluminium_type_id" class="form-select">
                                <option value="">ทั้งหมด</option>
                                @foreach($aluminumtype as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">สีอลูมิเนียม:</label>
                            <select name="aluminium_surface_id" class="form-select">
                                <option value="">ทั้งหมด</option>
                                @foreach($aluminumSurfaces as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="glass" style="display: none;">
                    <div class="box-control">
                        <div class="form-group">
                            <label class="form-label">ประเภทกระจก:</label>
                            <select name="glass_type_id" class="form-select">
                                <option value="">ทั้งหมด</option>
                                @foreach($glassTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">สีกระจก:</label>
                            <select name="glass_colour_id" class="form-select">
                                <option value="">ทั้งหมด</option>
                                @foreach($colour as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="accessory" style="display: none;">
                    <div class="box-control">
                        <div class="form-group">
                            <label class="form-label">อุปกรณ์เสริม:</label>
                            <select name="accessory_type_id" class="form-select">
                                <option value="">ทั้งหมด</option>
                                @foreach($accessorytype as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">สีอุปกรณ์เสริม:</label>
                            <select name="aluminium_surface_id" class="form-select">
                                <option value="">ทั้งหมด</option>
                                @foreach($aluminumSurfaces as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="tool" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">อุปกรณ์ช่าง:</label>
                        <select name="tool_type_id" class="form-select">
                            <option value="">ทั้งหมด</option>
                            @foreach($tooltype as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="consumable" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">วัสดุสิ้นเปลือง:</label>
                        <select name="consumable_type_id" class="form-select">
                            <option value="">ทั้งหมด</option>
                            @foreach($consumable as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-top: 12px; display:flex; gap:8px; align-items:center;">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fa-solid fa-magnifying-glass"></i> ค้นหา
                    </button>

                    <a href="{{ route('admin.materials.addstockpage') }}" class="btn btn-primary">ล้างการค้นหา</a>
                </div>
            </div>
        </form>
    </div>

    @include('components.successanderror')

    @if ($material->isEmpty())
    <div class="box" style="margin-top:20px; padding:20px; text-align:center; border:1px dashed #ccc;">
        <h4>ไม่พบวัสดุที่ค้นหา</h4>
        <p>ลองล้างตัวกรองหรือตรวจสอบเงื่อนไขการค้นหาอีกครั้ง</p>
        <a href="{{ route('admin.materials.addstockpage') }}" class="btn btn-primary" style="margin-top:8px;">
            แสดงทั้งหมด
        </a>
    </div>
    @else
    <div class="control-position">
        @foreach ($material as $mrl)
        @if ($mrl->material_type == 'อลูมิเนียม')

        <div class="boxcarde" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn-additem" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $mrl->id }} ">
                    <i class="fa-solid fa-plus" title="เพิ่มสต็อก"></i>
                </button>

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
                <button type="button" class="btn-additem" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $mrl->id }} ">
                    <i class="fa-solid fa-plus" title="เพิ่มสต็อก"></i>
                </button>
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
                <button type="button" class="btn-additem" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $mrl->id }} ">
                    <i class="fa-solid fa-plus" title="เพิ่มสต็อก"></i>
                </button>
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
                <button type="button" class="btn-additem" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $mrl->id }} ">
                    <i class="fa-solid fa-plus" title="เพิ่มสต็อก"></i>
                </button>
                @if ($mrl->price->sum('quantity') > 0 )
                <div class="boxstatus boxstatusadd">
                    {{ $mrl->price->sum('quantity') }}{{ $mrl->toolItem->unit->name}}
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
                <button type="button" class="btn-additem" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $mrl->id }} ">
                    <i class="fa-solid fa-plus" title="เพิ่มสต็อก"></i>
                </button>
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


        <div class="modal fade" id="addStockModal{{ $mrl->id }}" tabindex="-1" aria-labelledby="addStockLabel{{ $mrl->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="addStockLabel{{ $mrl->id }}">เพิ่มสต็อก: {{ $mrl->material_type }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                    </div>

                    <form action="{{ route('admin.materials.addstock') }}" method="POST">
                        @csrf

                        <input type="hidden" name="id" value="{{ $mrl->id }}">

                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">จำนวนที่ต้องการเพิ่ม</label>
                                <input type="number" name="quantity" class="form-input" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">ร้านตัวแทนจำหน่าย</label>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <select name="dealer_id" class="form-select" required>
                                        <option value="">กรุณาเลือก</option>
                                        @foreach($dealers as $dealer)
                                        <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                                        @endforeach
                                    </select>
                                    <a class="btn-secondary" href="{{ route('admin.materalstype.createFormdealer') }}"
                                        style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0px; transition: all 0.3s ease;">
                                        + เพิ่ม
                                    </a>
                                </div>

                            </div>

                            @if($mrl->material_type == 'อลูมิเนียม')
                            <div class="form-group">
                                <label class="form-label">ความยาว (เมตร)</label>
                                <input type="number" step="0.01" name="length_meter" class="form-input" required>
                            </div>
                            @endif

                            @if($mrl->material_type == 'กระจก')
                            <div class="form-group">
                                <label class="form-label">กว้าง (เมตร)</label>
                                <input type="number" step="0.01" name="width_meter" class="form-input" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">ยาว (เมตร)</label>
                                <input type="number" step="0.01" name="length_meter" class="form-input" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">ความหนา (มม.)</label>
                                <input type="number" step="0.1" name="thickness" class="form-input" required>
                            </div>
                            @endif




                            <div class="form-group">
                                <label class="form-label">ราคาต้นทุนต่อหน่วย</label>
                                <input type="number" step="0.01" name="price" class="form-input" required>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-delecte" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>









        @endforeach
    </div>
    @endif

</div>

<script>
    const searchtype = document.getElementById('searchtype');
    const aluminum = document.getElementById('aluminum');
    const glass = document.getElementById('glass');
    const accessory = document.getElementById('accessory');
    const consumable = document.getElementById('consumable');
    const tool = document.getElementById('tool')

    searchtype.addEventListener('change', function() {
        aluminum.style.display = 'none';
        glass.style.display = 'none';
        accessory.style.display = 'none';
        consumable.style.display = 'none';
        tool.style.display = 'none';

        const searchtype = this.value;

        if (searchtype == 'aluminum') {
            aluminum.style.display = 'block';
        } else if (searchtype == 'glass') {
            glass.style.display = 'block';
        } else if (searchtype == 'accessory') {
            accessory.style.display = 'block';
        } else if (searchtype == 'consumable') {
            consumable.style.display = 'block';
        } else if (searchtype == 'tool') {
            tool.style.display = 'block';
        }
    });
</script>

@endsection