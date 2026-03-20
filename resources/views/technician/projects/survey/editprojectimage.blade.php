@extends('layouts.technician')

@section('content')
<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>แก้ไขรูปภาพงาน</h3>
        <button type="button" onclick="history.back()" class="btn btn-primary">ย้อนกลับ</button>
    </div>

    <div class="box">
        <form action="{{ route('technician.projects.updateprojectimage', $projectImage->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
                
                <div  style=" margin-bottom: 20px; text-align: center;">
                    @if($projectImage->image_path)
                        <img src="data:image/jpeg;base64,{{ base64_encode($projectImage->image_path) }}" 
                             alt="Current Image" 
                             style="width: 100%; max-width: 400px; height: 300px; object-fit: cover;  border: 1px solid #ddd;">
                    @else
                        <div style="padding: 20px; background: #eee;  display: inline-block; width: 100%; max-width: 400px; text-align: center;">ไม่มีรูปภาพ</div>
                    @endif
                </div>
                
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
                    <label for="" class="form-label">อัปโหลดไฟล์ภาพใหม่ <span style="color: red; font-size: 0.8em;">(เลือกเฉพาะเมื่อต้องการเปลี่ยนรูปใหม่)</span></label>
                    <input type="file" class="form-input" name="image_path" accept="image/*" capture="camera">
                </div>

                <div class="form-group">
                    <label for="" class="form-label">รายละเอียดภาพ (ถ้ามี)</label>
                    <textarea name="description" id="" class="form-input">{{ $projectImage->description }}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">บันทึกการแก้ไข</button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection