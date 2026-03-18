@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="boxmaterial control-section" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3>รายละเอียดงาน: {{ $project->projectname?->name }}</h3>
            <div style="width: fit-content; height: fit-content;">
                @if ($project->status == 'pending_survey')
                <div style="background-color: #D4AF37;" class="boxstatusproject">
                    นัดสำรวจ
                </div>
                @elseif ($project->status == 'waiting_survey')
                <div style=" background-color: #FF8C00;" class="boxstatusproject">
                    รอวันสำรวจ
                </div>
                @elseif ($project->status == 'surveying')
                <div style=" background-color: #1E90FF;" class="boxstatusproject">
                    กำลังสำรวจ
                </div>
                @elseif ($project->status == 'pending_quotation')
                <div style="background-color: #E91E63;" class="boxstatusproject">
                    รอเสนอราคา
                </div>
                @elseif ($project->status == 'waiting_approval')
                <div style="background-color: #9C27B0;" class="boxstatusproject">
                    รออนุมัติ
                </div>
                @elseif ($project->status == 'approved')
                <div style="background-color: #78d37b;" class="boxstatusproject">
                    อนุมัติและชำระเงินแล้ว
                </div>
                @elseif ($project->status == 'material_planning')
                <div style="background-color: #00CED1;" class="boxstatusproject">
                    วางแผนวัสดุ
                </div>
                @elseif ($project->status == 'waiting_purchase')
                <div style="background-color: #FF4500;" class="boxstatusproject">
                    รอสั่งซื้อ
                </div>
                @elseif ($project->status == 'ready_to_withdraw')
                <div style="background-color: #008080;" class="boxstatusproject">
                    พร้อมเบิก
                </div>
                @elseif ($project->status == 'materials_withdrawn')
                <div style="background-color: #8B4513;" class="boxstatusproject">
                    เบิกวัสดุแล้ว
                </div>
                @elseif ($project->status == 'installing')
                <div style="background-color: #4CAF50;" class="boxstatusproject">
                    กำลังติดตั้ง
                </div>
                @elseif ($project->status == 'completed')
                <div style="background-color: #708090;" class="boxstatusproject">
                    เสร็จสิ้น
                </div>
                @elseif ($project->status == 'cancelled')
                <div style="background-color: #DC143C;" class="boxstatusproject">
                    ยกเลิก
                </div>
                @endif
            </div>
        </div>
        <div>
            <a href="{{ route('admin.projects.index',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>

    </div>
    <div style=" margin-top: 20px;">
        @include('components.successanderror')
    </div>
    <div class="boxmaterial" style="margin-top: 20px;">
        <div style="margin-bottom: 15px;  border-bottom: 2px solid #eee; padding-bottom: 10px;">
            จัดการเอกสารโครงการ
        </div>
        
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            
            @if (in_array($project->status, $satatusopen))
            <div style="flex: 1; min-width: 250px; border: 1px solid #61b8c2;  padding: 15px;  display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h5>ใบเสนอราคา</h5>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">รายละเอียดและยอดประเมิน</p>
                </div>
                <a href="{{ route('admin.projects.addbiddocument', $project->id) }}" class="btn btn-secondary" style="height:max-content; background-color: #17a2b8; border: none; padding: 8px 15px; ">
                    เปิดเอกสาร
                </a>
            </div>
            
            @endif


            @if (in_array($project->status,$statusmaterialplanningopen) && $project->project_purchases != null)
            <div style="flex: 1; min-width: 250px; border: 1px solid #61b8c2;  padding: 15px;  display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h5>ใบสั่งซื้อวัสดุ</h5>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">รายการวัสดุที่ต้องซื้อเพิ่ม</p>
                </div>
                <a href="{{ route('admin.projects.materialplanningpagedocument', $project->id) }}" class="btn btn-secondary" style="height:max-content; background-color: #00CED1; border: none; padding: 8px 15px; ">
                    เปิดเอกสาร
                </a>
            </div>
            @endif

            @if (in_array($project->status, $statuspayment))
            <div style="flex: 1; min-width: 250px; border: 1px solid #28a745;  padding: 15px;  display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h5>ใบเสร็จรับเงิน</h5>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">หลักฐานการชำระเงิน</p>
                </div>
                <a href="{{ route('admin.projects.receipt', $project->id) }}" class="btn btn-secondary" style="height:max-content; background-color: #28a745; border: none; padding: 8px 15px; ">
                    เปิดเอกสาร
                </a>
            </div>

            <div style="flex: 1; min-width: 250px; border: 1px solid #dc3545;  padding: 15px;  display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h5>ใบกำกับภาษี</h5>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">เอกสารภาษีมูลค่าเพิ่ม (VAT)</p>
                </div>
                <a href="{{ route('admin.projects.taxInvoice', $project->id) }}" class="btn btn-secondary" style=" height:max-content; background-color: #dc3545; border: none; padding: 8px 15px; ">
                    เปิดเอกสาร
                </a>
            </div>
            @endif

            @if (!in_array($project->status, $satatusopen) && !in_array($project->status, $statuspayment) && !in_array($project->status, $statusmaterialplanningopen))
            <div style="width: 100%; text-align: center; padding: 20px; ">
                โปรเจกต์นี้ยังไม่อยู่ในขั้นตอนการออกเอกสาร (กรุณาอัปเดตสถานะการสำรวจ)
            </div>
            @endif

        </div>
    </div>
    @if (in_array($project->status,$satatuswaiting))
    <div class="boxmaterial" style="margin-top: 20px;">
        <form action="{{ route('admin.projects.updateProjectPendingSurvey', $project->id) }}" method="POST">
            @csrf
            @method('PUT')
            ข้อมูลงาน
            <div class="box-control" style="margin-bottom: 50px;">
                <div class="form-group">
                    <label class="form-label">ชื่องาน</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <select name="project_name_id" class="form-input" required>
                            <option value="">เลือกชื่องาน</option>
                            @foreach ($projectname as $pn)
                            <option value="{{ $pn->id }}" {{ $project->project_name_id == $pn->id ? 'selected' : '' }}>
                                {{ $pn->name }}
                            </option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.projects.formprojectname') }}" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0;">
                            จัดการ
                        </a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">ชื่อ-นามสกุล ลูกค้า</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <select name="customer_id" class="form-input" required>
                            <option value="">เลือกลูกค้า</option>
                            @foreach ($customerall as $ca)
                            <option value="{{ $ca->id }}" {{ $project->customer_id == $ca->id ? 'selected' : '' }}>
                                {{ $ca->first_name .'  '. $ca->last_name }}
                            </option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.projects.formnewcustomer') }}" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0;">
                            จัดการ
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">วันและเวลานัดสำรวจ</label>
                    <input type="datetime-local" name="survey_date" class="form-input" value="{{ $project->survey_date }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">เลือกช่างที่จะไปสำรวจ</label>
                    <select name="assigned_surveyor_id" class="form-select" required>
                        <option value="">เลือกช่างที่จะไปสำรวจ</option>
                        @foreach ($technician as $tc)
                        <option value="{{ $tc->id }}" {{ $project->assigned_surveyor_id == $tc->id ? 'selected' : '' }}>
                            {{' ช่าง '.$tc->name }} {{ $tc->last_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ค่าแรงช่างที่จะไปสำรวจ</label>
                    <input type="number" name="labor_cost_surveying" class="form-input" value="{{ $project->labor_cost_surveying }}" min="0" step="0.01" required>
                </div>

                <div class="form-group">
                    <label class="form-label">หมายเหตุหรือความต้องการเบื้องต้น (ถ้ามี)</label>
                    <textarea name="note" class="form-input">{{ $project->note }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-secondary">แก้ไขข้อมูล</button>
            </div>
        </form>
    </div>
    <div class="boxmaterial" style="margin-top: 20px;">
        <div style=" margin-top: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            ข้อมูลค่าใช้จ่ายเพิ่มเติม
            <a href="{{ route('admin.projects.formdetialexpense',$project->id) }}" class="btn btn-secondary">เพิ่มค่าใช้จ่าย</a>
        </div>
        <table style="text-align: center;">
            <tr>
                <th>รายการที่</th>
                <th>รายการค่าใช้จ่าย</th>
                <th>ค่าใช้จ่าย</th>
                <th>วันที่ใช้จ่าย</th>
                <th>รายละเอียด</th>
                <th>ผู้เพิ่ม</th>
                <th>จัดการ</th>
            </tr>
            @foreach ($project->projectexpenses as $expense)
            <tr>

                <td>{{ $loop->iteration }}</td>
                <td>{{ $expense->type->name }}</td>
                <td>{{number_format($expense->amount,2).' บาท'  }}</td>
                <td>
                    {{ $expense->expense_date
                        ? \Carbon\Carbon::parse($expense->expense_date)
                        ->locale('th') 
                        ->addYears(543) 
                        ->isoFormat('D MMMM YYYY') 
                        : 'ยังไม่ได้กำหนดวันทำงาน' 
                    }}
                </td>
                <td>{{ $expense->description ?? '-' }}</td>
                <td>{{ $expense->creator->name }}</td>

                <td>
                    <a href="{{ route('admin.projects.formeditdetialexpense',$expense->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                        <i class="fas fa-edit "></i>
                    </a>

                    <form action="{{ route('admin.projects.deletedetialexpense', $expense->id)  }}" method="POST"
                        style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" title="ลบ">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>

    </div>


    <div class="boxmaterial" style="margin-top: 20px;">
        <div style=" margin-bottom: 20px;">
            ข้อมูลการสำรวจหน้างานและความต้องการของลูกค้า
        </div>
        <form action="{{ route('admin.projects.addautersurver') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $project->id ?? '' }}">

            <div style="display: flex;  margin-bottom: 20px; width: 100%;">
                <div class="boxmaterial">
                    <div style="display: flex; flex-direction: column;">
                        <div class="image-preview">
                            @if(isset($project) && $project->homeimg)
                            <img src="data:image/jpeg;base64,{{ base64_encode($project->homeimg) }}" alt="Project Image">
                            @else
                            <div style=" text-align: center; margin-top:50px;">
                                <span>ไม่มีรูปภาพ</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="boxmaterial" style="width: 800px;">
                    <div class="form-group">
                        <label for="homeimg" class="form-label">รูปภาพบ้าน</label>
                        <input type="file" name="homeimg" id="homeimg" class="form-input" accept="image/*">

                        <label for="daily_labor_rate" class="form-label" style="margin-top: 10px;">อัตราค่าแรงต่อวัน</label>
                        <input type="number" name="daily_labor_rate" id="daily_labor_rate" class="form-input" value="{{ $project->daily_labor_rate ?? '' }}" min="0" step="0.01" required>

                        <label for="estimated_work_days" class="form-label" style="margin-top: 10px;">จำนวนวันทำงานที่ประเมิน</label>
                        <input type="number" name="estimated_work_days" id="estimated_work_days" class="form-input" value="{{ $project->estimated_work_days ?? '' }}" required>

                        <div style="display: flex; flex-direction: row-reverse; margin-top: 20px;">
                            <button type="submit" class="btn btn-secondary">อัพเดทข้อมูล</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="boxmaterial" style="display: flex; justify-content: space-between;">
            รูปภาพหน้างาน
            <a href="{{ route('admin.projects.formprojectimagedetail',$project->id) }}" class="btn btn-secondary">เพิ่มรูปภาพงาน</a>
        </div>
        @if ($project->projectimage->count() > 0)

        <table style="text-align: center;">
            <tr>
                <th>ภาพที่</th>
                <th>รูปภาพ</th>
                <th>ตำแหน่งที่จะติดตั้ง</th>
                <th>รายละเอียดภาพ </th>
                <th>จัดการ</th>
            </tr>
            @foreach ($project->projectimage as $image)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><img src="data:image/jpeg;base64,{{ base64_encode($image->image_path) }}" class="project-image1"></td>
                <td>{{ $image->imagetype->name }}</td>
                <td>{{ $image->description ?? 'ไม่มีรายละเอียดภาพ' }}</td>
                <td>
                    <div style="display: flex; justify-content: center; align-items: center; gap: 5px;">
                        <a href="{{ route('admin.projects.formeditprojectimage',$image->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                            <i class="fas fa-edit "></i>
                        </a>
                        <form action="{{ route('admin.projects.deleteprojectimage',$image->id) }}" method="post">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>

        @else
        <div class="box">
            <center>
                <p>รูปภาพยังไม่ถูกเพิ่ม</p>
            </center>
        </div>
        @endif
       
        <div style="margin-top: 20px;">
            <div class="boxmaterial" style="margin-top: 20px; display: flex; justify-content: space-between;">
                ความต้องการของลูกค้า
                <a href="{{ route('admin.projects.formcustomerneeddetial',$project->id) }}" class="btn btn-secondary">เพิ่มความต้องการของลูกค้า</a>
            </div>

            <div>
                 @if ($project->customerneed->count() > 0)
                <table>
                    <tr style="text-align: center;">
                        <th>รายการที่</th>
                        <th>ชุดรายการที่เลือก</th>
                        <th>รูปภาพ</th>
                        <th>ตำแหน่งที่จะติดตั้ง</th>
                        <th>ขนาด (กว้าง * สูง)</th>
                        <th>จำนวน</th>
                        <th>หมายเหตุ</th>
                        <th>จัดการ</th>
                    </tr>
                    @foreach ($project->customerneed as $customerneed)
                    <tr style="text-align: center;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customerneed->productset->productSetName->name }}</td>
                        <td><img src="data:image/jpeg;base64,{{ base64_encode($customerneed->productset->product_image) }}" class="project-image1"></td>
                        <td>{{ $customerneed->projectImage->imagetype->name  }}</td>
                        <td>{{ $customerneed->width.' * '.$customerneed->height.' ซม.' }}</td>
                        <td>{{ $customerneed->quantity.' ชุด' }}</td>
                        <td>
                            {{ $customerneed->note_need ?? 'ไม่มี'}}
                        </td>
                        <td>
                            <div style="display: flex; justify-content: center; align-items: center; gap: 5px;">
                                <a href="{{ route('admin.projects.editformcustomerneed',$customerneed->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                                    <i class="fas fa-edit "></i>
                                </a>
                                <form action="{{ route('admin.projects.deletecustomerneed',$customerneed->id) }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
                @else
        <div class="box">
            <center>
                <p>ข้อมูลความต้องการยังไม่ถูกเพิ่ม</p>
            </center>
        </div>
        @endif
            </div>
        </div>
    </div>
    @endif

    @if (in_array($project->status,$satatusonline))
    <div class="boxmaterial" style="margin-top: 20px; margin-bottom: 20px;">
        <div style="display: flex; justify-content:  space-between; border-bottom: 2px solid #eee; padding-bottom: 10px;">
            <p style="margin-bottom: 20px; ">ข้อมูลส่วนตัวและที่อยู่ลูกค้า</p>
            <a href="{{ route('admin.projects.projecteditcustomer', ['id' => $project->customer->id, 'project_id' => $project->id]) }}" class=" btn-icon btn-edit" title="แก้ไข">
                        <i class="fas fa-edit"></i>
            </a>
        </div>
        
            <div class="detail-grid" style="margin-top: 20px;;">
                <div>
                    <span class="label">เลขประจำตัวผู้เสียภาษี</span>
                    <span class="value">
                    {{ $project->customer->tax_id_number ?? '-' }}
                    </span>
                </div>

                <div>
                    <span class="label">ชื่อ-นามสกุล</span>
                    <span class="value">
                        {{ $project->customer->prefix ?? '' }}{{ $project->customer->first_name }} {{ $project->customer->last_name }}
                    </span>
                </div>

                <div>
                    <span class="label">เพศ</span>
                    <span class="value">{{ $project->customer->gender ?? '-' }}</span>
                </div>

                <div>
                    <span class="label">เบอร์โทร</span>
                    <span class="value">{{ $project->customer->phone ?? '-' }}</span>
                </div>

                <div>
                    <span class="label">อีเมล</span>
                    <span class="value">{{ $project->customer->email ?? '-' }}</span>
                </div>

                <div class="full">
                    <span class="label">ที่อยู่</span>
                    <span class="value">
                        เลขที่ {{ $project->customer->house_number ?? '-' }}
                        {{ $project->customer->village ? 'หมู่ '.$project->customer->village : '' }}
                        {{ $project->customer->house_name ?? '' }}
                        {{ $project->customer->alley ? 'ซอย '.$project->customer->alley : '' }}
                        {{ $project->customer->road ? 'ถนน '.$project->customer->road : '' }} <br>
                        ต.{{ $project->customer->tambon->name_th ?? '-' }}
                        อ.{{ $project->customer->amphure->name_th ?? '-' }}
                        จ.{{ $project->customer->province->name_th ?? '-' }}
                        {{ $project->customer->tambon->zip_code ?? '-' }}
                    </span>
                </div>
            </div>
    </div>

    <div class="boxmaterial">
        <div style="border-bottom: 2px solid #eee;">
            <p style="margin-bottom: 20px; ">รายละเอียดงาน : {{ $project->projectname->name ?? '-' }}</p>
        </div>
        <div class="detail-grid" style="margin-top: 20px;;">

                <div>
                    <span class="label">อัตราค่าแรงต่อวัน</span>
                    <span class="value">
                        {{ $project->daily_labor_rate ?? '' }}
                    </span>
                </div>

                <div>
                    <span class="label">จำนวนวันทำงานที่ประเมิน</span>
                    <span class="value">{{ $project->estimated_work_days.' วัน' ?? '' }}</span>
                </div>

        </div>
        <div style="border-bottom: 2px solid #eee; ">
            <p style="margin-bottom: 20px; margin-top: 20px;">ความต้องการของลูกค้า</p>
        </div>

        <div>
            @foreach ($project->customerneed as $customerneed)
            <div>
            <p style="margin-bottom: 20px; margin-top: 20px;">ชุดที่ {{ $loop->iteration }}</p>
                <div style="display: flex; flex-direction: row; flex-wrap: wrap;" class="imgpositionneed">
                    @if($customerneed->productset && $customerneed->productset->product_image)
                    <div style="flex: 1;">
                        <img src="data:image/jpeg;base64,{{ base64_encode($customerneed->productset->product_image) }}" alt="Product Image">
                        <span style="font-size: 12px; color: gray; display: block; text-align: center; margin-bottom: 5px;">รูปแบบสินค้า</span>
                    </div>
                    @endif

                    @if($customerneed->projectImage && $customerneed->projectImage->image_path)
                    <div style="flex: 1;">
                        <img src="data:image/jpeg;base64,{{ base64_encode($customerneed->projectImage->image_path) }}" alt="Location Image">
                        <span style="font-size: 12px; color: gray; display: block; text-align: center; margin-bottom: 5px;">หน้างานจริง</span>
                    </div>
                    @endif

                    @if($customerneed)
                    <div style="flex: 1;">
                        
                        <img src="data:image/jpeg;base64,{{ base64_encode($customerneed->installation_image) }}" alt="Location Image">
                        <span style="font-size: 12px; color: gray; display: block; text-align: center; margin-bottom: 5px;">พื้นที่ว่างที่จะติด</span>
                    </div>
                    @endif
                </div>
                <div class=" box-control" style=" margin-top: 50px; border-bottom: 2px solid #eee; padding-bottom: 20px;">

                        <div>
                            <span class="label">ชุดผลิตภัณฑ์</span>
                            <span class="value">
                                {{ $customerneed->productset->productSetName->name.'|'.'อลูมิเนียม'.$customerneed->productset->aluminumSurfaceFinish->name.'|'.'กระจก'.$customerneed->productset->glasscolouritem->name  ?? '' }}
                            </span>
                        </div>

                        <div>
                            <span class="label" >ตำแหน่งที่จะติดตั้ง</span>
                            <span class="value">{{ $customerneed->projectImage->imagetype->name ?? 'ไม่มีข้อมูล' }}</span>
                        </div>

                        <div>
                            <span class="label" >ขนาดกว้าง*สูง (ซม.)</span>
                            <span class="value">{{ $customerneed->	width ?? 'ไม่มีข้อมูล' }}*{{ $customerneed->height ?? 'ไม่มีข้อมูล' }}</span>
                        </div>


                        <div>
                            <span class="label" >หมายเหตุหรือความต้องการเบื้องต้น (ถ้ามี)</span>
                            <span class="value">{{ $customerneed->note_need ?? 'ไม่มีข้อมูล' }}</span>
                        </div>

                </div>
                
            </div>
            @endforeach
        </div>






               
    </div>






    @endif

    



</div>



@endsection