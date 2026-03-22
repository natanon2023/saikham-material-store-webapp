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

    <div style="margin-top:20px; overflow-x:auto;" class="boxmaterial box-control">
        <div style="display:flex; align-items:center; gap:6px; min-width:max-content;">
            <?php
            $statuses = [
            [1,'#D4AF37','นัดสำรวจ'], [2,'#FF8C00','รอวันสำรวจ'], [3,'#1E90FF','กำลังสำรวจ'],
            [4,'#E91E63','รอเสนอราคา'], [5,'#9C27B0','รออนุมัติ'], [6,'#78d37b','อนุมัติและชำระเงินแล้ว'],
            [7,'#00CED1','วางแผนวัสดุ'], [8,'#FF4500','รอสั่งซื้อ'], [9,'#008080','พร้อมเบิกวัสดุ'],
            [10,'#8B4513','เบิกวัสดุแล้ว'], [11,'#4CAF50','กำลังติดตั้ง'], [12,'#708090','เสร็จสิ้น'],
            [13,'#DC143C','ยกเลิก'],
            ];
            foreach ($statuses as $i => [$num, $color, $label]):
            ?>
            <?php if ($i > 0): ?>
                <div style="width:0.5px; height:16px; background:#ddd;"></div>
            <?php endif; ?>
            <div style="display:flex; align-items:center; gap:7px; padding:6px 11px; background:#f5f5f5; border-radius:8px; border:0.5px solid #e0e0e0; white-space:nowrap;">
                <span style="font-size:11px; color:#999;"><?= $num ?></span>
                <div style="width:9px; height:9px; border-radius:50%; background:<?= $color ?>; flex-shrink:0;"></div>
                <span style="font-size:12px; color:#333;"><?= $label ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
@endsection

<script src="{{ asset('js/technician/calendar-setup.js') }}"></script>
<script>
        window.calendarEvents = {!! json_encode($events, JSON_UNESCAPED_UNICODE) !!};
</script>

