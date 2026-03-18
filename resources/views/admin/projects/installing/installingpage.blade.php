@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="margin-bottom: 20px; display: flex; justify-content: space-between;">
        <h3>กำหนดช่างและวันทำงาน</h3>
        <a href="{{ route('admin.projects.index',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>



    <form method="POST" action="{{ route('admin.projects.assignInstaller', $project->id) }}">
        @csrf
        @method('PUT')

        <div class="boxmaterial">
            <div class="box-control">
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

    <div class="boxmaterial" style="margin-top: 20px;">
        <h3 style="margin-top: 0; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            ระยะเวลาทำงาน {{ $project->estimated_work_days }} วัน
        </h3>

        <div class="box-control" style="display: flex; gap: 20px; margin-top: 15px;">
            
            <div style="flex: 1; background-color: #f9f9f9; padding: 15px;  border-left: 4px solid #b5ffc6;">
                <span style="font-size: 0.85em; color: #666; display: block;">วันเริ่มงาน</span>
                {{ $project->installation_start_date 
                        ? \Carbon\Carbon::parse($project->installation_start_date)
                        ->locale('th') 
                        ->addYears(543) 
                        ->isoFormat('D MMMM YYYY') 
                        : 'ยังไม่ได้กำหนดวันทำงาน' 
                }}
            </div>

            <div style="flex: 1; background-color: #f9f9f9; padding: 15px;  border-left: 4px solid #ffa7b0;">
                <span style="font-size: 0.85em; color: #666; display: block;">วันจบงาน</span>
                {{ $project->installation_end_date 
                        ? \Carbon\Carbon::parse($project->installation_end_date)
                        ->locale('th') 
                        ->addYears(543) 
                        ->isoFormat('D MMMM YYYY') 
                        : 'ยังไม่ได้กำหนดวันทำงาน' 
                }}
            </div>

        </div>
    </div>


    <div class="boxmaterial" style="margin-top: 20px; margin-bottom: 10px;">
        <h3>เลือกช่างติดตั้ง</h3>
        <form action="{{ route('admin.projects.assignInstalleruser', $project->id) }}" method="POST">
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <select name="user_id" class="form-input" required>
                        <option value="">เลือกช่าง</option>
                        @foreach ($technician as $installer)
                        <option value="{{ $installer->id }}">
                            {{ 'ช่าง '.$installer->name.' '.$installer->last_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-secondary">บันทึก</button>
                </div>
            </div>
        </form>

    </div>


    <div class="boxmaterial">
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between;">
            รายชื่อช่างติดตั้ง
        </div>
        <table>
            <tr align="center">
                <th>ลำดับ</th>
                <th>ชื่อ - สกุล</th>
                <th>จัดการ</th>
            </tr>
            @foreach ($project->installers as $installer)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td align="center">ช่าง {{ $installer->name }} {{ $installer->last_name }}</td>
                <td align="center">
                    <form action="{{ route('admin.projects.removeinstaller', $installer->pivot->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" title="ลบ" onclick="return confirm('ลบเฉพาะช่างคนนี้ใช่หรือไม่?')">
                            <i class="fas fa-trash" ></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach

        </table>
        
    </div>

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