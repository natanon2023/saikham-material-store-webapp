@extends('layouts.admin')

@section('content')
<div class="main-content">

    <div class="boxmaterial" style="display: flex; justify-content: space-between;">
        <h3>แก้ไขรายการค่าใช้จ่าย</h3>
        <a onclick="history.back()" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div class="box">
        <form action="{{ route('admin.projects.editdetialexpense.update', $projectexpense->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="project_id" value="{{ $projectexpense->project_id }}">

            <div class="box-control">

              
                <div class="form-group">
                    <label for="expense_type_id" class="form-label">รายการค่าใช้จ่าย</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <select name="expense_type_id" id="expense_type_id" class="form-select" required>
                            <option value="">เลือกรายการค่าใช้จ่าย</option>
                            @foreach ($expense as $ep)
                                <option value="{{ $ep->id }}" 
                                    {{ $projectexpense->expense_type_id == $ep->id ? 'selected' : '' }}>
                                    {{ $ep->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

               
                <div class="form-group">
                    <label for="amount" class="form-label">ค่าใช้จ่าย (บาท)</label>
                    <input type="number" name="amount" id="amount" class="form-input" step="0.01"
                        value="{{ old('amount', $projectexpense->amount) }}" required>
                </div>

             
                <div class="form-group">
                    <label for="expense_date" class="form-label">วันที่ใช้จ่าย</label>
                    <input type="date" name="expense_date" id="expense_date" class="form-input"
                        value="{{ old('expense_date', $projectexpense->expense_date) }}" required>
                </div>

               
                <div class="form-group">
                    <label for="description" class="form-label">รายละเอียด (ถ้ามี)</label>
                    <input type="text" name="description" id="description" class="form-input"
                        value="{{ old('description', $projectexpense->description) }}">
                </div>

               
                <div class="form-group" >
                    <button type="submit" class="btn btn-secondary">
                        แก้ไขรายการค่าใช้จ่าย
                    </button>
                </div>

            </div>
        </form>
    </div>

</div>
@endsection
