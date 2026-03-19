@extends('layouts.admin')

@section('content')

<div class="main-content">
    @include('components.successanderror')
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;  margin-bottom: 20px;">
        <h3 style="margin: 0;">รายละเอียดงาน</h3>
        <a href="{{ route('admin.projects.adminfulleventcalendarpage') }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div class="controlboxproject1" style="display: flex; flex-wrap: wrap; background: {{ $project->trashed() ? '#f8d7da' : '#fff' }}; opacity: {{ $project->trashed() ? '0.85' : '1' }}; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 20px; border: {{ $project->trashed() ? '2px dashed #dc3545' : '1px solid #eaeaea' }};">

        @if($project->trashed())
        <div style="width: 100%; background-color: #dc3545; color: white; text-align: center; padding: 10px; font-weight: bold; font-size: 1.1rem; letter-spacing: 1px;">
            <i class="fas fa-trash-alt"></i> งานนี้ถูกลบไปแล้ว
        </div>
        @endif

        <div class="boximgproject" style="flex: 1 1 350px; min-height: 300px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-right: 1px solid #eaeaea; {{ $project->trashed() ? 'filter: grayscale(100%);' : '' }}">
            @if (!empty($project->homeimg))
            <img src="data:image/jpeg;base64,{{ base64_encode($project->homeimg) }}" alt="Project Image" style="width:100%; height:100%; object-fit:cover;">
            @else
            <div style="text-align:center; color:#adb5bd; font-size: 1.2rem;">
                <i class="fas fa-image" style="font-size: 3rem; margin-bottom: 10px;"></i><br>
                <span>ยังไม่มีรูปภาพหน้างาน</span>
            </div>
            @endif
        </div>

        <div class="controlboxproject2" style="flex: 2 1 500px; padding: 25px; display: flex; flex-direction: column;">

            <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #f1f3f5; padding-bottom: 15px; margin-bottom: 15px;">
                <h3 style="margin: 0; color: #333; font-size: 1.4rem;">{{ $project->projectname->name }}</h3>
                <div style="background-color: {{ $currentStatus[0] }}; color: #fff; padding: 6px 20px; border-radius: 50px; font-weight: bold; font-size: 0.9rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: inline-block; text-align: center;">
                    {{ $currentStatus[1] }}
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 140px 1fr; gap: 10px; font-size: 1rem; color: #555; margin-bottom: 20px;">
                <div style="color: #888;">รหัสงาน:</div>
                <div><strong>{{ $project->project_code.'-'.\Carbon\Carbon::parse($project->created_at)->format('Ymd') }}</strong></div>

                <div style="color: #888;">ชื่อลูกค้า:</div>
                <div><strong>คุณ {{ $project->customer->first_name }} {{ $project->customer->last_name }}</strong></div>

                <div style="color: #888;">เบอร์โทร:</div>
                <div><strong>{{ $project->customer->phone ?? '-' }}</strong></div>

                @if (in_array($project->status, ['pending_survey', 'waiting_survey', 'surveying']))
                <div style="color: #888;">วันนัดสำรวจ:</div>
                <div style="color: #FF8C00;">
                    <strong>
                        {{ $project->survey_date
                        ? \Carbon\Carbon::parse($project->survey_date)
                        ->locale('th') 
                        ->addYears(543) 
                        ->isoFormat('D MMMM YYYY') 
                        : 'ยังไม่ได้กำหนดวันทำงาน' 
                    }}
                    </strong>
                </div>
                @endif



                @if(in_array($project->status, ['materials_withdrawn', 'installing', 'completed','ready_to_withdraw']))
                <div style="color: #888;">วันทำงาน:</div>
                @if ($project->installation_start_date != null && $project->installation_end_date != null)
                <div style="color: #4CAF50;">
                    <strong>
                        วันที่
                        {{ $project->installation_start_date 
                                ? \Carbon\Carbon::parse($project->installation_start_date)
                                ->locale('th') 
                                ->addYears(543) 
                                ->isoFormat('D MMMM YYYY') 
                                : 'ยังไม่ได้กำหนดวันทำงาน' 
                        }}
                        ถึง วันที่
                        {{ $project->installation_end_date 
                                ? \Carbon\Carbon::parse($project->installation_end_date)
                                ->locale('th') 
                                ->addYears(543) 
                                ->isoFormat('D MMMM YYYY') 
                                : 'ยังไม่ได้กำหนดวันทำงาน' 
                        }}
                    </strong>
                </div>
                @else

                <div style="color: #4CAF50;">
                    <strong>
                        ยังไม่ได้กำหนดวันทำงาน
                    </strong>
                </div>
                @endif
                @endif
            </div>

            <div style="margin-top: auto; padding-top: 15px; border-top: 1px solid #f1f3f5; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @if(!$project->trashed())
                    @if ($project->status == 'pending_survey')
                    <a href="{{ route('admin.projects.expensedetail', $project->id) }}" class="btn btn-secondary btn-full-text">เพิ่มค่าใช้จ่าย</a>

                    @elseif ($project->status == 'waiting_survey')
                    <form action="{{ route('admin.projects.updatestatussurveying', $project->id) }}" method="post" style="margin:0;">
                        @csrf
                        <button class="btn btn-secondary btn-full-text">สำรวจหน้างาน</button>
                    </form>

                    @elseif($project->status == 'surveying')
                    <a href="{{ route('admin.projects.formsurveying', $project->id) }}" class="btn btn-secondary btn-full-text">บันทึกการสำรวจ</a>

                    @elseif($project->status == 'pending_quotation')
                    <a href="{{ route('admin.projects.addbid', $project->id) }}" class="btn btn-secondary btn-full-text">เสนอราคา</a>

                    @elseif($project->status == 'waiting_approval')
                    <form action="{{ route('admin.projects.updatestatusapproved', $project->id) }}" method="post" style="margin:0;">
                        @csrf
                        <button class="btn btn-secondary btn-full-text">ลูกค้าอนุมัติ</button>
                    </form>

                    @elseif($project->status == 'approved')
                    <form action="{{ route('admin.projects.updatestatusmaterialplanning', $project->id) }}" method="post" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-full-text">วางแผนวัสดุ</button>
                    </form>
                    @elseif($project->status == 'material_planning')
                    <a href="{{ route('admin.projects.materialplanningpage', $project->id) }}" class="btn btn-secondary btn-full-text">รายการที่จะซื้อ</a>

                    @elseif($project->status == 'waiting_purchase')
                    <a href="{{ route('admin.projects.restockpage', $project->id) }}" class="btn btn-secondary btn-full-text">เติมสต็อกวัสดุ</a>
                    @elseif($project->status == 'ready_to_withdraw')

                    @if(!empty($project->installation_start_date) && now()->format('Y-m-d') >= $project->installation_start_date && $project->installers->count() > 0 && $project->installation_start_date != null )
                    <a href="{{ route('admin.projects.withdrawpage', $project->id) }}" class="btn btn-secondary btn-full-text">เบิกวัสดุ</a>
                    @else
                    <button class="btn btn-secondary btn-full-text" style="height: max-content; opacity: 0.6; cursor: not-allowed;" disabled>
                        รอถึงวันทำงานจึงจะสามารถเบิกของได้ (
                        {{ $project->installation_start_date 
                                    ? \Carbon\Carbon::parse($project->installation_start_date)
                                    ->locale('th') 
                                    ->addYears(543) 
                                    ->isoFormat('D MMMM YYYY') 
                                    : 'ยังไม่ได้กำหนดวันทำงาน' 
                                }}
                        )
                    </button>
                    @endif
                    <a href="{{ route('admin.projects.installingpage', $project->id) }}" class="btn btn-secondary btn-full-text">กำหนดวันทำงาน</a>
                    @elseif($project->status == 'materials_withdrawn')
                    @if(!empty($project->installation_start_date) && now()->format('Y-m-d') >= $project->installation_start_date)
                    <form action="{{ route('admin.projects.updatestatusinstalling', $project->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-full-text" style="height: max-content;">
                            เริ่มการติดตั้ง
                        </button>
                    </form>
                    @else
                    <button class="btn btn-secondary btn-full-text" style="height: max-content; opacity: 0.6; cursor: not-allowed;" disabled>
                        รอถึงวันทำงานจึงจะสามารถเริ่มติดตั้งได้ (
                        {{ $project->installation_start_date 
                                    ? \Carbon\Carbon::parse($project->installation_start_date)
                                    ->locale('th') 
                                    ->addYears(543) 
                                    ->isoFormat('D MMMM YYYY') 
                                    : 'ยังไม่ได้กำหนดวันทำงาน' 
                                }}
                        )
                    </button>
                    @endif

                    @elseif($project->status == 'installing')
                        <a href="{{ route('admin.projects.choosetypeissues', $project->id) }}" class="btn btn-danger btn-full-text">แจ้งปัญหา</a>
                        <a href="{{ route('admin.projects.confirmworkcompletedpage', $project->id) }}" class="btn btn-secondary btn-full-text">ยืนยันการทำงานเสร็จสิ้น</a>
                    @elseif($project->status == 'completed')
                        <form action="{{ route('admin.projects.updatestatusinstalling', $project->id) }}" method="POST" style="margin: 0;">
                        @csrf
                            <button class="btn btn-danger btn-full-text" style="height: max-content;">ยังติดตั้งไม่สำเร็จ</button>
                        </form>
                    @endif
                    @else
                        <span style="color: #dc3545; font-weight: bold;">กรุณากู้คืนงานก่อนทำรายการต่อ</span>
                    @endif
                </div>
                <div>
                    <a href="{{ route('admin.projects.alldetail', $project->id) }}" class="btn btn-primary btn-full-text" title="ดูรายละเอียดเต็ม" style="{{ $project->trashed() ? 'pointer-events: none; opacity: 0.5;' : '' }}">
                        ดูรายละเอียดทั้งหมด
                    </a>

                    @if($project->trashed())
                    <form action="{{ route('admin.projects.restore', $project->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('ยืนยันการกู้คืน?');">
                        @csrf
                        <button type="submit" class="btn-icon " style="background-color: #4CAF50; color:#fff;" title="กู้คืน">
                            <i class="fas fa-undo"></i>
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" title="ลบ">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection