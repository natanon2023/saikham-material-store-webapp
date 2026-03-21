@extends('layouts.technician')

@section('content')
<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3><i class="fa fa-calendar"></i> ตารางงานของฉัน</h3>
        <div>
            งานทั้งหมด: {{ $eventCount }} งาน
        </div>
    </div>

    <div class="box">
        <div id="calendar"></div>
    </div>

    <div style=" margin-top: 20px;" class=" boxmaterial box-control">

        <div style="display: flex; align-items: center; gap: 8px;">
            1.
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #D4AF37;"></div>
            <span style="font-size: 0.9em; "> นัดสำรวจ</span>
        </div>
        
        <div style="display: flex; align-items: center; gap: 8px;">
            2.
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #FF8C00;"></div>
            <span style="font-size: 0.9em; "> รอวันสำรวจ</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            3.
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #1E90FF;"></div>
            <span style="font-size: 0.9em; "> กำลังสำรวจ</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            4.
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #E91E63;"></div>
            <span style="font-size: 0.9em; "> รอเสนอราคา</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            5.
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #9C27B0;"></div>
            <span style="font-size: 0.9em; "> รออนุมัติ</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            6.
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #78d37b;"></div>
            <span style="font-size: 0.9em; ">อนุมัติและชำระเงินแล้ว</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            7. 
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #00CED1;"></div>
            <span style="font-size: 0.9em; ">วางแผนวัสดุ</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            8.
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #FF4500;"></div>
            <span style="font-size: 0.9em; "> รอสั่งซื้อ</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            9.
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #008080;"></div>
            <span style="font-size: 0.9em; "> พร้อมเบิกวัสดุ</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            10. 
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #8B4513;"></div>
            <span style="font-size: 0.9em; ">เบิกวัสดุแล้ว</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            11.
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #4CAF50;"></div>
            <span style="font-size: 0.9em; "> กำลังติดตั้ง</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            12. 
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #708090;"></div>
            <span style="font-size: 0.9em; ">เสร็จสิ้น</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            13.
            <div style="width: 14px; height: 14px; border-radius: 50%; background-color: #DC143C;"></div>
            <span style="font-size: 0.9em; ">ยกเลิก</span>
        </div>

    </div>
</div>
@endsection

<script src="{{ asset('js/technician/calendar-setup.js') }}"></script>
<script>
        window.calendarEvents = {!! json_encode($events, JSON_UNESCAPED_UNICODE) !!};
</script>

