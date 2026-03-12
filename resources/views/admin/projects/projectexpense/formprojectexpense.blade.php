@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')
    <div class="boxmaterial" style="display: flex; justify-content: space-between;">
        <h3>เพิ่มรายการค่าใช้จ่ายในงาน</h3>
        <button type="button" onclick="history.back()" class="btn btn-primary">ย้อนกลับ</button>
    </div>
    <div class="box">
        <form action="{{ route('admin.projects.createprojectexpense') }}" method="post">
            @csrf
            <input type="hidden" value="{{ $project->id }}" name="project_id">
            <div class="box-control">
                <div class="form-group">
                    <label for="" class="form-label">รายการค่าใช้จ่าย</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <select name="expense_type_id" id="" class="form-select" required>
                            <option value="">เลือกรายการค่าใช้จ่าย</option>
                            @foreach ($expense as $ep )
                                <option value="{{ $ep->id }}">{{ $ep->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.projects.formexpense',$project->id) }}" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0px; transition: all 0.3s ease;">
                            + เพิ่ม
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="form-label">ค่าใช้จ่าย</label>
                    <input type="number" name="amount" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="" class="form-label">วันที่ใช้จ่าย</label>
                    <input type="date" name="expense_date" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="" class="form-label">รายละเอียด ( ถ้ามี )</label>
                    <input type="text" name="description" class="form-input">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">เพิ่มรายการค่าใช้จ่าย</button>
                </div>
            </div>
            

        </form>
    </div>

</div>

@endsection