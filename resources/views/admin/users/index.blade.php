@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>ข้อมูลผู้ใช้งานทั้งหมด</h3>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-secondary">+ เพิ่มผู้ใช้</a>
            <a href="{{ route('admin.users.trash') }}" class="btn btn-delecte">
                <i class="fas fa-trash" style="margin-right: 5px;"></i>ถังขยะ
            </a>
        </div>
    </div>

    <div class="control-boxsearch">
        <div class="boxsearch">
            <form action="{{ route('admin.users.index') }}" method="GET" style="display:flex; gap:10px; align-items:center;">
                <select name="role" class="form-select">
                    <option value="">ทั้งหมด</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>
                        แอดมิน
                    </option>
                    <option value="technician" {{ request('role') == 'technician' ? 'selected' : '' }}>
                        ช่าง
                    </option>
                </select>

                <button type="submit" class="btn btn-secondary">ค้นหา</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">รีเซ็ต</a>
            </form>
        </div>
    </div>



    <div>
        <table border="1" cellpadding="5" cellspacing="0" style="margin-top: 10px; width: 100%;">
            <thead>
                <tr style="text-align: center;">
                    <td style="width: 60px; text-align: center;">ลำดับ</td>
                    <td>ชื่อ - นามสกุล (ชื่อเล่น)</th>
                    <td>อีเมล</th>
                    <td>สถานะผู้ใช้งาน</th>
                    <td>การจัดการ</th>
                </tr>
            </thead>
            <tbody id="userTable">
                @forelse($users as $user)
                <tr class="searchable-item" data-name="{{ strtolower($user->name . ' ' . $user->email) }}">
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $user->name }} {{ $user->last_name  }}
                        @if ($user->role === 'technician')
                        ({{ 'ช่าง'.$user->nickname }})
                        @elseif($user->role === 'admin')
                        ({{ 'คุณ'.$user->nickname }})
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $user->email }}</td>
                    <td style="text-align: center;">{{ $user->role === 'technician' ? 'ช่าง' : ($user->role === 'admin' ? 'แอดมิน' : $user->role) }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.users.show', $user->id) }}"
                            class="btn-icon btn-show" title="ดูรายละเอียด">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon btn-edit" title="แก้ไข"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('ยืนยันการลบ?')" title="ลบ" class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">ไม่มีข้อมูล</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div id="noResultMessage" style="display: none; text-align: center; padding: 20px; color: #999;">
            ไม่พบรายการที่ค้นหา
        </div>
    </div>
</div>
@endsection