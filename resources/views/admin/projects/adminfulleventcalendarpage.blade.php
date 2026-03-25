@extends('layouts.admin')

@section('content')


<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3><i class="fa fa-calendar"></i> ตารางงานของฉัน</h3>
        <div>
            งานทั้งหมด: {{ $eventCount }} งาน
        </div>
    </div>

    <div style="margin-top:20px; overflow-x:auto;" class="boxmaterial box-control">
        <div style="display:flex; align-items:center; gap:6px; min-width:max-content;">
            <?php
            $statuses = [
            ['#D4AF37','นัดสำรวจ'], ['#1E90FF','กำลังสำรวจ'],
            ['#9C27B0','รออนุมัติ'], ['#78d37b','อนุมัติและชำระเงินแล้ว'],
            ['#00CED1','วางแผนวัสดุ'], ['#FF4500','รอสั่งซื้อ'], ['#008080','พร้อมเบิกวัสดุ'],
            ['#8B4513','เบิกวัสดุแล้ว'], ['#4CAF50','กำลังติดตั้ง'], ['#708090','เสร็จสิ้น'],
            ['#DC143C','ยกเลิก'],
            ];
            foreach ($statuses as $i => [$color, $label]):
            ?>
            <?php if ($i > 0): ?>
                <div style="width:0.5px; height:16px; background:#ddd;"></div>
            <?php endif; ?>
            <div style="display:flex; align-items:center; gap:7px; padding:6px 11px; background:#f5f5f5; border-radius:8px; border:0.5px solid #e0e0e0; white-space:nowrap;">
                <div style="width:9px; height:9px; border-radius:50%; background:<?= $color ?>; flex-shrink:0;"></div>
                <span style="font-size:12px; color:#333;"><?= $label ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="box">
        
        <div id="calendar"></div>
    </div>

    
    
    
</div>
@endsection
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
    window.calendarEvents = {!! json_encode($events, JSON_UNESCAPED_UNICODE) !!};

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        function getThaiToday() {
            const now = new Date();
            return now.toLocaleDateString('th-TH', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'th',
            firstDay: 1,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },

            buttonText: {
                today: 'วันนี้',
                month: 'เดือน',
                week: 'สัปดาห์',
                day: 'วัน',      
                list: 'รายการ'   
            },
            
            titleFormat: function(info) {
                const date = info.date.marker;
                const month = date.toLocaleDateString('th-TH', { month: 'long' });
                const year = date.getFullYear() + 543;
                return `${month} ${year}`;
            },

            events: window.calendarEvents,

            dayCellDidMount: function(info) {
                let addButton = document.createElement('a');
                addButton.innerHTML = '<i class="fa-solid fa-circle-plus"></i>';
                addButton.className = 'btn-add-calendar';
                addButton.title = 'เพิ่มงานใหม่';
                
                let date = info.date;
                let dateStr = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
                addButton.href = "{{ route('admin.projects.formpendingsurvey') }}?date=" + dateStr;

                let dayTop = info.el.querySelector('.fc-daygrid-day-top');
                if (dayTop) {
                    dayTop.appendChild(addButton);
                }
            },

            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault();
                }
            },

            datesSet: function() {
                let titleEl = document.querySelector('.fc-toolbar-title');
                if (titleEl && !document.getElementById('today-sub-title')) {
                    let subTitle = document.createElement('div');
                    subTitle.id = 'today-sub-title';
                    subTitle.style.fontSize = '14px';
                    subTitle.style.color = '#666';
                    subTitle.style.fontWeight = 'normal';
                    subTitle.style.marginTop = '5px';
                    subTitle.innerText = `วันนี้คือวันที่: ${getThaiToday()}`;
                    titleEl.appendChild(subTitle);
                }
            }
        });

        calendar.render();
    });
</script>