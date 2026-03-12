@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    @include('components.selectcreateForm2')

    <div class="boxmaterial boxmaterial-titel">
        <h4>เพิ่มข้อมูลวัสดุสิ้นเปลือง</h4>
        <div class="btn btn-secondary">
            <a href="{{ route('admin.materials.showselecttypematerials') }}">ย้อนกลับ</a>
        </div>
    </div>
    <div class="box">
        <form action="{{ route('admin.materials.createconsumable') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ชื่อวัสดุสิ้นเปลือง</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <select name="consumable_type_id" id="" class="form-select">
                            <option value="">เลือกชื่อวัสดุสิ้นเปลือง</option>
                            @foreach ($consumabletype as $ct )
                                <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                            @endforeach
                        </select>
                        <a class="btn-secondary" href="{{ route('admin.materalstype.createFormconsumableType') }}"
                                style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0px; transition: all 0.3s ease;">
                                + เพิ่ม
                        </a>
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="form-label">ภาพวัสดุสิ้นเปลือง</label>
                    <input type="file" name="image_consumable_item" class="form-input" accept="image/*" capture="camera">
                </div>



                <div class="form-group">
                    <label class="form-label">หน่วยนับ</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <select name="unit_id" class="form-select">
                            <option value="">เลือกหน่วย</option>
                            @foreach ($unit as $ut)
                            <option value="{{ $ut->id }}">{{ $ut->name }}</option>
                            @endforeach
                        </select>
                        <a class="btn-secondary" href="{{ route('admin.materalstype.createFormunit') }}"
                            style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0
                            px;">
                            + เพิ่ม
                        </a>
                    </div>
                </div>

                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </div>

            </div>
        </form>

    </div>


</div>

@endsection