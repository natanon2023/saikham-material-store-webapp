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

        <div class="box-create-material" style="display: flex; justify-content: space-between; align-items: center;">
            เพิ่มชื่อประเภทกระจก
            <div>
                <a href="{{ route('materialstype.trash') }}" class="btn btn-secondary">
                    <i class="fas fa-trash" style="margin-right: 5px;"></i>ถังขยะ
                </a> 
                <a href="{{ route('admin.materials.formglass') }}"
                    class="btn btn-secondary">ย้อนกลับ
                </a>
            </div>
        </div>
        <div class="box">
            <form action="{{ route('admin.materalstype.createglassType') }}" method="post">
                @csrf
                <div class="box-control">
                    <div class="form-group">
                        <label class="form-label" for="">ชื่อประเภทกระจก</label>
                        <input id="name"  name="name" class="form-input" type="text" placeholder="กรอกชื่อประเภทกระจก">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save" style="margin-right: 5px;"></i>
                        บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>

        <div style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
            <div style="background-color: white; padding: 20px; width: auto; border: 1px solid #79c4f6;">
                ประเภทกระจกทั้งหมด {{ $glassTypes->count() }} ประเภท
            </div>
            <div style="background-color: white; padding: 20px; width: min-content; border: 1px solid #79c4f6;">
                <form action="{{ route('admin.materalstype.createFormglassType') }}" method="get">
                    <div class="box-control" style="display: flex; align-items: center; gap: 10px;">
                        <div class="form-group" style="margin: 0; flex: 1;">
                            <input type="text" class="form-input" name="searchName" value="{{ request('searchName') }}"
                                placeholder="ค้นหา" style="padding: 8px 12px; font-size: 14px;">
                        </div>

                        <button class="btn btn-primary" type="submit" style="padding: 8px 15px; font-size: 14px;">
                            <i class="fa fa-search"></i> ค้นหา
                        </button>

                        <a class="btn btn-secondary" href="{{route('admin.materalstype.createFormglassType')}}"
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
                    <th style="width: auto;">ชื่อประเภทกระจก</th>
                    <th style="width: 120px; text-align: center;">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($glassTypes as $glassType)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $glassType->name }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.materalstype.editGlassType', $glassType->id)}}"
                                class="btn-icon btn-edit" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.materalstype.deleteGlassType', $glassType->id) }}"
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
