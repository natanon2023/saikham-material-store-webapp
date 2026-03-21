@extends('layouts.customer')
@section('content')

@include('components.successanderror')

<div style="max-width: 1100px; margin: 0 auto; padding: 0 1rem;">
    <h3 style="color: #1F2933;">เช็คสถานะการติดตั้ง</h3>

    <form action="{{ route('customer.cakestatuspage') }}" method="GET">
        <div>
            <input type="text" name="phone" placeholder="กรอกเบอร์โทรศัพท์" value="{{ $phone }}" class="form-input" style="width: 70%;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i>ค้นหา</button>
        </div>
    </form>

    @if(count($projects) > 0)<div class="project-timeline">
        @foreach($projects as $row)
        @php
        $thaiStatus = match($row->status) {
        'waiting_survey' => 'รอสำรวจ',
        'pending_survey' => 'ค้างสำรวจ',
        'surveying' => 'กำลังสำรวจ',
        'pending_quotation' => 'รอเสนอราคา',
        'waiting_approval' => 'รออนุมัติ',
        'approved' => 'อนุมัติแล้ว',
        'material_planning' => 'วางแผนวัสดุ',
        'waiting_purchase' => 'รอซื้อวัสดุ',
        'ready_to_withdraw' => 'พร้อมเบิก',
        'materials_withdrawn' => 'เบิกวัสดุแล้ว',
        'installing' => 'กำลังติดตั้ง',
        'completed' => 'เสร็จสิ้น',
        'cancelled' => 'ยกเลิก',
        default => 'อื่นๆ'
        };
        @endphp

        <div class="project-item">
            <div class="project-card">
                <div>
                    <div style="font-size: small; color: #1F2933; margin-bottom: 5px;;">ID: PJ-{{ $row->created_at->format('d-m-Y') }}</div>
                    <h4 class="project-title">{{ $row->projectname->name }}</h4>
                    <div class="status-badge">
                        สถานะ: {{ $thaiStatus }}
                    </div>
                </div>

                <div>
                    <a href="{{ route('customer.projectdetail', $row->id) }}" class="btn btn-primary">
                        ดูรายละเอียด
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

    

@endsection