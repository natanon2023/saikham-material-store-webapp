@extends('layouts.admin')

@section('content')
<div class="main-content">

    @include('components.successanderror')


    <div class="boxmaterial" style="display: flex; justify-content :space-between">
        <h3>เพิ่มชื่อรายการค่าใช้จ่าย</h3>
        <a href="{{ $backUrl }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div class="box">
        <form action="{{ route('admin.projects.createexpense') }}" method="post">
            @csrf
            @isset($project)
                <input type="hidden" name="project_id" value="{{ $project->id }}">
            @endisset
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ชื่อรายการค่าใช้จ่าย</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">รายละเอียด (ถ้ามี)</label>
                    <input type="text" name="description" class="form-input">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">เพิ่มข้อมูล</button>
                </div>
            </div>
        </form>
    </div>

    
    
    <h3 style="margin-bottom: 20px; margin-top: 30px;">ข้อมูลรายการค่าใช้จ่ายทั้งหมด</h3>
    <table width="100%" border="1" style="border-collapse: collapse; margin-top: 20px;">
        <tr align="center" style="background-color: #f4f4f4;">
            <th>ลำดับ</th>
            <th>ชื่อรายการ / รายละเอียด</th>
            <th>แก้ไข</th>
            <th>ลบ</th>
        </tr>
        
        @foreach ($expens as $ex)
        <tr align="center" style ="{{ $ex->trashed() ? 'opacity: 0.5; background-color: #f9f9f9;' : '' }}">
            <td>{{ $loop->iteration }}</td>
            
            <td width="30%">
                {{ $ex->name }}<br>
                <span style="font-size: 0.9em; color: #555;">{{ $ex->description ?? '-' }}</span>
            </td>

            <td width="45%">
                @if(!$ex->trashed())
                    <form action="{{ route('admin.projects.updateexpense', $ex->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @isset($project)
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                        @endisset
                        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px; justify-content: center;">
                            <input type="text" name="name" value="{{ $ex->name }}" class="form-input" placeholder="ชื่อรายการ" required> 
                            <input type="text" name="description" value="{{ $ex->description }}" class="form-input" placeholder="รายละเอียด">
                            <button type="submit" class="btn btn-warning">แก้ไข</button>
                        </div>
                    </form>
                @else
                    <span style="color: gray;">(ข้อมูลถูกระงับ)</span>
                @endif
            </td>

            <td width="15%">
                @if($ex->trashed())
                    <a href="{{ route('admin.projects.restoreexpense', $ex->id) }}" 
                       class="btn btn-secondary">กู้คืนข้อมูล</a>
                @else
                    <a href="{{ route('admin.projects.deleteexpense', $ex->id) }}" 
                       class="btn btn-danger"
                       onclick="return confirm('ยืนยันการลบ?')">ลบ</a>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection