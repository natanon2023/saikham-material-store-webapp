@extends('layouts.admin')

@section('content')
    <div class="main-content">
        @if (session('error'))
            <div class="alert alert-danger">
                <div style="color: red;">{{ session('error') }}</div>
            </div>
        @endif
        
        <div class="box-create-material">
            แก้ไขประเภทวัสดุสิ้นเปลือง
        </div>

       

        <div class="box">
            <form action="{{ route('admin.materalstype.updateconsumableType', $consumableTypes->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-control">
                    <div class="form-group">
                        <label class="form-label" for="name">ชื่อประเภทวัสดุสิ้นเปลือง</label>
                        <input type="text" class="form-input" id="name" name="name"
                            value="{{ old('name', $consumableTypes->name) }}" placeholder="กรอกชื่อประเภทวัสดุสิ้นเปลือง"
                            required>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"
                                style="margin-right: 5px;"></i>บันทึกการแก้ไข</button>
                        <a href="{{ route('admin.materalstype.createFormconsumableType') }}" class="btn btn-secondary">ยกเลิก</a>
                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection
