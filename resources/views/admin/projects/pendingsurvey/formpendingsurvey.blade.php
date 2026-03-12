@extends('layouts.admin')

@section('content')

<div class="main-content">

    @include('components.successanderror')
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>เพิ่มข้อมูลงานใหม่</h3>
        <a href="{{ route('admin.projects.adminfulleventcalendarpage') }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    @include('components.progress-steps1')

    
    <div class="boxmaterial" style="margin-top: 20px;">
        บันทึกข้อมูลลูกค้าและนัดหมายสำรวจหน้างาน
    </div>
    <div class="box">
        <form action="{{ route('admin.projects.pendingsurvey') }}" method="post">
            @csrf
            <div class="box-control">

                <div class="form-group">
                    <label for="" class="form-label">ชื่องาน</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <select name="project_name_id" id="" class="form-select" required>
                            <option value="">เลือกชื่องาน</option>
                            @foreach ($projectname as $pn )
                            <option value="{{ $pn->id }}">{{ $pn->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.projects.formprojectname') }}" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 5px; transition: all 0.3s ease; border-radius:0;">
                            + เพิ่ม
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">ชื่อ-นามสกุล ลูกค้า</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        
                        <select name="customer_id" id="" class="form-select" required>
                            <option value="">เลือกชื่อลูกค้า</option>
                            @foreach ($customer as $cm)
                                <option value="{{ $cm->id }}">{{ ' คุณ '.$cm->first_name .'  '. $cm->last_name }}</option>
                            @endforeach
                        </select> 
                        
                        <a class="btn-secondary" href="{{ route('admin.projects.formnewcustomer') }}" style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 5px; transition: all 0.3s ease; border-radius:0;">
                            + เพิ่ม
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">วันและเวลานัดสำรวจ</label>
                    <input type="datetime-local" name="survey_date" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">เลือกช่างที่จะไปสำรวจ</label>
                    <select name="assigned_surveyor_id" id="" class="form-select" required>
                        @foreach ($technician as $tc)
                        <option value="">เลือกช่างที่จะไปสำรวจ</option>
                        <option value="{{ $tc->id }}">{{' ช่าง '.$tc->name }} {{ $tc->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ค่าแรงช่างที่จะไปสำรวจ</label>
                    <input type="number" name="labor_cost_surveying" class="form-input" min="0" step="0.01" required>
                </div>



                <div class="form-group">
                    <label for="" class="form-label">หมายเหตุหรือความต้องการเบื้องต้น (ถ้ามี)</label>
                    <textarea name="note" class="form-input" ></textarea>
                </div>




                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">บันทึกข้อมูล</button>
                </div>

            </div>
        </form>

    </div>

    

    


</div>

@endsection