@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <h3>รายงานปัญหาโครงการ</h3>
        <a href="{{ url()->previous() }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <form action="{{ route('admin.projects.issues.store', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="boxmaterial">
            <div class="box-control">
                
                <div class="form-group">
                    <label class="form-label">หมวดหมู่ปัญหา</label>
                    <select name="category" class="form-input" required>
                        <option value="">เลือกหมวดหมู่</option>
                        <option value="material_damage">วัสดุชำรุดหรือเสียหาย </option>
                        <option value="spec_error">ขนาดหรือสเปคผิดพลาด </option>
                        <option value="site_issue">ปัญหาหน้างานก่อสร้าง </option>
                        <option value="installation_defect">ปัญหาการติดตั้ง</option>
                        <option value="customer_issue">ปัญหาจากลูกค้า</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">หัวข้อปัญหาแบบย่อ </label>
                    <input type="text" name="title" class="form-input" placeholder="เช่น กระจกบานเลื่อนห้องนอนแตก 1 บาน" required>
                </div>

                

                <div class="form-group">
                    <label class="form-label">รูปภาพ</label>
                    <input type="file" name="images[]" class="form-input" accept="image/*" multiple required>
                </div>

                
                
                <div class="form-group">
                    <label class="form-label">รายละเอียดเพิ่มเติม</label>
                    <textarea name="description" class="form-input" rows="4" placeholder="ระบุรายละเอียดเพิ่มเติม เพื่อให้ทีมประเมินการแก้ไขได้ถูกต้อง..."></textarea>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-secondary">บันทึกการรายงานปัญหา</button>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection