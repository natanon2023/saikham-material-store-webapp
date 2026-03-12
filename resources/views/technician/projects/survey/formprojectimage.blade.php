@extends('layouts.technician')

@section('content')
<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>เพิ่มรูปภาพงาน</h3>
        <button type="button" onclick="history.back()" class="btn btn-secondary">ย้อนกลับ</button>
    </div>


    <div class="box">
        <form action="{{ route('technician.projects.createprojectimage') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <div class="box-control">
                <div class="form-group">
                    <label for="" class="form-label">ประเภทรูปภาพ</label>
                    <select name="image_type" id="" class="form-select" required>
                        <option value="">เลือกประเภทรูปภาพ</option>
                        <option value="ด้านหน้า">ด้านหน้า</option>
                        <option value="ด้านข้างซ้าย">ด้านข้างซ้าย</option>
                        <option value="ด้านข้างขวา">ด้านข้างขวา</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="" class="form-label">ไฟล์ภาพ</label>
                    <input type="file" class="form-input" name="image_path" accept="image/*" capture="camera" required>
                </div>

                <div class="form-group">
                    <label for="" class="form-label">รายละเอียดภาพ (ถ้ามี)</label>
                    <textarea name="description" id="" class="form-input"></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">บันทึกรูปภาพ</button>
                </div>

            </div>
        </form>
    </div>




</div>

@endsection