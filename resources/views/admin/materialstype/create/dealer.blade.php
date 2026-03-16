@extends('layouts.admin')

@section('content')
    <div class="main-content">
        @if (session('success'))
            <div class="alert alert-success">
                <div style="color: green;">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <div style="color: red;">{{ session('error') }}</div>
            </div>
        @endif

        <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
            เพิ่มชื่อตัวแทนจำหน่าย
            <div>
                <a href="{{ route('materialstype.trash') }}" class="btn btn-danger">
                    <i class="fas fa-trash" style="margin-right: 5px;"></i>ถังขยะ
                </a>
                <a href="{{ route('admin.materials.addstockpage') }}" class="btn btn-primary">ย้อนกลับ</a>
            </div>
        </div>
        <div class="box">
            @php
                $materialTypeSource = request()->get('material_type');
            @endphp

            <form action="{{ route('admin.materalstype.createdealer') }}" method="post">
                @csrf

                <input type="hidden" name="material_type_source" value="{{ $materialTypeSource }}">

                <div class="box-control">
                    <div class="form-group">
                        <label class="form-label" for="name">ชื่อตัวแทนจำหน่าย</label>
                        <input id="name" name="name" class="form-input" type="text" placeholder="กรอกชื่อตัวแทนจำหน่าย">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save" style="margin-right: 5px;"></i>
                        บันทึกข้อมูล
                    </button>

                    <a href="{{ route('admin.materials.addstockpage') }}" class="btn btn-secondary">ยกเลิก</a>
                </div>
            </form>
        </div>

        <div style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
            <div style="background-color: white; padding: 20px; width: auto; ">
                ตัวแทนจำหน่ายทั้งหมด {{ $dealers->count() }} ประเภท
            </div>
            <div style="background-color: white; padding: 20px; width: min-content; ">
                <form action="{{ route('admin.materalstype.createdealer') }}" method="get">
                    <div class="box-control" style="display: flex; align-items: center; gap: 10px;">
                        <div class="form-group" style="margin: 0; flex: 1;">
                            <input type="text" class="form-input" name="searchName" value="{{ request('searchName') }}"
                                placeholder="ค้นหา" style="padding: 8px 12px; font-size: 14px;">
                        </div>

                        <button class="btn  btn-secondary" type="submit" style="padding: 8px 15px; font-size: 14px;">
                            <i class="fa fa-search"></i> ค้นหา
                        </button>

                        <a class="btn btn-primary" href="{{route('admin.materalstype.createdealer')}}"
                            style="padding: 8px 12px; font-size: 14px;">
                            <i class="fa fa-refresh"></i> ล้าง
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">ลำดับ</th>
                    <th style="width: auto;">ชื่อตัวแทนจำหน่าย</th>
                    <th style="width: 120px; text-align: center;">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dealers as $dealer)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $dealer->name }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.materalstype.editdealer', $dealer->id)}}"
                                class="btn-icon btn-edit" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.materalstype.deletedealer', $dealer->id) }}"
                                method="POST" style="display: inline;"
                                onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">ไม่พบข้อมูล</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection
