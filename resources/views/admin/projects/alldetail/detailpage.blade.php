@extends('layouts.admin')

@section('content')
<div class="main-content">

    <div class="boxmaterial control-section" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3>รายละเอียดงาน: {{ $project->projectname?->name }}</h3>
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
            @endphp
            <div style="background-color: {{ $cs[0] }};" class="boxstatusproject">{{ $cs[1] }}</div>
        </div>
        <a href="{{ route('admin.projects.index', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div style="margin-top: 20px;">
        @include('components.successanderror')
    </div>

    @if(in_array($project->status, $statusopendatework))
    <div class="boxmaterial" style="margin-top: 20px; margin-bottom: 20px;">
        <h3 style="margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            ระยะเวลาทำงาน {{ $project->estimated_work_days }} วัน
        </h3>
        <div class="box-control" style="display: flex; gap: 20px; margin-top: 15px;">
            <div style="flex: 1; background: #f9f9f9; padding: 15px; border-left: 4px solid #b5ffc6;">
                <span style="font-size: 0.85em; color: #666; display: block;">วันเริ่มงาน</span>
                {{ $project->installation_start_date ? \Carbon\Carbon::parse($project->installation_start_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : 'ยังไม่ได้กำหนด' }}
            </div>
            <div style="flex: 1; background: #f9f9f9; padding: 15px; border-left: 4px solid #ffa7b0;">
                <span style="font-size: 0.85em; color: #666; display: block;">วันจบงาน</span>
                {{ $project->installation_end_date ? \Carbon\Carbon::parse($project->installation_end_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : 'ยังไม่ได้กำหนด' }}
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.projects.assignInstaller', $project->id) }}">
        @csrf @method('PUT')
        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">วันเริ่มงาน (ทำงาน {{ $project->estimated_work_days }} วัน)</label>
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
    @endif

    <div class="boxmaterial" style="margin-top: 20px; margin-bottom: 20px;">
        <div style="margin-bottom: 15px; border-bottom: 2px solid #eee; padding-bottom: 10px;">
            จัดการเอกสารโครงการ
        </div>
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">

            @if(in_array($project->status, $satatusopen))
            <div style="flex: 1; min-width: 250px; border: 1px solid #61b8c2; padding: 15px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h5>ใบเสนอราคา</h5>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">รายละเอียดและยอดประเมิน</p>
                </div>
                <a href="{{ route('admin.projects.addbiddocument', $project->id) }}" class="btn btn-secondary" style="background:#17a2b8; border:none; padding:8px 15px; height: max-content;">เปิดเอกสาร</a>
            </div>
            @endif

            @if(in_array($project->status, $statusmaterialplanningopen))
            <div style="flex: 1; min-width: 250px; border: 1px solid #61b8c2; padding: 15px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h5>ใบสั่งซื้อวัสดุ</h5>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">รายการวัสดุที่ต้องซื้อเพิ่ม</p>
                </div>
                <a href="{{ route('admin.projects.materialplanningpagedocument', $project->id) }}" class="btn btn-secondary" style="background:#00CED1; border:none; padding:8px 15px; height: max-content;">เปิดเอกสาร</a>
            </div>
            @endif

            @if(in_array($project->status, $statuspayment))
            <div style="flex: 1; min-width: 250px; border: 1px solid #28a745; padding: 15px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h5>ใบเสร็จรับเงิน</h5>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">หลักฐานการชำระเงิน</p>
                </div>
                <a href="{{ route('admin.projects.receipt', $project->id) }}" class="btn btn-secondary" style="background:#28a745; border:none; padding:8px 15px; height: max-content;">เปิดเอกสาร</a>
            </div>
            <div style="flex: 1; min-width: 250px; border: 1px solid #dc3545; padding: 15px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h5>ใบกำกับภาษี</h5>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">เอกสารภาษีมูลค่าเพิ่ม (VAT)</p>
                </div>
                <a href="{{ route('admin.projects.taxInvoice', $project->id) }}" class="btn btn-secondary" style="background:#dc3545; border:none; padding:8px 15px; height: max-content;">เปิดเอกสาร</a>
            </div>
            @endif

            @if(!in_array($project->status, $satatusopen) && !in_array($project->status, $statuspayment) && !in_array($project->status, $statusmaterialplanningopen))
            <div style="width: 100%; text-align: center; padding: 20px; color: #999;">
                โปรเจกต์นี้ยังไม่อยู่ในขั้นตอนการออกเอกสาร (กรุณาอัปเดตสถานะการสำรวจ)
            </div>
            @endif
        </div>
    </div>

    @if (in_array($project->status, $satatuswaiting1))
        <div class="boxmaterial" style="margin-top: 20px; margin-bottom: 20px;">
            <form action="{{ route('admin.projects.updateProjectPendingSurvey', $project->id) }}" method="POST">
                @csrf @method('PUT')
                <p style="margin-bottom: 15px;">ข้อมูลงาน</p>
                <div class="box-control" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label class="form-label">ชื่องาน</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <select name="project_name_id" class="form-input" required>
                                <option value="">เลือกชื่องาน</option>
                                @foreach($projectname as $pn)
                                <option value="{{ $pn->id }}" {{ $project->project_name_id == $pn->id ? 'selected' : '' }}>{{ $pn->name }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('admin.projects.formprojectname') }}" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0;">จัดการ</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ชื่อ-นามสกุล ลูกค้า</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <select name="customer_id" class="form-input" required>
                                <option value="">เลือกลูกค้า</option>
                                @foreach($customerall as $ca)
                                <option value="{{ $ca->id }}" {{ $project->customer_id == $ca->id ? 'selected' : '' }}>{{ $ca->first_name.' '.$ca->last_name }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('admin.projects.formnewcustomer') }}" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; text-decoration: none; white-space: nowrap; border-radius: 0;">จัดการ</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">วันและเวลานัดสำรวจ</label>
                        <input type="datetime-local" name="survey_date" class="form-input" value="{{ $project->survey_date }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">เลือกช่างที่จะไปสำรวจ</label>
                        <select name="assigned_surveyor_id" class="form-select" required>
                            <option value="">เลือกช่าง</option>
                            @foreach($technician as $tc)
                            <option value="{{ $tc->id }}" {{ $project->assigned_surveyor_id == $tc->id ? 'selected' : '' }}>ช่าง {{ $tc->name }} {{ $tc->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ค่าแรงช่างสำรวจ</label>
                        <input type="number" name="labor_cost_surveying" class="form-input" value="{{ $project->labor_cost_surveying }}" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">หมายเหตุ (ถ้ามี)</label>
                        <textarea name="note" class="form-input">{{ $project->note }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary">แก้ไขข้อมูล</button>
            </form>
        </div>
    @endif

    @if(in_array($project->status, $satatuswaiting))
        <div class="boxmaterial" style="margin-top: 20px; margin-bottom: 20px;">
        <div style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px; display: flex; justify-content: space-between;">
            <p style="margin: 0;">ข้อมูลงานและลูกค้า</p>
            <a href="{{ route('admin.projects.projecteditcustomer', ['id' => $project->customer->id, 'project_id' => $project->id]) }}" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
        </div>
        
        <div class="detail-grid" style="margin-top: 20px;">
            <div>
                <span class="label">ชื่องาน</span>
                <span class="value">{{ $project->projectname->name ?? '-' }}</span>
            </div>
            <div>
                <span class="label">วันนัดสำรวจ</span>
                <span class="value">
                    {{ $project->survey_date
                        ? \Carbon\Carbon::parse($project->survey_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY')
                        : 'ยังไม่ได้กำหนด' }}
                </span>
            </div>
            <div>
                <span class="label">ชื่อ-นามสกุล</span>
                <span class="value">{{ $project->customer->prefix ?? '' }}{{ $project->customer->first_name }} {{ $project->customer->last_name }}</span>
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
                    {{ $project->customer->road ? 'ถนน '.$project->customer->road : '' }}<br>
                    ต.{{ $project->customer->tambon->name_th ?? '-' }}
                    อ.{{ $project->customer->amphure->name_th ?? '-' }}
                    จ.{{ $project->customer->province->name_th ?? '-' }}
                    {{ $project->customer->tambon->zip_code ?? '' }}
                </span>
            </div>
        </div>
    </div>

        

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <span>ค่าใช้จ่ายเพิ่มเติม</span>
                <a href="{{ route('admin.projects.formdetialexpense', $project->id) }}" class="btn btn-secondary">เพิ่มค่าใช้จ่าย</a>
            </div>
            <table style="text-align: center;">
                <tr><th>ที่</th><th>รายการ</th><th>จำนวนเงิน</th><th>วันที่</th><th>รายละเอียด</th><th>ผู้เพิ่ม</th><th>จัดการ</th></tr>
                @forelse($project->projectexpenses as $expense)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $expense->type->name }}</td>
                    <td>{{ number_format($expense->amount, 2) }} บาท</td>
                    <td>{{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : '-' }}</td>
                    <td>{{ $expense->description ?? '-' }}</td>
                    <td>{{ $expense->creator->name }}</td>
                    <td>
                        <a href="{{ route('admin.projects.formeditdetialexpense', $expense->id) }}" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.projects.deletedetialexpense', $expense->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" align="center" style="padding:15px; color:#999;">ยังไม่มีค่าใช้จ่าย</td></tr>
                @endforelse
            </table>
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <p style="margin-bottom: 20px;">ข้อมูลการสำรวจหน้างาน</p>
            <form action="{{ route('admin.projects.addautersurver') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $project->id }}">
                <div style="display: flex; margin-bottom: 20px;">
                    <div class="boxmaterial">
                        <div class="image-preview">
                            @if($project->homeimg)
                                <img src="data:image/jpeg;base64,{{ base64_encode($project->homeimg) }}" alt="Project Image">
                            @else
                                <div style="text-align:center; margin-top:50px;"><span>ไม่มีรูปภาพ</span></div>
                            @endif
                        </div>
                    </div>
                    <div class="boxmaterial" style="width: 800px;">
                        <div class="form-group">
                            <label class="form-label">รูปภาพบ้าน</label>
                            <input type="file" name="homeimg" class="form-input" accept="image/*">
                            <label class="form-label" style="margin-top:10px;">อัตราค่าแรงต่อวัน</label>
                            <input type="number" name="daily_labor_rate" class="form-input" value="{{ $project->daily_labor_rate ?? '' }}" min="0" step="0.01" required>
                            <label class="form-label" style="margin-top:10px;">จำนวนวันทำงานที่ประเมิน</label>
                            <input type="number" name="estimated_work_days" class="form-input" value="{{ $project->estimated_work_days ?? '' }}" required>
                            <div style="display:flex; flex-direction:row-reverse; margin-top:20px;">
                                <button type="submit" class="btn btn-secondary">อัพเดทข้อมูล</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <h3 style="margin-bottom:15px;">เลือกช่างติดตั้ง</h3>
            <form action="{{ route('admin.projects.assignInstalleruser', $project->id) }}" method="POST">
                @csrf
                <div class="box-control">
                    <div class="form-group">
                        <select name="user_id" class="form-input" required>
                            <option value="">เลือกช่าง</option>
                            @foreach($technician as $installer)
                            <option value="{{ $installer->id }}">ช่าง {{ $installer->name }} {{ $installer->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary">บันทึก</button>
                </div>
            </form>
            <table style="margin-top: 15px;">
                <tr align="center"><th>ลำดับ</th><th>ชื่อ - สกุล</th><th>จัดการ</th></tr>
                @forelse($project->installers as $installer)
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td align="center">ช่าง {{ $installer->name }} {{ $installer->last_name }}</td>
                    <td align="center">
                        <form action="{{ route('admin.projects.removeinstaller', $installer->pivot->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" onclick="return confirm('ลบเฉพาะช่างคนนี้?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" align="center" style="padding:15px; color:#999;">ยังไม่มีช่างติดตั้ง</td></tr>
                @endforelse
            </table>
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
                <span>รูปภาพหน้างาน</span>
                <a href="{{ route('admin.projects.formprojectimagedetail', $project->id) }}" class="btn btn-secondary">เพิ่มรูปภาพ</a>
            </div>
            @if($project->projectimage->count() > 0)
            <table style="text-align:center;">
                <tr><th>ที่</th><th>รูปภาพ</th><th>ตำแหน่ง</th><th>รายละเอียด</th><th>จัดการ</th></tr>
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
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
                <span>ความต้องการของลูกค้า</span>
                <a href="{{ route('admin.projects.formcustomerneeddetial', $project->id) }}" class="btn btn-secondary">เพิ่มความต้องการ</a>
            </div>
            @if($project->customerneed->count() > 0)
            <table>
                <tr style="text-align:center;">
                    <th>ที่</th><th>ชุดรายการ</th><th>รูปภาพ</th><th>ตำแหน่ง</th><th>ขนาด (กว้าง×สูง)</th><th>จำนวน</th><th>หมายเหตุ</th><th>จัดการ</th>
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
            <p style="text-align:center; color:#999; padding:15px;">ยังไม่มีข้อมูลความต้องการ</p>
            @endif
        </div>

    @endif

    @if(in_array($project->status, $satatusonline))

        <div class="boxmaterial" style="margin-top: 20px; margin-bottom: 20px;">
            <div style="display:flex; justify-content:space-between; border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:15px;">
                <p style="margin:0;">ข้อมูลส่วนตัวและที่อยู่ลูกค้า</p>
                <a href="{{ route('admin.projects.projecteditcustomer', ['id' => $project->customer->id, 'project_id' => $project->id]) }}" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
            </div>
            <div class="detail-grid" style="margin-top:20px;">
                <div>
                    <span class="label">เลขประจำตัวผู้เสียภาษี</span>
                    <span class="value">{{ $project->customer->tax_id_number ?? '-' }}</span>
                </div>
                <div>
                    <span class="label">ชื่อ-นามสกุล</span>
                    <span class="value">{{ $project->customer->prefix ?? '' }}{{ $project->customer->first_name }} {{ $project->customer->last_name }}</span>
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
                        {{ $project->customer->road ? 'ถนน '.$project->customer->road : '' }}<br>
                        ต.{{ $project->customer->tambon->name_th ?? '-' }}
                        อ.{{ $project->customer->amphure->name_th ?? '-' }}
                        จ.{{ $project->customer->province->name_th ?? '-' }}
                        {{ $project->customer->tambon->zip_code ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:15px;">
                <p style="margin:0;">รายละเอียดงาน: {{ $project->projectname->name ?? '-' }}</p>
            </div>
            <div class="detail-grid" style="margin-top:20px;">
                <div>
                    <span class="label">วันเริ่มงาน</span>
                    <span class="value">{{ $project->installation_start_date ? \Carbon\Carbon::parse($project->installation_start_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : 'ยังไม่ได้กำหนด' }}</span>
                </div>
                <div>
                    <span class="label">วันจบงาน</span>
                    <span class="value">{{ $project->installation_end_date ? \Carbon\Carbon::parse($project->installation_end_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : 'ยังไม่ได้กำหนด' }}</span>
                </div>
                <div>
                    <span class="label">อัตราค่าแรงต่อวัน</span>
                    <span class="value">{{ number_format($project->daily_labor_rate ?? 0, 2) }} บาท</span>
                </div>
                <div>
                    <span class="label">จำนวนวันทำงาน</span>
                    <span class="value">{{ $project->estimated_work_days ?? '-' }} วัน</span>
                </div>
            </div>

            <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin:30px 0 15px;">
                <p style="margin:0;">รายชื่อช่างติดตั้ง</p>
            </div>
            <table>
                <thead>
                    <tr align="center"><th>ลำดับ</th><th>ชื่อ - สกุล</th><th>เบอร์โทร</th></tr>
                </thead>
                <tbody>
                    @forelse($project->installers as $installer)
                    <tr align="center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $installer->name }} {{ $installer->last_name }}
                            @if($installer->nickname) (ช่าง{{ $installer->nickname }}) @endif
                        </td>
                        <td>{{ $installer->phone_number ? 'โทร '.$installer->phone_number : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" align="center" style="padding:15px; color:#999;">ยังไม่มีช่างติดตั้ง</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:15px;">
                <p style="margin:0;">ภาพก่อนและหลังติดตั้ง</p>
            </div>
            <table border="1" cellpadding="12" cellspacing="0" style="width:100%; border-collapse:collapse;">
                <thead style="background:#f8f9fa;">
                    <tr align="center">
                        <th width="5%">ที่</th>
                        <th width="20%">ผลิตภัณฑ์</th>
                        <th width="15%">ตำแหน่ง</th>
                        <th width="15%">ภาพก่อนติดตั้ง</th>
                        <th width="25%">ภาพหลังติดตั้ง</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project->customerneed as $index => $need)
                    <tr align="center">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $need->productset->productSetName->name ?? '-' }}</strong><br>
                            <small>{{ $need->width }} × {{ $need->height }} ซม.</small>
                        </td>
                        <td>{{ $need->projectImage->imagetype->name ?? '-' }}</td>
                        <td>
                            @if($need->installation_image)
                                <img src="data:image/jpeg;base64,{{ base64_encode($need->installation_image) }}" style="width:100px; height:100px; object-fit:cover; border:1px solid #ddd;">
                            @else
                                <span style="color:#999;">ไม่มีภาพ</span>
                            @endif
                        </td>
                        <td>
                            @if($need->imageafter)
                                <img src="data:image/jpeg;base64,{{ base64_encode($need->imageafter) }}" style="width:150px; height:100px; object-fit:cover; border:2px solid #ddd;">
                            @else
                                <span style="background:#dc3545; color:#fff; padding:5px 10px; border-radius:20px; font-size:0.85em;">ยังไม่ได้อัปโหลด</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" align="center" style="padding:20px; color:#999;">ยังไม่มีข้อมูล</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:15px;">
                <p style="margin:0;">ความต้องการของลูกค้า</p>
            </div>
            @foreach($project->customerneed as $cn)
            <div style="border-bottom:2px solid #eee; padding-bottom:20px; margin-bottom:20px;">
                <p style="margin:0 0 15px 0;">ชุดที่ {{ $loop->iteration }}</p>
                <div style="display:flex; flex-wrap:wrap;" class="imgpositionneed">
                    @if($cn->productset?->product_image)
                    <div style="flex:1;">
                        <img src="data:image/jpeg;base64,{{ base64_encode($cn->productset->product_image) }}" alt="Product">
                        <span style="font-size:12px; color:gray; display:block; text-align:center; margin-top:5px;">รูปแบบสินค้า</span>
                    </div>
                    @endif
                    @if($cn->projectImage?->image_path)
                    <div style="flex:1;">
                        <img src="data:image/jpeg;base64,{{ base64_encode($cn->projectImage->image_path) }}" alt="Location">
                        <span style="font-size:12px; color:gray; display:block; text-align:center; margin-top:5px;">หน้างานจริง</span>
                    </div>
                    @endif
                    @if($cn->installation_image)
                    <div style="flex:1;">
                        <img src="data:image/jpeg;base64,{{ base64_encode($cn->installation_image) }}" alt="Install">
                        <span style="font-size:12px; color:gray; display:block; text-align:center; margin-top:5px;">พื้นที่ว่างที่จะติด</span>
                    </div>
                    @endif
                </div>
                <div class="box-control" style="margin-top:20px; gap:50px;">
                    <div>
                        <span class="label">ชุดผลิตภัณฑ์</span>
                        <span class="value">
                            {{ $cn->productset->productSetName->name ?? '' }}
                            (อลูมิเนียม{{ $cn->productset->aluminumSurfaceFinish->name ?? '' }})
                            กระจก{{ $cn->productset->glasscolouritem->name ?? '' }}
                        </span>
                    </div>
                    <div>
                        <span class="label">ตำแหน่งติดตั้ง</span>
                        <span class="value">{{ $cn->projectImage->imagetype->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="label">ขนาด (ซม.)</span>
                        <span class="value">{{ $cn->width }} × {{ $cn->height }}</span>
                    </div>
                    <div>
                        <span class="label">หมายเหตุ</span>
                        <span class="value">{{ $cn->note_need ?? 'ไม่มี' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    @endif

</div>

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
</script>
@endsection