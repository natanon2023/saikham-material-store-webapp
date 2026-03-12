@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="margin-bottom: 20px; display: flex; justify-content: space-between;">
        <h3>กำหนดช่างและวันทำงาน</h3>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <form method="POST" action="{{ route('admin.projects.assignInstaller', $project->id) }}">
        @csrf
        @method('PUT')

        <div class="boxmaterial" >
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ช่างติดตั้ง</label>
                    <select name="assigned_installer_id" class="form-input" required>
                        <option value="">เลือกช่าง</option>
                        @foreach ($technician as $installer)
                        <option value="{{ $installer->id }}"
                            {{ $project->assigned_installer_id == $installer->id ? 'selected' : '' }}>
                            {{ 'คุณ '.$installer->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        วันเริ่มงาน (ทำงาน {{ $project->estimated_work_days }} วัน)
                    </label>
                    <input type="date" name="installation_start_date" class="form-input" value="{{ $project->installation_start_date }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">วันจบงาน</label>
                    <input type="date" class="form-input" id="installation_end_date" value="{{ $project->installation_end_date }}" readonly>
                </div>

                <button class="btn btn-secondary">บันทึก</button>
            </div>
        </div>

    </form>
</div>


<script>
    document.querySelector('input[name="installation_start_date"]').addEventListener('change', function() {
        if (!this.value) {
            document.getElementById('installation_end_date').value = '';
            return;
        }

        const startDate = new Date(this.value);
        
        const workDays = parseInt("{{ $project->estimated_work_days ?? 1 }}", 10);
        const daysToadd = workDays > 0 ? workDays - 1 : 0;

        startDate.setDate(startDate.getDate() + daysToadd);

        const yyyy = startDate.getFullYear();
        const mm = String(startDate.getMonth() + 1).padStart(2, '0');
        const dd = String(startDate.getDate()).padStart(2, '0');

        document.getElementById('installation_end_date').value = `${yyyy}-${mm}-${dd}`;
    });
</script>
@endsection