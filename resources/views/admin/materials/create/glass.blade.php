@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    @include('components.selectcreateForm2')

    <div class="boxmaterial boxmaterial-titel">
        <h4>เพิ่มข้อมูลกระจก</h4>
        <div class="btn btn-secondary">
            <a href="{{ route('admin.materials.showselecttypematerials') }}">ย้อนกลับ</a>
        </div>
    </div>

    <div class="box">
        <form action="{{ route('admin.materials.createglass') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ประเภทย่อยวัสดุ</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <select name="glass_type_id" class="form-select">
                            <option value="">เลือกประเภทย่อย</option>
                            @foreach ($glasstype as $gt)
                            <option value="{{ $gt->id }}">{{ $gt->name }}</option>
                            @endforeach
                        </select>
                        <a class="btn-secondary" href="{{ route('admin.materalstype.createFormglassType') }}"
                                style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0px; transition: all 0.3s ease;">
                                + เพิ่ม
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">เลือกสีวัสดุ</label>
                    <select name="colouritem_id" class="form-select">
                        <option value="">เลือกสี</option>
                        @foreach ($colour as $cl)
                        <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">รูปภาพวัสดุ</label>
                    <input type="file" name="image_glass_item" class="form-input" accept="image/*" capture="camera">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </div>
            </div>
        </form>

    </div>



</div>
@endsection