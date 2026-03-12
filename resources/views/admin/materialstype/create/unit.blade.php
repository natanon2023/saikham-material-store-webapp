@extends('layouts.admin')

@section('content')
    <div class="main-content">
        @include('components.successanderror')

        <div class="box-create-material" style="display: flex; justify-content: space-between; align-items: center;">
            เพิ่มหน่วย
            <div>
                <a href="{{ route('materialstype.trash') }}" class="btn btn-secondary">
                    <i class="fas fa-trash" style="margin-right: 5px;"></i>ถังขยะ
                </a> 
            </div>
        </div>
        <div class="box">
            

            <form action="{{ route('admin.materalstype.createunit') }}" method="post">
                @csrf
                <div class="box-control">
                    <div class="form-group">
                        <label class="form-label" for="name">ชื่อหน่วย</label>
                        <input id="name" name="name" class="form-input" type="text" placeholder="กรอกหน่วยที่ต้องการเพิ่ม">
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
                หน่วยอุปกรณ์/วัสดุทั้งหมด {{ $units->count() }} ประเภท
            </div>
            <div style="background-color: white; padding: 20px; width: min-content; border: 1px solid #79c4f6;">
                <form action="{{ route('admin.materalstype.createFormunit') }}" method="get">
                    <div class="box-control" style="display: flex; align-items: center; gap: 10px;">
                        <div class="form-group" style="margin: 0; flex: 1;">
                            <input type="text" class="form-input" name="searchName" value="{{ request('searchName') }}"
                                placeholder="ค้นหา" style="padding: 8px 12px; font-size: 14px;">
                        </div>

                        <button class="btn btn-primary" type="submit" style="padding: 8px 15px; font-size: 14px;">
                            <i class="fa fa-search"></i> ค้นหา
                        </button>

                        <a class="btn btn-secondary" href="{{route('admin.materalstype.createFormunit')}}"
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
                    <th style="width: auto;">ชื่อหน่วยอุปกรณ์/วัสดุทั้งหมด</th>
                    <th style="width: 120px; text-align: center;">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($units as $unit)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $unit->name }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.materalstype.editunit', $unit->id)}}"
                                class="btn-icon btn-edit" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.materalstype.deleteunit', $unit->id) }}"
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
