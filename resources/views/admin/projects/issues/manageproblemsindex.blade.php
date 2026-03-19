@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">โครงการที่มีการรายงานปัญหา</h3>
    </div>

    @forelse($groupedIssues as $projectId => $projectIssues)
        @php
            $project = $projectIssues->first()->project;
            $currentStatus = $statusColors[$project->status] ?? ['#DC143C', 'ยกเลิก'];
            $issueCount = $projectIssues->count();
        @endphp

        @if($project)
        <div class="controlboxproject1" style="display: flex; flex-wrap: wrap; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 20px; border: 1px solid #eaeaea; ">
            
            <div class="boximgproject" style="flex: 1 1 250px; max-width: 300px; min-height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-right: 1px solid #eaeaea;">
                @if (!empty($project->homeimg))
                    <img src="data:image/jpeg;base64,{{ base64_encode($project->homeimg) }}" style="width:100%; height:100%; object-fit:cover;">
                @else
                    <div style="text-align:center; color:#adb5bd; font-size: 1rem;">
                        <i class="fas fa-image" style="font-size: 2.5rem; margin-bottom: 10px;"></i><br>
                        <span>ไม่มีรูปภาพหน้างาน</span>
                    </div>
                @endif
            </div>

            <div class="controlboxproject2" style="flex: 2 1 400px; padding: 25px; display: flex; flex-direction: column; justify-content: center;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                    <h3 style="margin: 0; color: #333; font-size: 1.4rem;">
                        {{ $project->projectname?->name ?? '-' }}
                    </h3>
                    <div style="background-color: {{ $currentStatus[0] }}; color: #fff; padding: 5px 15px; border-radius: 50px; font-weight: bold; font-size: 0.85rem;">
                        {{ $currentStatus[1] }}
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 120px 1fr; gap: 8px; font-size: 1rem; color: #555; margin-bottom: 20px;">
                    <div style="color: #888;">รหัสงาน:</div>
                    <div><strong>{{ $project->project_code.'-'.\Carbon\Carbon::parse($project->created_at)->format('Ymd') }}</strong></div>

                    <div style="color: #888;">ชื่อลูกค้า:</div>
                    <div><strong>คุณ {{ $project->customer->first_name ?? '-' }} {{ $project->customer->last_name ?? '' }}</strong></div>
                    
                    <div style="color: #888;">จำนวนปัญหา:</div>
                    <div><strong style="color: #e74c3c;">{{ $issueCount }} รายการ</strong></div>
                </div>

                <div style="text-align: right; margin-top: auto;">
                    <a href="{{ route('admin.projects.issues.detail',$project->id) }}" class="btn btn-danger" style="padding: 10px 20px; height:max-content;">
                        ดูรายละเอียดปัญหาทั้งหมด
                    </a>
                </div>
            </div>

        </div>
        @endif
    @empty
        <div class="boxmaterial" style="text-align: center; padding: 40px; color: #888;">
            ไม่มีโครงการที่มีการรายงานปัญหาในขณะนี้
        </div>
    @endforelse
</div>
@endsection