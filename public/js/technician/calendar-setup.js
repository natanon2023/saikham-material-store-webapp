document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    if (!calendarEl) return;

    function getThaiToday() {
        return new Date().toLocaleDateString('th-TH', {
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

        events: window.calendarEvents || [],

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
                subTitle.innerText = `วันนี้คือวันที่: ${getThaiToday()}`;
                titleEl.appendChild(subTitle);
            }
        }
    });

    calendar.render();
});

