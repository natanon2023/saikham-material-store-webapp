@extends('layouts.technician')

@section('content')
<div class="main-content">

    <div class="boxmaterial" style="display: flex; justify-content: space-between;">
        <h3>จัดการประเภทรูปภาพ</h3>
        <a href="{{ route('technician.projects.formprojectimage',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div style=" margin-top: 20px;">
        @include('components.successanderror')
    </div>

    <div class="box">
        <form action="{{ route('technician.projects.crateimgtype') }}" method="post">
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <label for="" class="form-label">ประเภทรูปภาพ</label>
                    <input type="text" class="form-input" name="name" required>
                </div>

                <button type="submit" class="btn btn-secondary">บันทึก</button>
            </div>
        </form>
    </div>

    <h3 style="margin-bottom: 20px; margin-top: 30px;">ข้อมูลประเภทรูปภาพทั้งหมด</h3>
    <table width="100%" border="1" style="border-collapse: collapse; margin-top: 20px;">
        <tr align="center" style="background-color: #f4f4f4; font-weight: bold;">
            <td style="padding: 10px;">ลำดับ</td>
            <td>ตำแหน่งที่จะติดตั้ง</td>
            <td>แก้ไข</td>
            <td>จัดการ</td>
        </tr>
        
        @foreach ($imgtype as $type)
        <tr align="center" style ="{{ $type->trashed() ? 'opacity: 0.5; background-color: #f9f9f9;' : '' }}">
            <td style="padding: 10px;">{{ $loop->iteration }}</td>
            
            <td width="30%">
                {{ $type->name }}
            </td>

            <td width="40%">
                @if(!$type->trashed())
                    <form action="{{ route('technician.projects.updateimgtype', $type->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group" style="display: flex; flex-direction: row; gap: 5px; justify-content: center; margin: 0;">
                            <input type="text" name="name" value="{{ $type->name }}" class="form-input" required>
                            <button type="submit" class="btn btn-warning">แก้ไข</button>
                        </div>
                    </form>
                @else
                    <span style="color: gray;">(ข้อมูลถูกระงับ)</span>
                @endif
            </td>

            <td width="20%">
                @if($type->trashed())
                    <form action="{{ route('technician.projects.restoreimgtype', $type->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary">กู้คืนข้อมูล</button>
                    </form>
                @else
                    <form action="{{ route('technician.projects.deleteimgtype', $type->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="background-color: #dc3545; color: white;">ลบ</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    
</div>
@endsection