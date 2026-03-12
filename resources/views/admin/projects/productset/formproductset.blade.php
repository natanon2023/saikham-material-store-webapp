@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between;">
        <h4>เพิ่มผลิตภัณฑ์ใหม่</h4>
        <a onclick="history.back()" class="btn btn-secondary">ย้อนกลับ</a>
    </div>

    <div class="box">
        <form action="{{ route('admin.projects.createproductset') }}" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <label for="" class="form-label">ชื่อผลิตภัณฑ์ใหม่</label>
                    <div class="createname">
                        <select name="product_set_name_id" id="" class="form-select" required>
                            <option value="">เลือกชื่อผลิตภัณฑ์ใหม่</option>
                            @foreach ($productsetname as $pdsn)
                            <option value="{{ $pdsn->id }}">{{ $pdsn->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.projects.formproductsetname') }}" class="btn-secondary btn-secondary2">
                            + เพิ่ม
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">รูปภาพผลิตภัณฑ์</label>
                    <input type="file" name="product_image" class="form-input" accept="image/*" capture="camera" required>
                </div>
                <div class="form-group">
                    <label class="form-label">สีอลูมิเนียม</label>
                    <select name="aluminum_surface_finish_id" id="" class="form-select" required>
                        <option value="">เลือกสีอลูมิเนียม</option>
                        @foreach ($aluminumsurfacefinish as $als)
                        <option value="{{ $als->id }}">{{ $als->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">ประเภทกระจก</label>
                    <select name="glasstype_id" id="" class="form-select" required>
                        <option value="">เลือกประเภทกระจก</option>
                        @foreach ($glasstype as $gt)
                        <option value="{{ $gt->id }}">{{ $gt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">สีกระจก</label>
                    <select name="glass_colouritem_id" id="" class="form-select" required>
                        <option value="">เลือกสีกระจก</option>
                        @foreach ($glasscolouritem as $gct)
                        <option value="{{ $gct->id }}">{{ $gct->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="" class="form-label">รายละเอียด</label>
                    <textarea name="detail" id="" required class="form-input" ></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </div>
            </div>

        </form>

    </div>

</div>

@endsection