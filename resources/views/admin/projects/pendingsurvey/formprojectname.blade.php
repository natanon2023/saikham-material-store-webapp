@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content :space-between">
        <h3>เพิ่มชื่องาน</h3>
        <a href="javascript:history.back()" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div class="box">
        <form action="{{ route('admin.projects.createprojectname') }}" method="post">
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ชื่องาน</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">เพิ่มชื่องาน</button>
                </div>
            </div>
        </form>
    </div>
    <h3 style="margin-bottom: 20px; margin-top: 30px;">ข้อมูลชื่องานทั้งหมด</h3>
    <table width="100%" border="1" style="border-collapse: collapse; margin-top: 20px;">
        <tr align="center" style="background-color: #f4f4f4;">
            <td>ลำดับ</td>
            <td>ชื่องาน</td>
            <td>แก้ไข</td>
            <td>ลบ</td>
        </tr>
        
        @foreach ($projectnameall as $projectname)
        <tr align="center" style ="{{ $projectname->trashed() ? 'opacity: 0.5; background-color: #f9f9f9;' : '' }}">
            <td>{{ $loop->iteration }}</td>
            
            <td width="30%">
                    {{ $projectname->name }}
            </td>

            <td width="40%">
                @if(!$projectname->trashed())
                    <form action="{{ route('admin.projects.admupdateprojectname', $projectname->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group" style="display: flex; flex-direction: row; gap: 5px; justify-content: center;">
                            <input type="text" name="name" value="{{ $projectname->name }}" class="form-input" required>
                            <button type="submit" class="btn btn-warning">แก้ไข</button>
                        </div>
                    </form>
                @else
                    <span style="color: gray;">(ข้อมูลถูกระงับ)</span>
                @endif
            </td>

            <td width="20%">
                @if($projectname->trashed())
                    <a href="{{ route('admin.projects.restoreprojectname', $projectname->id) }}" 
                       class="btn btn-secondary">กู้คืนข้อมูล</a>
                @else
                    <a href="{{ route('admin.projects.deleteprojectname', $projectname->id) }}" 
                       class="btn btn-delecte"
                       onclick="return confirm('ยืนยันการลบ?')">ลบ</a>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection