@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>ถังขยะ</h3>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">กลับไปหน้าข้อมูลผู้ใช้งาน</a>
            </div>
    </div>

    <div>

       
        <table border="1" cellpadding="5" cellspacing="0" style="margin-top: 10px; width: 100%;">
            <thead>
                <tr style="text-align: center;">
                    <td>ชื่อ - นามสกุล</td>
                    <td>อีเมล</td>
                    <td>สถานะผู้ใช้งาน</td>
                    <td>การจัดการ</td>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="text-align: center;" class="searchable-item" data-name="{{ strtolower($user->name . ' ' . $user->email) }}">
                        <td>{{ $user->name.' '.$user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role === 'technician' ? 'ช่าง' : ($user->role === 'admin' ? 'แอดมิน' : $user->role) }}</td>
                        <td>
                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary">กู้คืน</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr style="text-align: center;">
                        <td colspan="4">ไม่มีข้อมูลในถังขยะ</td>
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
