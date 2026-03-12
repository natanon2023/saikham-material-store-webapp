@extends('layouts.admin')

@section('content')
    <div class="main-content">
        
        @if (session('error'))
            <div class="alert alert-danger">
                <div style="color: red;">{{ session('error') }}</div>
            </div>
        @endif
        
        <div class="box-create-material">
            แก้ไขประเภทเครื่องมือช่าง
        </div>

        

        <div class="box">
            <form action="{{ route('admin.materalstype.updateToolType', $toolType->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-control">
                    <div class="form-group">
                        <label class="form-label" for="name">ชื่อประเภทเครื่องมือช่าง</label>
                        <input type="text" class="form-input" id="name" name="name"
                            value="{{ old('name', $toolType->name) }}" placeholder="กรอกชื่อประเภทเครื่องมือช่าง" required>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"
                                style="margin-right: 5px;"></i>บันทึกการแก้ไข</button>
                        <a href="{{ route('admin.materalstype.index') }}" class="btn btn-secondary">ยกเลิก</a>
                    </div>

                </div>


            </form>
        </div>
    </div>
@endsection
