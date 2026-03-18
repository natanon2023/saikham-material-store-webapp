@extends('layouts.admin')

@section('content')


<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3><i class="fa fa-calendar"></i> ตารางงานของฉัน</h3>
        <div>
            งานทั้งหมด: {{ count($events) }} งาน
        </div>
    </div>

    <div class="box">
        
        <div id="calendar"></div>
    </div>

    <div class="boxmaterial" style="margin-top: 20px; padding: 20px;">
        <h4>
            หมายเหตุ: ขั้นตอนดำเนินงานและความหมายของสีสถานะโครงการ
        </h4>
    
    <div style=" margin-top: 20px;" class="box-control">

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