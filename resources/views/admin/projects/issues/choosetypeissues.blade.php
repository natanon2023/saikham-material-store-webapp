@extends('layouts.admin')

@section('content')
    <div class="main-content">
        <div style="background-color: #ffffff; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h4 style="margin-bottom: 20px;">ขั้นตอนการบันทึกข้อมูล</h4>
            <a href="{{ route('admin.projects.index',$projects->id) }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
        <div style="display: flex; flex-direction: row; text-align: center;">
            <div style="flex: auto; background-color: #334E68; padding: 10px; color:#ffffff ;" >
                ขั้นตอนที่ 1 เลือกประเภทปัญหาที่ต้องการแจ้ง
            </div>
            <div style="flex: auto; padding: 10px; border: 1px solid #334E68; text-align: center;">
                ขั้นตอนที่ 2 กรอกข้อมูลและกดบันทึก
            </div>
        </div>
    </div>
        <div class="boxmaterial" >
            <h4>เลือกประเภทปัญหา</h4>
        </div>
        <div class="box-selecttype">
            <a href="{{ route('admin.projects.issues.create',$projects->id) }}" class="a-selecttype" >1. ปัญหาวัสดุ</a>
        </div>
        <div class="box-selecttype">
            <a href="{{ route('admin.projects.generalissues',$projects->id) }}" class="a-selecttype">2. ปัญหาทั่วไป</a>
        </div>
    </div>
@endsection