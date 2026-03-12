@extends('layouts.admin')

@section('content')
    <div class="main-content">

        @if (session('error'))
            <div class="alert alert-danger">
                <div style="color: red;">{{ session('error') }}</div>
            </div>
        @endif
        
        <div class="box-create-material">
            แก้ไขชื่อตัวแทนจำหน่าย
        </div>
        <div class="box">
            <form action="{{ route('admin.materalstype.updatedealer', $dealer->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-control">
                    <div class="form-group">
                    <label class="form-label" for="name">ชื่อตัวแทนจำหน่าย</label>
                    <input type="text" class="form-input" id="name" name="name"
                        value="{{ old('name', $dealer->name) }}" placeholder="กรอกชื่อตัวแทนจำหน่าย" required>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save" style="margin-right: 5px;"></i>บันทึกการแก้ไข</button>
                    <a href="{{ route('admin.materalstype.createaluminiumType') }}" class="btn btn-secondary">ยกเลิก</a>
                </div>
                </div>


            </form>
        </div>
    </div>
@endsection
