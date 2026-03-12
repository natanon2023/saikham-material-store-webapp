@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="boxmaterial control-section">
        <h5>เพิ่มชื่อผลิตภัณฑ์ใหม่</h5>
        <a onclick="history.back()" class="btn btn-secondary">ย้อนกลับ</a>
    </div>
    <div class="box">
        <form action="{{ route('admin.projects.createproductsetname') }}" method="post">
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <label for="" class="form-label">ชื่อผลิตภัณฑ์ใหม่</label>
                    <input type="text" class="form-input" name="name" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </div>
        </form>

    </div>


</div>

@endsection