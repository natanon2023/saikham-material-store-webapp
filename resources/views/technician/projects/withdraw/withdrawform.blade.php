@extends('layouts.technician')

@section('content')
<div class="main-content">

    <div class="boxmaterial" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h3 style="margin:0;">ยืนยันการเบิกวัสดุ</h3>
        <a href="{{ route('technician.projects.withdrawpage', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <form action="{{ route('technician.projects.withdrawstore', $project->id) }}" method="POST">
        @csrf

        @foreach($selectedPriceIds as $pid)
            <input type="hidden" name="selected_price_ids[]" value="{{ $pid }}">
        @endforeach

        @foreach($customQtys as $pid => $qty)
            <input type="hidden" name="custom_qty[{{ $pid }}]" value="{{ $qty }}">
        @endforeach

        <div class="boxmaterial" style="margin-bottom:20px;">
            <div style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
                <div style="flex:1; min-width:200px;">
                    <label class="form-label">โครงการ</label>
                    <input type="text" class="form-input" value="{{ $project->projectname->name }}" readonly>
                </div>
                <div style="flex:1; min-width:250px;">
                    <label class="form-label">เลือกผู้เบิก</label>
                    <select name="withdrawn_by" class="form-input" required>
                        <option value="">กรุณาเลือกผู้เบิก</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} {{ $user->last_name }}
                                ({{ $user->role == 'technician' ? 'แอดมิน' : 'ช่าง' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="boxmaterial">
            <h4 style="margin-bottom:10px;">สรุปรายการที่กำลังจะเบิก</h4>
            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse; border:1px solid #eee;">
                <thead style="background:#f9f9f9;">
                    <tr align="center">
                        <th>ลำดับ</th>
                        <th>ประเภท</th>
                        <th>รายละเอียด</th>
                        <th>ล็อต</th>
                        <th>จำนวนที่จะเบิก</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itemsToWithdraw as $index => $row)
                    <tr style="border-bottom:1px solid #eee; text-align:center;">
                        <td>{{ $index + 1 }}</td>
                        <td><b>{{ $row['material_type'] }}</b></td>
                        <td align="left">{{ $row['description'] }}</td>
                        <td>{{ $row['lot'] }}</td>
                        <td><b>{{ $row['qty'] }}</b></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="display:flex; justify-content:flex-end; margin-top:20px;">
            <button type="submit" class="btn btn-secondary">ยืนยันการเบิก</button>
        </div>
    </form>
</div>
@endsection