@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    @include('components.selectcreateForm2')

    <div class="boxmaterial boxmaterial-titel">
        <h4>เพิ่มข้อมูลเครื่องมือช่าง</h4>
        <div class="btn btn-secondary">
            <a href="{{ route('admin.materials.showselecttypematerials') }}">ย้อนกลับ</a>
        </div>
    </div>
    <div class="box">
        <form action="{{ route('admin.materials.createtool') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ชื่อเครื่องมือช่าง</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <select name="tool_type_id" id="" class="form-select" required>
                            <option value="">เลือกชื่อเครื่องมือช่าง</option>
                            @foreach ($tooltype as $tt )
                                <option value="{{ $tt->id }}">{{ $tt->name }}</option>
                            @endforeach
                        </select>
                        <a class="btn-secondary" href="{{ route('admin.materalstype.createFormtoolType') }}"
                                style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0px; transition: all 0.3s ease;">
                                + เพิ่ม
                        </a>
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="form-label">รายละเอียดการใช้งาน</label>
                    <input type="textarea"  name="description" class="form-input" required>
                </div>


                <div class="form-group">
                    <label for="" class="form-label">ภาพเครื่องมือช่าง</label>
                    <input type="file" name="image_tool_item" class="form-input" accept="image/*" capture="camera" required>
                </div>



                <div class="form-group">
                    <label class="form-label">หน่วยนับ</label>
                    <div style="display: flex; align-items: center; gap: 8px;" >
                        <select name="unit_id" class="form-select" required>
                            <option value="">เลือกหน่วย</option>
                            @foreach ($unit as $ut)
                            <option value="{{ $ut->id }}">{{ $ut->name }}</option>
                            @endforeach
                        </select>
                        <a class="btn-secondary" href="{{ route('admin.materalstype.createFormunit') }}"
                            style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0px;">
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