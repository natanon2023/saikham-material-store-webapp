@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>เพิ่มรูปภาพงาน</h3>
        <a href="{{ route('admin.projects.formsurveying',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>


    <div class="box">
        <form action="{{ route('admin.projects.createprojectimage') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <div class="box-control">
                <div class="form-group">
                    <label for="" class="form-label">ประเภทรูปภาพ</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                       <select name="image_type" id="" class="form-select" required>
                            <option value="">เลือกประเภทรูปภาพ</option>
                            @foreach ($imgtypename as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                            
                        </select>
                        <a href="{{ route('admin.projects.formcrateimgtype') }}" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 5px; transition: all 0.3s ease; border-radius:0;">
                            + เพิ่ม
                        </a>  
                    </div>
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
                    <button type="submit" class="btn btn-secondary">บันทึกรูปภาพ</button>
                </div>

            </div>
        </form>
    </div>




</div>

@endsection