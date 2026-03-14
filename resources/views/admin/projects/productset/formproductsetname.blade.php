@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')
    <div class="boxmaterial" style="display: flex; justify-content: space-between;">
        <h3>เพิ่มชื่อผลิตภัณฑ์ใหม่</h3>
        <a href="javascript:history.back()" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div class="box">
        <form action="{{ route('admin.projects.createproductsetname') }}" method="post">
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ชื่อผลิตภัณฑ์ใหม่</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">เพิ่มชื่อผลิตภัณฑ์</button>
                </div>
            </div>
        </form>
    </div>

    <h3 style="margin-bottom: 20px; margin-top: 30px;">ข้อมูลชื่อผลิตภัณฑ์ทั้งหมด</h3>
    <table width="100%" border="1" style="border-collapse: collapse; margin-top: 20px;">
        <tr align="center" style="background-color: #f4f4f4;">
            <td>ลำดับ</td>
            <td>ชื่อผลิตภัณฑ์</td>
            <td>แก้ไข</td>
            <td>ลบ</td>
        </tr>
        
        @foreach ($productsetnameall as $productsetname)
        <tr align="center" style ="{{ $productsetname->trashed() ? 'opacity: 0.5; background-color: #f9f9f9;' : '' }}">
            <td>{{ $loop->iteration }}</td>
            
            <td width="30%">
                {{ $productsetname->name }}
            </td>

            <td width="40%">
                @if(!$productsetname->trashed())
                    <form action="{{ route('admin.projects.admupdateproductsetname', $productsetname->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group" style="display: flex; flex-direction: row; gap: 5px; justify-content: center; margin-bottom: 0;">
                            <input type="text" name="name" value="{{ $productsetname->name }}" class="form-input" required>
                            <button type="submit" class="btn btn-warning">แก้ไข</button>
                        </div>
                    </form>
                @else
                    <span style="color: gray;">(ข้อมูลถูกระงับ)</span>
                @endif
            </td>

            <td width="20%">
                @if($productsetname->trashed())
                    <a href="{{ route('admin.projects.restoreproductsetname', $productsetname->id) }}" 
                       class="btn btn-secondary">กู้คืนข้อมูล</a>
                @else
                    <a href="{{ route('admin.projects.deleteproductsetname', $productsetname->id) }}" 
                       class="btn btn-delecte"
                       onclick="return confirm('ยืนยันการลบชื่อผลิตภัณฑ์นี้?')">ลบ</a>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection