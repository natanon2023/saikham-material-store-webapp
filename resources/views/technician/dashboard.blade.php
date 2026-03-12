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
</div>
@endsection

<script src="{{ asset('js/technician/calendar-setup.js') }}"></script>
<script>
        window.calendarEvents = {!! json_encode($events, JSON_UNESCAPED_UNICODE) !!};
</script>

