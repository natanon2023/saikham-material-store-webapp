@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div style="background-color: #ffffff; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h4 style="margin-bottom: 20px;">เติมวัสดุทดแทน</h4>
            <a href="{{ route('admin.projects.showissuedetail', $issue->id) }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
        <div style="display: flex; flex-direction: row; text-align: center;">
            <div style="flex: auto; background-color: #334E68; padding: 10px; color:#ffffff;">
                จำนวนที่เสียหาย {{ $issue->damaged_amount }}
            </div>
        </div>
    </div>
    
    @include('components.successanderror')

    <div class="boxmaterial" style="margin-bottom: 20px;">
        <h4 style="margin-bottom: 15px;">ระบุจำนวนที่ต้องการเติมลงในตารางงานเดิม</h4>
        <form action="{{ route('admin.projects.issues.refill.store', $issue->id) }}" method="POST">
            @csrf
            
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">จำนวนที่ต้องการเติมให้ช่าง</label>
                    <input type="number" name="refill_amount" class="form-input" min="1" max="{{ $issue->damaged_amount }}" value="{{ $issue->damaged_amount }}" required>
                </div>

                <div style="margin-top: 15px;">
                    <button type="submit" class="btn btn-secondary">ยืนยันการเติมวัสดุและเสร็จสิ้น</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection