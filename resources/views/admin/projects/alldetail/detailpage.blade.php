@extends('layouts.admin')

@section('content')

@php
    $statusMap = [
        'pending_survey'      => ['#D4AF37', 'นัดสำรวจ'],
        'waiting_survey'      => ['#FF8C00', 'รอวันสำรวจ'],
        'surveying'           => ['#1E90FF', 'กำลังสำรวจ'],
        'pending_quotation'   => ['#E91E63', 'รอเสนอราคา'],
        'waiting_approval'    => ['#9C27B0', 'รออนุมัติ'],
        'approved'            => ['#78d37b', 'อนุมัติและชำระเงินแล้ว'],
        'material_planning'   => ['#00CED1', 'วางแผนวัสดุ'],
        'waiting_purchase'    => ['#FF4500', 'รอสั่งซื้อ'],
        'ready_to_withdraw'   => ['#008080', 'พร้อมเบิก'],
        'materials_withdrawn' => ['#8B4513', 'เบิกวัสดุแล้ว'],
        'installing'          => ['#4CAF50', 'กำลังติดตั้ง'],
        'completed'           => ['#708090', 'เสร็จสิ้น'],
        'cancelled'           => ['#DC143C', 'ยกเลิก'],
    ];
    $cs = $statusMap[$project->status] ?? ['#999', 'ไม่ระบุ'];

    $phase1 = ['pending_survey', 'waiting_survey', 'surveying', 'pending_quotation'];
    $phase2 = ['waiting_approval','approved', 'material_planning', 'waiting_purchase', 'ready_to_withdraw', 'materials_withdrawn', 'installing', 'completed'];

    $missingInfo = [];
    if (empty($project->daily_labor_rate) || empty($project->estimated_work_days)) {
        $missingInfo[] = 'ประเมินหน้างานและค่าแรงติดตั้ง (ข้อ 3)';
    }
    if ($project->customerneed->count() == 0) {
        $missingInfo[] = 'ภาพหน้างานและความต้องการของลูกค้าอย่างน้อย 1 รายการ (ข้อ 4 และ 5)';
    }
    if (empty($project->installation_start_date)) {
        $missingInfo[] = 'กำหนดวันเริ่มติดตั้ง (ข้อ 6)';
    }
    if ($project->installers->count() == 0) {
        $missingInfo[] = 'เพิ่มทีมช่างติดตั้งอย่างน้อย 1 คน (ข้อ 6)';
    }
    
    $isReadyForQuotation = count($missingInfo) === 0;
@endphp

<div class="main-content">

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h2 class="page-title" >
            {{ $project->projectname?->name ?? 'ไม่มีชื่องาน' }}
            <div style="background-color: {{ $cs[0] }}; color: #fff; padding: 6px 20px; border-radius: 50px; font-weight: bold; font-size: 0.9rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: inline-block; text-align: center; margin-left: 10px;">
                {{ $cs[1] }}
            </div>
        </h2>
        <div style="display: flex; justify-content: space-between;">
            <a href="{{ route('admin.projects.adminfulleventcalendarpage') }}" class="btn btn-primary btn-full-text">ย้อนกลับ</a>
            @if ($project->status == 'waiting_approval')
            <form action="{{ route('admin.projects.updatestatusapproved', $project->id) }}" method="post" style="margin:0;">
                    @csrf
                <button class="btn btn-secondary btn-full-text">ลูกค้าอนุมัติและชำระเงินแล้ว</button>
            </form>
            @elseif($project->status == 'approved')
                    <form action="{{ route('admin.projects.updatestatusmaterialplanning', $project->id) }}" method="post" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-full-text">วางแผนวัสดุ</button>
                    </form>
            @elseif($project->status == 'waiting_purchase')
                    <a href="{{ route('admin.projects.restockpage', $project->id) }}" class="btn btn-secondary btn-full-text">เติมสต็อกวัสดุ</a>
            @elseif($project->status == 'ready_to_withdraw')
                        @if(!empty($project->installation_start_date) && now()->format('Y-m-d') >= \Carbon\Carbon::parse($project->installation_start_date)->format('Y-m-d') )
                            <a href="{{ route('admin.projects.withdrawpage', $project->id) }}" class="btn btn-secondary btn-full-text">เบิกวัสดุ</a>
                        @else
                            <button class="btn btn-secondary btn-full-text" style="height: max-content; opacity: 0.6; cursor: not-allowed;" disabled>
                                รอวันทำงานเบิกของได้
                            </button>
                        @endif
            @elseif($project->status == 'materials_withdrawn')
                        @if (\Carbon\Carbon::parse($project->installation_start_date)->addDay()->isFuture())
                            <form action="{{ route('admin.projects.cancelWithdrawal', $project->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('ยืนยันการยกเลิกการเบิก? ระบบจะคืนจำนวนวัสดุทั้งหมดกลับเข้าคลังและเปลี่ยนสถานะเป็นพร้อมเบิก');">
                                @csrf
                                <button type="submit" class="btn btn-delecte btn-full-text" style="height: max-content;">
                                    ยกเลิกการเบิกวัสดุ
                                </button>
                            </form>
                        @endif
                        @if(!empty($project->installation_start_date) && now()->format('Y-m-d') >= \Carbon\Carbon::parse($project->installation_start_date)->format('Y-m-d'))
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
                    <div style="display: flex; flex-direction:row;">
                        <a href="{{ route('admin.projects.choosetypeissues', $project->id) }}" class="btn  btn-edit btn-full-text">แจ้งปัญหา</a>
                        <a href="{{ route('admin.projects.confirmworkcompletedpage', $project->id) }}" class="btn btn-secondary btn-full-text">ยืนยันการทำงานเสร็จสิ้น</a>
                        @if (\Carbon\Carbon::parse($project->installation_start_date)->addDay()->isFuture())
                         <form action="{{ route('admin.projects.cancellinstalling', $project->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn btn-delecte btn-full-text" style="height: max-content;">
                                ยกเลิกการติกตั้ง
                            </button>
                        </form>
                        @endif
                    </div>
            @elseif($project->status == 'completed')
                        <form action="{{ route('admin.projects.updatestatusinstalling', $project->id) }}" method="POST" style="margin: 0;">
                        @csrf
                            <button class="btn btn-danger btn-full-text" style="height: max-content;">ยังติดตั้งไม่สำเร็จ</button>
                        </form>
            @endif
        </div>
            
    </div>

    <div style="margin-top: 20px;">
         @include('components.successanderror')
    </div>

   

    @if(in_array($project->status, $phase2))
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 20px;">
        <div   style="background-color: #ffffff; border: 1px solid #17a2b8; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h5 style="color: #17a2b8; margin: 0 0 5px 0;">ใบเสนอราคา</h5>
                <p style="margin: 0; font-size: 13px; color: #666;">รายละเอียดและยอดประเมิน</p>
            </div>
            <a href="{{ route('admin.projects.addbiddocument', $project->id) }}" class="btn btn-secondary btn-full-text" style="background:#17a2b8; border:none; font-size:12px;">เปิดเอกสาร</a>
        </div>
        @if(in_array($project->status, ['approved','material_planning', 'waiting_purchase', 'ready_to_withdraw', 'materials_withdrawn', 'installing', 'completed']))
        <div  style="background-color: #ffffff; border: 1px solid #28a745; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h5 style="color: #28a745; margin: 0 0 5px 0;">ใบเสร็จรับเงิน</h5>
                <p style="margin: 0; font-size: 13px; color: #666;">หลักฐานการชำระเงิน</p>
            </div>
            <a href="{{ route('admin.projects.receipt', $project->id) }}" class="btn btn-secondary btn-full-text" style="background:#28a745; border:none; font-size:12px;">เปิดเอกสาร</a>
        </div>
        <div  style="background-color: #ffffff; border: 1px solid #dc3545; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h5 style="color: #dc3545; margin: 0 0 5px 0;">ใบกำกับภาษี</h5>
                <p style="margin: 0; font-size: 13px; color: #666;">เอกสารภาษีมูลค่าเพิ่ม</p>
            </div>
            <a href="{{ route('admin.projects.taxInvoice', $project->id) }}" class="btn btn-secondary btn-full-text" style="background:#dc3545; border:none; font-size:12px;">เปิดเอกสาร</a>
        </div>
        @endif
        @if(in_array($project->status, ['material_planning', 'waiting_purchase', 'ready_to_withdraw', 'materials_withdrawn', 'installing', 'completed']))
        <div  style=" background-color: #f8f9fa; border: 1px solid #00CED1; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h5 style="color: #00CED1; margin: 0 0 5px 0;">ใบสั่งซื้อวัสดุ</h5>
                <p style="margin: 0; font-size: 13px; color: #666;">รายการวัสดุที่ต้องซื้อเพิ่ม</p>
            </div>
            <a href="{{ route('admin.projects.materialplanningpagedocument', $project->id) }}" class="btn btn-secondary btn-full-text" style="background:#00CED1; border:none; font-size:12px;">เปิดเอกสาร</a>
        </div>
        @endif
    </div>
    @endif

    @if(in_array($project->status, $phase1))
    <div class=" boxmaterial" style="margin-top: 20px;">
       <form action="{{ route('admin.projects.updateProjectPendingSurvey', $project->id) }}" method="POST" id="main-form" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <div  style="margin-top:20px; display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size: 18px; font-weight: bold;">1. ข้อมูลลูกค้าและนัดหมายสำรวจ</span>
            <a href="{{ route('admin.projects.projecteditcustomer', ['id' => $project->customer->id, 'project_id' => $project->id]) }}" class="btn btn-secondary">แก้ไขที่อยู่ลูกค้า</a>
        </div>
        
        <div class="box">
            <div class="box-control grid-2">
                <div class="form-group">
                    <label class="form-label">ชื่องาน <span style="color:red">*</span></label>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <select name="project_name_id" class="form-select" required>
                            <option value="">เลือกชื่องาน</option>
                            @foreach ($projectname as $pn)
                                <option value="{{ $pn->id }}" {{ $project->project_name_id == $pn->id ? 'selected' : '' }}>
                                    {{ $pn->name }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.projects.formprojectname') }}" target="_blank" class="btn-secondary" style="padding:8px 12px; font-size:12px; text-decoration:none; white-space:nowrap; ">+ เพิ่ม</a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">ลูกค้า <span style="color:red">*</span></label>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <select name="customer_id" class="form-select" required>
                            <option value="">เลือกชื่อลูกค้า</option>
                            @foreach ($customerall as $cm)
                                <option value="{{ $cm->id }}" {{ $project->customer_id == $cm->id ? 'selected' : '' }}>
                                    คุณ {{ $cm->first_name }} {{ $cm->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.projects.formnewcustomer') }}" target="_blank" class="btn-secondary" style="padding:8px 12px; font-size:12px; text-decoration:none; white-space:nowrap; border-radius:0;">+ เพิ่ม</a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">วันและเวลานัดสำรวจ <span style="color:red">*</span></label>
                    <input type="datetime-local" name="survey_date" id="survey_date" class="form-input" value="{{ $project->survey_date }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">ช่างสำรวจ <span style="color:red">*</span></label>
                    <select name="assigned_surveyor_id" id="assigned_surveyor_id" class="form-select" required>
                        <option value="">เลือกช่างที่จะไปสำรวจ</option>
                        @foreach ($technician as $tc)
                            <option value="{{ $tc->id }}" {{ $project->assigned_surveyor_id == $tc->id ? 'selected' : '' }}>
                                ช่าง {{ $tc->name }} {{ $tc->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ค่าแรงช่างสำรวจ <span style="color:red">*</span></label>
                    <input type="number" name="labor_cost_surveying" class="form-input" min="0" step="0.01" value="{{ $project->labor_cost_surveying }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">หมายเหตุ (ถ้ามี)</label>
                    <textarea name="note" class="form-input">{{ $project->note }}</textarea>
                </div>
            </div>
        </div>
        <hr>
        <div  style="margin-top:24px; display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size: 18px; font-weight: bold;">2. ค่าใช้จ่ายเพิ่มเติม</span>
            <button type="button" class="btn btn-secondary" id="btn-add-expense" >+ เพิ่มรายการ</button>
        </div>
        <div class="box">
            <div class="box-control">
                <div id="expense-header" style="display:none; grid-template-columns:2fr 1fr 1fr 2fr auto; gap:8px; font-size:12px; color:#999; padding:0 4px 6px; border-bottom:1px solid #eee;">
                    <span>รายการค่าใช้จ่าย *</span>
                    <span>จำนวนเงิน (บาท) *</span>
                    <span>วันที่ *</span>
                    <span>หมายเหตุ</span>
                    <span></span>
                </div>
                <div id="expense-rows"></div>
                <div id="expense-empty" style="text-align:center; color:#bbb; padding:20px 0; font-size:13px;">
                    ยังไม่มีรายการค่าใช้จ่าย — กดปุ่ม "+ เพิ่มรายการ" เพื่อเพิ่ม
                </div>
                <div id="expense-summary" style="display:none; justify-content:flex-end; padding-top:12px; border-top:1px solid #eee; margin-top:8px; font-weight:600;">
                    รวมค่าใช้จ่าย: <span id="expense-total" style="margin-left:8px; color:#E91E63;">0.00 บาท</span>
                </div>
            </div>
        </div>

        <hr>

        <div  style="margin-bottom: 20px; margin-top: 20px;">
        <div>
            <h3 class="card-title" style="font-size: 18px; font-weight: bold;">3. ประเมินหน้างานและค่าแรงติดตั้ง</h3>
        </div>
            <div>
                <div>
                    <label class="form-label">รูปภาพสถานที่หน้างาน</label>
                    <div class="img-preview-box" style="margin-top: 10px; height: 200px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border: 1px dashed #ccc; border-radius: 6px;">
                        @if($project->homeimg)
                            <img src="data:image/jpeg;base64,{{ base64_encode($project->homeimg) }}" style="max-height: 180px; max-width: 100%;">
                        @else
                            <span style="color: #aaa;">ยังไม่มีรูปภาพหน้างาน</span>
                        @endif
                    </div>
                    
                </div>
                <div class=" box-control">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <input type="file" name="homeimg" class="form-input" style="margin-top: 15px;" accept="image/*" >
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label">อัตราค่าแรงช่างติดตั้ง (ต่อวัน/ต่อคน)</label>
                        <input type="number" name="daily_labor_rate" class="form-input" value="{{ $project->daily_labor_rate ?? '' }}" min="0" step="0.01" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label">ประเมินจำนวนวันทำงาน</label>
                        <input type="number" name="estimated_work_days" class="form-input" value="{{ $project->estimated_work_days ?? '' }}" required>
                    </div>
                    
                </div>
            </div>
    </div>

        <div style="margin-top:24px; display:flex; justify-content:flex-end; gap:10px;">
            <button type="submit" class="btn btn-secondary" style="padding:10px 32px; font-size:15px;">บันทึกข้อมูล</button>
        </div>
    </form> 
    </div>
    
    @else

    <div class="boxmaterial customer-detail" style="margin-top: 20px;">
        <div  style="display: flex; justify-content :space-between; margin-bottom: 20px; border-bottom: solid 1px #000000;">
            <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">ข้อมูลลูกค้าและนัดหมายสำรวจ</h3>
        </div>

        <div class="detail-grid">
            <div>
                <span class="label">ชื่องาน</span>
                <span class="value">{{ $project->projectname->name ?? '-' }}</span>
            </div>
            <div>
                <span class="label">วันที่สำรวจ</span>
                <span class="value">{{ $project->survey_date ? \Carbon\Carbon::parse($project->survey_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY H:mm') : '-' }} น.</span>
            </div>
            <div>
                <span class="label">ช่างสำรวจ</span>
                <span class="value">ช่าง {{ $project->assignedSurveyor->name ?? '-' }}</span>
            </div>
            <div>
                <span class="label">ค่าแรงสำรวจ</span>
                <span class="value">{{ number_format($project->labor_cost_surveying, 2) }} บาท</span>
            </div>
            <div>
                <span class="label">ชื่อ-นามสกุล</span>
                <span class="value">
                    {{ $project->customer->prefix }} {{ $project->customer->first_name }} {{ $project->customer->last_name }}
                </span>
            </div>

            <div>
                <span class="label">เพศ</span>
                <span class="value">{{ $project->customer->gender }}</span>
            </div>

            <div>
                <span class="label">เบอร์โทร</span>
                <span class="value">{{ $project->customer->phone ?? '-' }}</span>
            </div>

    

            <div class="full">
                <span class="label">ที่อยู่</span>
                <span class="value">
                    เลขที่ {{ $project->customer->house_number ?? '-' }}
                    หมู่ {{ $project->customer->village ?? '-' }}
                    {{ $project->customer->house_name ?? '' }}
                    ซอย {{ $project->customer->alley ?? '-' }}
                    ถนน {{ $project->customer->road ?? '-' }} <br>
                    ต.{{ $project->customer->tambon->name_th ?? '-' }}
                    อ.{{ $project->customer->amphure->name_th ?? '-' }}
                    จ.{{ $project->customer->province->name_th ?? '-' }}
                    {{ $project->customer->tambon->zip_code ?? '-' }}
                </span>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <div class=" boxmaterial">
            <h3 class="card-title" style="font-size: 18px; font-weight: bold;">ค่าใช้จ่ายเพิ่มเติมของโครงการ</h3>
        </div>
        <table class="table-modern" style="margin-bottom: 0;">
            <tr align="center">
                <th >รายการที่</th>
                <th>รายการ</th>
                <th>รายละเอียด</th>
                <th>วันที่</th>
                <th>จำนวนเงิน</th>
            </tr>
            @forelse($project->projectexpenses as $expense)
            <tr align="center">
                <td >{{ $loop->iteration  }}</td>
                <td>{{ $expense->type->name ?? '-' }}</td>
                <td>{{ $expense->description ?? '-' }}</td>
                <td>{{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : '-' }}</td>
                <td style=" font-weight: 600;">{{ number_format($expense->amount, 2) }} ฿</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center; color:#999; padding:20px;">ไม่มีค่าใช้จ่ายเพิ่มเติม</td></tr>
            @endforelse
        </table>
    </div>
    
    <div class=" boxmaterial"  style="margin-bottom: 20px; margin-top: 20px;" >
        <div>
            <h3 class="card-title" style="font-size: 18px; font-weight: bold;">ประเมินหน้างานและค่าแรงติดตั้ง</h3>
            <div style="border-bottom: solid 1px #000000; margin-top:20px;"></div>
        </div>
        <div  style="padding: 15px;">
            <div>
                <div style="font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #555;">รูปภาพสถานที่หน้างาน</div>
                <div class="img-preview-box" style="height: 200px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border: 1px solid #ddd;">
                    @if($project->homeimg)
                        <img src="data:image/jpeg;base64,{{ base64_encode($project->homeimg) }}" style="max-height: 180px; max-width: 100%;">
                    @else
                        <span style="color: #aaa;">ไม่มีรูปภาพ</span>
                    @endif
                </div>
            </div>


            <div style="margin-top: 10px;">
                <span class="label">อัตราค่าแรงช่างติดตั้ง (ต่อวัน/คน)</span>
                <span class="value">{{ number_format($project->daily_labor_rate, 2) }} ฿</span>
            </div>

            <div>
                <span class="label">ประเมินจำนวนวันทำงาน</span>
                <span class="value">{{ $project->estimated_work_days ?? '-' }} วัน</span>
            </div>

        </div>
    </div>
    
    <div class=" boxmaterial"   style="margin-bottom: 20px;">
        <div >
            <h3  style="font-size: 18px; font-weight: bold;">รูปภาพหน้างานเพิ่มเติม</h3>
            <div style="border-bottom: solid 1px #000000; margin-top:20px;  margin-bottom: 20px;"></div>
        </div>
        @if($project->projectimage->count() > 0)
            <table class="table-modern" style="text-align:center; margin-bottom: 0;">
                <tr>
                    <th width="5%">ที่</th>
                    <th>รูปภาพ</th>
                    <th>ตำแหน่ง</th>
                    <th>รายละเอียด</th>
                </tr>
                @foreach($project->projectimage as $image)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><img src="data:image/jpeg;base64,{{ base64_encode($image->image_path) }}" style="max-height: 80px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></td>
                    <td>{{ $image->imagetype->name ?? '-' }}</td>
                    <td>{{ $image->description ?? '-' }}</td>
                </tr>
                @endforeach
            </table>
        @else
            <p style="text-align:center; color:#999; padding:20px; margin: 0;">ยังไม่มีรูปภาพเพิ่มเติม</p>
        @endif
    </div>
    
    <div  style="margin-bottom: 20px;">
        <div >
            <h3 class="card-title" style="font-size: 18px; font-weight: bold;">ความต้องการของลูกค้า (ชุดผลิตภัณฑ์)</h3>
            <div style="border-bottom: solid 1px #000000; margin-top:20px;  margin-bottom: 20px;"></div>
        </div>
        @if($project->customerneed->count() > 0)
        <table class="table-modern" style="margin-bottom: 0;">
            <tr style="text-align:center;">
                    <th width="5%">ที่</th>
                    <th>ชุดรายการ</th>
                    <th>รูปภาพ</th>
                    <th>ตำแหน่ง</th>
                    <th>ขนาด (ก×ส)</th>
                    <th>จำนวน</th>
                    <th>หมายเหตุ</th>
            </tr>
            @foreach($project->customerneed as $cn)
                <tr style="text-align:center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $cn->productset->productSetName->name ?? '-' }}</td>
                    <td>
                        @if(isset($cn->productset->product_image))
                            <img src="data:image/jpeg;base64,{{ base64_encode($cn->productset->product_image) }}" style="max-height: 60px; border-radius: 4px;">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $cn->projectImage->imagetype->name ?? '-' }}</td>
                    <td>{{ $cn->width }} × {{ $cn->height }} ซม.</td>
                    <td>{{ $cn->quantity }} ชุด</td>
                    <td>{{ $cn->note_need ?? '-' }}</td>
                </tr>
                @endforeach
        </table>
        @else
        <p style="text-align:center; color:#999; padding:20px 0; margin:0;">ยังไม่ได้ระบุชุดผลิตภัณฑ์</p>
        @endif
    </div>

    <div class=" boxmaterial" style="margin-bottom: 20px;">
        <div >
            <div >
                <h3  style="font-size: 18px; font-weight: bold;">กำหนดการติดตั้ง</h3>
                <div style="border-bottom: solid 1px #000000; margin-top:20px; "></div>
            </div>
            <div style="padding: 15px; margin-bottom:10px;" class=" box-control">
                <div style="margin-top: 10px;">
                <span class="label">วันเริ่มงาน</span>
                <span class="value">{{ $project->installation_start_date ? \Carbon\Carbon::parse($project->installation_start_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : '-' }}</span>
                </div>
                <div style="margin-top: 10px;">
                    <span class="label">วันจบงาน</span>
                    <span class="value">{{ $project->installation_end_date ? \Carbon\Carbon::parse($project->installation_end_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : '-' }}</span>
                </div>
                <div style="margin-top: 10px;">
                    <span class="label">ระยะเวลาประเมินไว้</span>
                    <span class="value">{{ $project->estimated_work_days ?? '-' }} วัน</span>
                </div>
            </div>
        </div>

        <div>
            <div >
                <h3 class="card-title" style="font-size: 18px; font-weight: bold;">ช่างติดตั้ง</h3>
                 <div style="border-bottom: solid 1px #000000; margin-top:20px;  margin-bottom: 20px;"></div>
            </div>
            <table class="table-modern" style="margin-bottom: 0;">
                <tr align="center">
                    <th width="15%" style="text-align: center;">ลำดับ</th>
                    <th>ชื่อ - สกุล</th>
                    <th>เบอร์โทร</th>
                </tr>
                @forelse($project->installers as $installer)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td align="center">ช่าง {{ $installer->name }} {{ $installer->last_name }}</td>
                    <td align="center">{{ $installer->phone_number }}</td>
                </tr>
                @empty
                <tr><td colspan="2" style="text-align: center; color:#999; padding: 15px;">ยังไม่ได้จัดทีมช่าง</td></tr>
                @endforelse
            </table>
        </div>
    </div>
    
    @endif


    @if(in_array($project->status, $phase1))
    <div class=" boxmaterial" style="margin-bottom: 20px; margin-top: 20px;" >
        <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
            <h3  style="font-size: 18px; font-weight: bold;">4. รูปภาพหน้างาน</h3>
            @if(in_array($project->status, $phase1))
            <a href="{{ route('admin.projects.formprojectimagedetail', $project->id) }}" class="btn btn-secondary">เพิ่มรูปภาพ</a>
            @endif
        </div>
        @if($project->projectimage->count() > 0)
            <table style="text-align:center;">
                <tr>
                    <th>ที่</th>
                    <th>รูปภาพ</th>
                    <th>ตำแหน่ง</th>
                    <th>รายละเอียด</th>
                    <th>จัดการ</th>
                </tr>
                @foreach($project->projectimage as $image)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><img src="data:image/jpeg;base64,{{ base64_encode($image->image_path) }}" class="project-image1"></td>
                    <td>{{ $image->imagetype->name }}</td>
                    <td>{{ $image->description ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.projects.formeditprojectimage', $image->id) }}" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.projects.deleteprojectimage', $image->id) }}" method="post" style="display:inline;">
                            @method('DELETE') @csrf
                            <button type="submit" class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        @else
            <p style="text-align:center; color:#999; padding:15px;">ยังไม่มีรูปภาพ</p>
        @endif
        <hr style="margin-top: 50px; margin-bottom: 30px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px; margin-bottom: 20px;">
            <h3  style="font-size: 18px; font-weight: bold;">5. ความต้องการของลูกค้า (ชุดผลิตภัณฑ์)</h3>
            @if(in_array($project->status, $phase1))
            <a href="{{ route('admin.projects.formcustomerneeddetial', $project->id) }}" class="btn btn-secondary" >+ เพิ่มความต้องการ</a>
            @endif
        </div>
        @if($project->customerneed->count() > 0)
        <table class="table-modern">
            <tr style="text-align:center;">
                    <th>ที่</th>
                    <th>ชุดรายการ</th>
                    <th>รูปภาพ</th>
                    <th>ตำแหน่ง</th>
                    <th>ขนาด (กว้าง×สูง)</th>
                    <th>จำนวน</th>
                    <th>หมายเหตุ</th>
                    <th>จัดการ</th>
            </tr>
            @foreach($project->customerneed as $cn)
                <tr style="text-align:center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $cn->productset->productSetName->name }}</td>
                    <td><img src="data:image/jpeg;base64,{{ base64_encode($cn->productset->product_image) }}" class="project-image1"></td>
                    <td>{{ $cn->projectImage->imagetype->name }}</td>
                    <td>{{ $cn->width }} × {{ $cn->height }} ซม.</td>
                    <td>{{ $cn->quantity }} ชุด</td>
                    <td>{{ $cn->note_need ?? 'ไม่มี' }}</td>
                    <td>
                        <a href="{{ route('admin.projects.editformcustomerneed', $cn->id) }}" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.projects.deletecustomerneed', $cn->id) }}" method="post" style="display:inline;">
                            @method('DELETE') @csrf
                            <button type="submit" class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
        </table>
        @else
        <p style="text-align:center; color:#999; padding:20px 0; margin:0;">ยังไม่ได้ระบุชุดผลิตภัณฑ์</p>
        @endif
    </div>

   <div class=" boxmaterial" style="margin-top: 20px;">
    <div >
        <div >
            <h3  style="font-size: 18px; font-weight: bold;">6.กำหนดวันติดตั้ง</h3>
        </div>
        <form method="POST" action="{{ route('admin.projects.assignInstaller', $project->id) }}">
            @csrf @method('PUT')
            <div class=" box-control">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label class="form-label">วันเริ่มงาน (ประเมินไว้ {{ $project->estimated_work_days ?? 0 }} วัน)</label>
                    <input type="date" name="installation_start_date" id="installation_start_date" class="form-input" value="{{ $project->installation_start_date }}" required>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label class="form-label">วันจบงาน</label>
                    <input type="date" class="form-input" id="installation_end_date" value="{{ $project->installation_end_date }}" readonly style="background: #f8f9fa;">
                </div>
                <div class="form-group">
                    <button class="btn btn-secondary" >บันทึกกำหนดการ</button>
                </div>
                
            </div>
            
            
        </form>
    </div>

    <div>
        <div style="margin-top: 20px; margin-bottom: 20px;">
            <h3 style="font-size: 18px; font-weight: bold;">ช่างติดตั้ง</h3>
        </div>
        <form action="{{ route('admin.projects.assignInstalleruser', $project->id) }}" method="POST" style="margin-bottom: 15px; display: flex; gap: 10px;">
            @csrf
            <select name="user_id" id="installer_select" class="form-select" required>
                <option value="">เลือกช่างติดตั้ง</option>
                @foreach($technician as $installer)
                <option value="{{ $installer->id }}">ช่าง {{ $installer->name }} {{ $installer->last_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary" style="white-space: nowrap;">+ เพิ่มช่าง</button>
        </form>
        <table class="table-modern">
            <tr>
                <th width="5%">ลำดับ</th>
                <th>ชื่อ - สกุล</th>
                <th width="10%" style="text-align: center;">จัดการ</th>
            </tr>
            @forelse($project->installers as $installer)
            <tr>
                <td >
                    {{ $loop->iteration }}
                </td>
                <td>ช่าง {{ $installer->name }} {{ $installer->last_name }}</td>
                <td style="text-align: center;">
                    <form action="{{ route('admin.projects.removeinstaller', $installer->pivot->id ?? $installer->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" onclick="return confirm('ลบช่างคนนี้ออกจากทีม?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align: center; color:#999;">ยังไม่ได้เพิ่มช่าง</td></tr>
            @endforelse
        </table>
    </div>
    </div>
    
    <!-- กล่อง CTA สำหรับออกใบเสนอราคา (จะแสดงและพร้อมใช้เมื่อข้อมูลครบ) -->
    <div class="boxmaterial" style="margin-top: 30px; text-align: center; padding: 30px; background: {{ $isReadyForQuotation ? '#f0fdf4' : '#fff5f5' }}; border: 1px solid {{ $isReadyForQuotation ? '#bbf7d0' : '#fecaca' }};">
        <h3 style="font-size: 20px; font-weight: bold; color: {{ $isReadyForQuotation ? '#166534' : '#991b1b' }};">ขั้นตอนต่อไป: ออกใบเสนอราคา</h3>
        
        @if($isReadyForQuotation)
            <p style="color: #15803d; margin-bottom: 20px;">ข้อมูลครบถ้วนแล้ว คุณสามารถดำเนินการออกใบเสนอราคาได้</p>
            <a href="{{ route('admin.projects.addbid', $project->id) }}" class="btn btn-secondary">เสนอราคา</a>
        @else
            <p style="color: #b91c1c; margin-bottom: 15px;">กรุณากรอกข้อมูลต่อไปนี้ให้ครบถ้วนก่อนออกใบเสนอราคา:</p>
            <ul style="list-style: none; padding: 0; color: #dc2626; margin-bottom: 20px; display: inline-block; text-align: left;">
                @foreach($missingInfo as $info)
                    <li><i class="fas fa-times-circle" style="margin-right: 8px;"></i> {{ $info }}</li>
                @endforeach
            </ul><br>
            <button disabled class="btn btn-secondary" style="font-size: 18px; padding: 12px 40px; cursor: not-allowed; opacity: 0.6;">ไปหน้าออกใบเสนอราคา</button>
        @endif
    </div>

    @endif

</div>

@if(in_array($project->status, $phase1))
<template id="expense-row-template">
    <div class="expense-row" style="display:grid; grid-template-columns:2fr 1fr 1fr 2fr auto; gap:8px; align-items:start; margin-bottom:8px;">
        <div style="display:flex; align-items:center; gap:6px;">
            <select name="expenses[__INDEX__][expense_type_id]" class="form-select" required>
                <option value="">เลือกรายการค่าใช้จ่าย</option>
                @if(isset($expense))
                    @foreach ($expense as $et)
                        <option value="{{ $et->id }}">{{ $et->name }}</option>
                    @endforeach
                @endif
            </select>
            <a href="{{ route('admin.projects.formexpensetype') }}?from=create" target="_blank" class="btn-secondary" style="padding:8px 10px; font-size:12px; text-decoration:none; white-space:nowrap; border-radius:0; flex-shrink:0;">+ เพิ่ม</a>
        </div>
        <input type="number" name="expenses[__INDEX__][amount]" class="form-input expense-amount" placeholder="0.00" min="0" step="0.01" required>
        <input type="date" name="expenses[__INDEX__][expense_date]" class="form-input expense-date" required>
        <input type="text" name="expenses[__INDEX__][description]" class="form-input" placeholder="หมายเหตุ (ถ้ามี)">
        <button type="button" class="btn-icon btn-delete" onclick="removeExpenseRow(this)" title="ลบ"><i class="fas fa-trash"></i></button>
    </div>
</template>
@endif

<script>
    const startInput = document.querySelector('input[name="installation_start_date"]');
    if (startInput) {
        startInput.addEventListener('change', function() {
            if (!this.value) { document.getElementById('installation_end_date').value = ''; return; }
            const startDate = new Date(this.value);
            const workDays  = parseInt("{{ $project->estimated_work_days ?? 1 }}", 10);
            startDate.setDate(startDate.getDate() + (workDays > 0 ? workDays - 1 : 0));
            const yyyy = startDate.getFullYear();
            const mm   = String(startDate.getMonth() + 1).padStart(2, '0');
            const dd   = String(startDate.getDate()).padStart(2, '0');
            document.getElementById('installation_end_date').value = `${yyyy}-${mm}-${dd}`;
        });
    }

    @if(in_array($project->status, $phase1))
    (function () {
        'use strict';

        var rowsEl    = document.getElementById('expense-rows');
        var emptyEl   = document.getElementById('expense-empty');
        var summaryEl = document.getElementById('expense-summary');
        var headerEl  = document.getElementById('expense-header');
        var totalEl   = document.getElementById('expense-total');
        var tmpl      = document.getElementById('expense-row-template');
        var btnAdd    = document.getElementById('btn-add-expense');
        
        var surveyInput = document.getElementById('survey_date');
        var surveyorSelect = document.getElementById('assigned_surveyor_id');

        var expenseIndex = 0;
        var allSchedules = @json($schedules ?? []);
        
        var existingExpenses = @json($project->projectexpenses ?? []);

        function updateTechnicianOptions() {
            if (!surveyInput || !surveyorSelect) return;
            var dateVal = surveyInput.value;
            if (!dateVal) return;

            var targetDate = dateVal.split('T')[0];
            var currentSelected = surveyorSelect.value;
            var shouldClearSelection = false;

            Array.from(surveyorSelect.options).forEach(function(opt) {
                if (opt.value === "") return;

                var techId = opt.value;
                var isBusy = allSchedules.some(function(sch) {
                    return sch.tech_id === techId && sch.date === targetDate;
                });

                var originalText = opt.text.replace(/ \(ติดงาน\)/g, '');

                if (isBusy) {
                    opt.text = originalText + " (ติดงาน)";
                    opt.disabled = true;
                    opt.style.color = '#999'; 
                    opt.style.backgroundColor = '#f1f1f1'; 
                    if (currentSelected === techId) {
                        shouldClearSelection = true;
                    }
                } else {
                    opt.text = originalText;
                    opt.disabled = false;
                    opt.style.color = '';
                    opt.style.backgroundColor = '';
                }
            });

            if (shouldClearSelection) {
                surveyorSelect.value = "";
            }
        }

        function getSurveyDateOnly() {
            var val = surveyInput.value;
            if (!val) return '';
            return val.split('T')[0];
        }

        function addExpenseRow(values) {
            var html = tmpl.innerHTML.replaceAll('__INDEX__', expenseIndex++);
            var wrap = document.createElement('div');
            wrap.innerHTML = html;
            var row = wrap.firstElementChild;

            var dateInput = row.querySelector('.expense-date');

            if (values && values.expense_date) {
                dateInput.value = values.expense_date.split('T')[0].split(' ')[0];
            } else {
                dateInput.value = getSurveyDateOnly();
            }

            row.querySelectorAll('input, select').forEach(function (el) {
                el.addEventListener('input',  recalcTotal);
                el.addEventListener('change', recalcTotal);
            });

            if (values) {
                row.querySelector('select').value             = values.expense_type_id || '';
                row.querySelector('.expense-amount').value    = values.amount          || '';
                row.querySelector('input[type="text"]').value = values.description     || '';
            }

            rowsEl.appendChild(row);
            updateVisibility();
            recalcTotal();
        }

        window.removeExpenseRow = function(btn) {
            btn.closest('.expense-row').remove();
            updateVisibility();
            recalcTotal();
        };

        function updateVisibility() {
            var has = rowsEl.children.length > 0;
            emptyEl.style.display   = has ? 'none'  : 'block';
            summaryEl.style.display = has ? 'flex'  : 'none';
            headerEl.style.display  = has ? 'grid'  : 'none';
        }

        function recalcTotal() {
            var sum = 0;
            rowsEl.querySelectorAll('.expense-amount').forEach(function (inp) {
                sum += parseFloat(inp.value) || 0;
            });
            totalEl.textContent = sum.toLocaleString('th-TH', { minimumFractionDigits: 2 }) + ' บาท';
        }

        surveyInput.addEventListener('change', function() {
            var newDate = getSurveyDateOnly();
            if (newDate) {
                rowsEl.querySelectorAll('.expense-date').forEach(function(dateInp) {
                    if (!dateInp.value) {
                        dateInp.value = newDate;
                    }
                });
            }
            updateTechnicianOptions();
        });

        btnAdd.addEventListener('click', function () {
            addExpenseRow(null);
        });

        document.addEventListener('DOMContentLoaded', function() {
            updateTechnicianOptions(); 
            
            if(existingExpenses.length > 0) {
                existingExpenses.forEach(function(exp) {
                    addExpenseRow({
                        expense_type_id: exp.expense_type_id,
                        amount: exp.amount,
                        expense_date: exp.expense_date,
                        description: exp.description
                    });
                });
            }
        });

    })();
    @endif

    document.addEventListener('DOMContentLoaded', function() {
        const estimatedDays = parseInt("{{ $project->estimated_work_days ?? 1 }}", 10);
        const allSchedules = @json($schedules ?? []); 

        const startDateInput = document.getElementById('installation_start_date');
        const endDateInput = document.getElementById('installation_end_date');
        const installerSelect = document.getElementById('installer_select');

        function updateInstallerOptions() {
            if (!startDateInput) return;
            if (!startDateInput.value) return;

            let startDate = new Date(startDateInput.value);
            let dateRange = [];
            for (let i = 0; i < estimatedDays; i++) {
                let d = new Date(startDate);
                d.setDate(d.getDate() + i);
                let yyyy = d.getFullYear();
                let mm = String(d.getMonth() + 1).padStart(2, '0');
                let dd = String(d.getDate()).padStart(2, '0');
                dateRange.push(`${yyyy}-${mm}-${dd}`);
            }

            if(installerSelect) {
                let currentSelected = installerSelect.value;
                let shouldClearSelection = false;

                Array.from(installerSelect.options).forEach(opt => {
                    if (!opt.value) return;

                    let techId = opt.value;
                    let isBusy = allSchedules.some(sch => sch.tech_id == techId && dateRange.includes(sch.date));

                    let originalText = opt.text.replace(/ \(ติดงาน\)/g, '');

                    if (isBusy) {
                        opt.text = originalText + " (ติดงาน)";
                        opt.disabled = true;
                        opt.style.color = '#999';
                        opt.style.backgroundColor = '#f1f1f1';
                        if (currentSelected === techId) {
                            shouldClearSelection = true;
                        }
                    } else {
                        opt.text = originalText;
                        opt.disabled = false;
                        opt.style.color = '';
                        opt.style.backgroundColor = '';
                    }
                });

                if (shouldClearSelection) {
                    installerSelect.value = "";
                }
            }
        }

        if (startDateInput) {
            startDateInput.addEventListener('change', function() {
                if (this.value) {
                    let startDate = new Date(this.value);
                    startDate.setDate(startDate.getDate() + (estimatedDays > 0 ? estimatedDays - 1 : 0));
                    
                    let yyyy = startDate.getFullYear();
                    let mm = String(startDate.getMonth() + 1).padStart(2, '0');
                    let dd = String(startDate.getDate()).padStart(2, '0');
                    
                    endDateInput.value = `${yyyy}-${mm}-${dd}`;
                } else {
                    endDateInput.value = '';
                }
                updateInstallerOptions();
            });
        }
        updateInstallerOptions();
    });
</script>
@endsection