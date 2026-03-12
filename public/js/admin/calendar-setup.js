
   
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
            
            titleFormat: function(info) {
                const date = info.date.marker;
                const month = date.toLocaleDateString('th-TH', { month: 'long' });
                const year = date.getFullYear() + 543;
                return `${month} ${year}`;
            },

            events: window.calendarEvents || [],

            // ฟังก์ชันสำหรับจัดการปุ่มบวกในทุกช่องวันที่
            dayCellDidMount: function(info) {
                // สร้างไอคอนบวก
                let addButton = document.createElement('a');
                addButton.innerHTML = '<i class="fa-solid fa-circle-plus"></i>';
                addButton.className = 'btn-add-calendar';
                addButton.title = 'เพิ่มงานใหม่';
                
                // ดึงค่าวันที่ในช่องนั้นๆ เพื่อส่งไปที่หน้าฟอร์ม (Optional: ส่งไปทาง URL)
                let dateStr = info.date.toISOString().split('T')[0];
                addButton.href = "{{ route('formpendingsurvey') }}?date=" + dateStr;

                // นำไปใส่ในส่วนหัวของช่องวันที่ (ข้างๆ ตัวเลข)
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