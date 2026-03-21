@extends('layouts.technician')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">แก้ไขจำนวนการเบิกวัสดุ</h3>
        <a href="{{ route('technician.projects.withdrawdetails', $item->withdrawal->project_id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>
    <div class="boxmaterial" style="margin-bottom: 20px;">
        <h4 >ข้อมูลรายการที่จะแก้ไข</h4>
            <strong>ประเภท:</strong> {{ $item->material->material_type ?? '-' }} |
            <strong>รายละเอียด:</strong> {{ $detail }} |
            <strong>ล็อต:</strong> {{ $item->lot }} | <strong>จำนวนปัจจุบัน:</strong> {{ $item->quantity }} |
            <strong>วันที่เบิก:</strong> {{ \Carbon\Carbon::parse($item->withdrawal->created_at)->locale('th')->addYears(543)->isoFormat('D MMM YY') }} |
            <strong>ผู้เบิก:</strong> {{ $item->withdrawal->withdrawnBy->name ?? '-' }}
    </div>

    <div class="boxmaterial">
        <h4 style="margin-bottom: 20px;">กรอกข้อมูลที่ต้องการแก้ไข</h4>
        <form action="{{ route('technician.projects.edit_withdrawal_item', $item->id) }}" method="POST"
              onsubmit="return confirm('ยืนยันการแก้ไขจำนวน?');">
            @csrf
            @method('PUT')
            <div class="box-control">
                <div class="form-group">
                <label class="form-label">จำนวนที่ถูกต้อง</label>
                <input  class="form-input" type="number" name="quantity" value="{{ $item->quantity }}" min="0" >
            </div>

            <div class="form-group">
                <label class="form-label">เหตุผลการแก้ไข </label>
                <textarea  name="reason" rows="3" required placeholder="ระบุเหตุผลการแก้ไข เช่น กรอกผิด, นับผิด, คืนบางส่วน..."
                class="form-input">
            </textarea>
            </div>

            <button type="submit" class="btn btn-secondary" >
                บันทึกการแก้ไข
            </button>
            </div>

            
        </form>
    </div>

    @if($logs->isNotEmpty())
    <div class="boxmaterial" style="margin-top: 20px;">
        <h4 style="margin: 0 0 15px 0;">ประวัติการแก้ไขรายการนี้</h4>
        <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
            <thead style="background: #333; color: #fff;">
                <tr align="center">
                    <th width="20%">วันที่แก้ไข</th>
                    <th width="15%">ผู้แก้ไข</th>
                    <th width="12%" style="text-align: center;">จากจำนวน</th>
                    <th width="12%" style="text-align: center;">เป็นจำนวน</th>
                    <th>เหตุผล</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr style="border-bottom: 1px solid #eee;" align="center">
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->locale('th')->addYears(543)->isoFormat('D MMM YY HH:mm') }}</td>
                    <td>{{ $log->editor->name ?? '-' }}</td>
                    <td align="center">{{ $log->old_quantity }}</td>
                    <td align="center"><b>{{ $log->new_quantity }}</b></td>
                    <td>{{ $log->reason ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection