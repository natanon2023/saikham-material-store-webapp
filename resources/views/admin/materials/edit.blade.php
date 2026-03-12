@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>แก้ไข{{ $material->material_type }}</h3>
        <a href="{{ route('admin.materials.showdetailmaterial',$material->id) }}" class="btn btn-secondary">ย้อนกลับ</a>
    </div>


    <div class="box" style="margin-top: 10px;">
        <form action="{{ route('admin.materials.updatematerial',$material->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @if ($material->material_type == 'อลูมิเนียม')
            <div class="form-group">
                <label class="form-label">ภาพปัจจุบัน</label>
                <img src="data:image/jpeg;base64,{{ base64_encode($material->aluminiumItem->image_aluminium_item) }}" alt="ภาพอลูมิเนียม" width="200">
            </div>
            <div class="box-control">

                <div class="form-group">
                    <label class="form-label">อัปโหลดภาพใหม่ (ถ้ามี)</label>
                    <input type="file" name="image_aluminium_item" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">ประเภทย่อย</label>
                    <select name="aluminium_profile_types_id" class="form-select">
                        @foreach ($aluminiumType as $at)
                        <option value="{{ $at->id }}" {{ $material->aluminiumItem->aluminium_profile_types_id == $at->id ? 'selected' : '' }}>
                            {{ $at->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">สี</label>
                    <select name="aluminum_surface_finish_id" class="form-select">
                        @foreach($surface as $s)
                        <option value="{{ $s->id }}" {{ $material->aluminiumItem->aluminum_surface_finish_id == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </div>

            @endif

            @if($material->material_type == 'กระจก')
            <div class="form-group">
                <label class="form-label">ภาพปัจจุบัน</label><br>
                <img src="data:image/jpeg;base64,{{ base64_encode($material->glassItem->image_glass_item) }}"
                    alt="ภาพกระจก" width="200">
            </div>


            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">อัปโหลดภาพใหม่ (ถ้ามี)</label>
                    <input type="file" name="image_glass_item" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">ประเภทกระจก</label>
                    <select name="glass_type_id" class="form-select">
                        @foreach($glassType as $gt)
                        <option value="{{ $gt->id }}"
                            {{ $material->glassItem->glass_type_id == $gt->id ? 'selected' : '' }}>
                            {{ $gt->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">สี</label>
                    <select name="colouritem_id" class="form-select">
                        @foreach($colour as $c)
                        <option value="{{ $c->id }}"
                            {{ $material->glassItem->colouritem_id == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </div>
            @endif

            @if ($material->material_type == 'อุปกรณ์เสริม')
            <div class="form-group">
                <label class="form-label">ภาพปัจจุบัน</label><br>
                <img src="data:image/jpeg;base64,{{ base64_encode($material->accessoryItem->image_accessory_item) }}"
                    alt="ภาพอุปกรณ์เสริม" width="200">
            </div>


            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">อัปโหลดภาพใหม่ (ถ้ามี)</label>
                    <input type="file" name="image_accessory_item" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">ประเภทอุปกรณ์เสริม</label>
                    <select name="accessory_type_id" class="form-select">
                        @foreach($accessoryType as $at)
                        <option value="{{ $at->id }}"
                            {{ $material->accessoryItem->accessory_type_id == $at->id ? 'selected' : '' }}>
                            {{ $at->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">สี</label>
                    <select name="aluminum_surface_finish_id" class="form-select">
                        @foreach($surface as $s)
                        <option value="{{ $s->id }}"
                            {{ $material->accessoryItem->aluminum_surface_finish_id == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">หน่วย</label>
                    <select name="unit_id" class="form-select">
                        @foreach($unit as $u)
                        <option value="{{ $u->id }}"
                            {{ $material->accessoryItem->unit_id == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @endif
            @if ($material->material_type == 'เครื่องมือช่าง')
            <div class="form-group">
                <label class="form-label">ภาพปัจจุบัน</label><br>
                <img src="data:image/jpeg;base64,{{ base64_encode($material->toolItem->image_tool_item) }}"
                    alt="ภาพเครื่องมือ" width="200">
            </div>
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">อัปโหลดภาพใหม่ (ถ้ามี)</label>
                    <input type="file" name="image_tool_item" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">ประเภทเครื่องมือ</label>
                    <select name="tool_type_id" class="form-select">
                        @foreach($toolType as $tt)
                        <option value="{{ $tt->id }}"
                            {{ $material->toolItem->tool_type_id == $tt->id ? 'selected' : '' }}>
                            {{ $tt->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">รายละเอียดการใช้งาน</label>
                    <input type="textarea" name="description" class="form-input"
                        value="{{ $material->toolItem->description }}">
                </div>

                <div class="form-group">
                    <label class="form-label">หน่วย</label>
                    <select name="unit_id" class="form-select">
                        @foreach($unit as $u)
                        <option value="{{ $u->id }}"
                            {{ $material->toolItem->unit_id == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </div>

            @endif
            @if ($material->material_type == 'วัสดุสิ้นเปลือง')
            <div class="form-group">
                <label class="form-label">ภาพปัจจุบัน</label><br>
                <img src="data:image/jpeg;base64,{{ base64_encode($material->consumableItem->image_consumable_item) }}"
                    alt="ภาพวัสดุสิ้นเปลือง" width="200">
            </div>

            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">อัปโหลดภาพใหม่ (ถ้ามี)</label>
                    <input type="file" name="image_consumable_item" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">ประเภทวัสดุสิ้นเปลือง</label>
                    <select name="consumable_type_id" class="form-input">
                        @foreach($consumableType as $ct)
                        <option value="{{ $ct->id }}"
                            {{ $material->consumableItem->consumable_type_id == $ct->id ? 'selected' : '' }}>
                            {{ $ct->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">หน่วย</label>
                    <select name="unit_id" class="form-input">
                        @foreach($unit as $u)
                        <option value="{{ $u->id }}"
                            {{ $material->consumableItem->unit_id == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </div>

            @endif




            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary">แก้ไขข้อมูล</button>
            </div>

        </form>

    </div>







</div>
@endsection