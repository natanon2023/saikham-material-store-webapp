@extends('layouts.technician')

@section('content')
<div class="main-content">
    <div class="boxmaterial control-section" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3>รายละเอียดงาน: {{ $project->projectname?->name }}</h3>
            <div style="width: fit-content;">
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
        </div>
        <a href="{{ route('technician.projects.index', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div style="margin-top: 20px;">
        @include('components.successanderror')
    </div>

    <div class="boxmaterial" style="margin-top: 20px; margin-bottom: 20px;">
        <div style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
            <p style="margin: 0;">ข้อมูลงานและลูกค้า</p>
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

    @if(in_array($project->status, $satatuswaiting))

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <p style="margin-bottom: 15px;">ข้อมูลค่าใช้จ่ายเพิ่มเติม</p>
            <table style="text-align: center;">
                <tr>
                    <th>ที่</th>
                    <th>รายการ</th>
                    <th>จำนวนเงิน</th>
                    <th>วันที่</th>
                    <th>รายละเอียด</th>
                    <th>ผู้เพิ่ม</th>
                </tr>
                @forelse($project->projectexpenses as $expense)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $expense->type->name }}</td>
                    <td>{{ number_format($expense->amount, 2) }} บาท</td>
                    <td>{{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : '-' }}</td>
                    <td>{{ $expense->description ?? '-' }}</td>
                    <td>{{ $expense->creator->name }}</td>
                </tr>
                @empty
                <tr><td colspan="6" align="center" style="padding:15px; color:#999;">ยังไม่มีค่าใช้จ่าย</td></tr>
                @endforelse
            </table>
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <p style="margin-bottom: 20px;">ข้อมูลการสำรวจหน้างาน</p>
            <form action="{{ route('technician.projects.addautersurver') }}" method="POST" enctype="multipart/form-data">
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
            <p style="margin-bottom: 15px;">รายชื่อช่างติดตั้ง</p>
            <table>
                <tr align="center">
                    <th>ลำดับ</th>
                    <th>ชื่อ - สกุล</th>
                </tr>
                @forelse($project->installers as $installer)
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td align="center">ช่าง {{ $installer->name }} {{ $installer->last_name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" align="center" style="padding:15px; color:#999;">ยังไม่มีช่างติดตั้ง</td>
                </tr>
                @endforelse
            </table>
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
                <span>รูปภาพหน้างาน</span>
                <a href="{{ route('technician.projects.formprojectimagedetail', $project->id) }}" class="btn btn-secondary">เพิ่มรูปภาพ</a>
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
                        <a href="{{ route('technician.projects.formeditprojectimage', $image->id) }}" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('technician.projects.deleteprojectimage', $image->id) }}" method="post" style="display:inline;">
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
                <a href="{{ route('technician.projects.formcustomerneeddetial', $project->id) }}" class="btn btn-secondary">เพิ่มความต้องการ</a>
            </div>
            @if($project->customerneed->count() > 0)
            <table>
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
                        <a href="{{ route('technician.projects.editformcustomerneed', $cn->id) }}" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('technician.projects.deletecustomerneed', $cn->id) }}" method="post" style="display:inline;">
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

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                <h4 style="margin:0;">ค่าแรงที่จะได้รับในโครงการนี้</h4>
            </div>
            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse; border:1px solid #ddd;">
                <thead style="background:#f9f9f9;">
                    <tr>
                        <th width="60%" style="border-bottom:2px solid #ddd;">รายการ</th>
                        <th width="40%" style="border-bottom:2px solid #ddd; text-align:right;">จำนวนเงิน (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    @if($isSurveyor)
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="color:#555;">ค่าแรงสำรวจ</td>
                        <td align="right" style="font-weight:bold;">{{ number_format($surveyPay, 2) }}</td>
                    </tr>
                    @endif
                    @if($isInstaller)
                    <tr style="border-bottom:1px solid #eee;">
                        <td>
                            <span style="color:#555;">ค่าแรงติดตั้ง</span><br>
                            <small style="color:#888;">({{ number_format($project->daily_labor_rate, 2) }} บ. × {{ $project->estimated_work_days }} วัน )</small>
                        </td>
                        <td align="right" style="font-weight:bold;">{{ number_format($installPay, 2) }}</td>
                    </tr>
                    @endif
                    <tr style="background:#f6fdf6; border-top:2px solid #cce8d4;">
                        <td style="font-weight:bold; color:#1e8e3e;">รวมทั้งหมด</td>
                        <td align="right" style="color:#1e8e3e; font-weight:bold; font-size:1.1em;">{{ number_format($totalPay, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:15px;">
                <p style="margin:0;">รายละเอียดงาน: {{ $project->projectname->name ?? '-' }}</p>
            </div>
            <div class="detail-grid" style="margin-top:20px;">
                <div>
                    <span class="label">วันเริ่มงาน</span>
                    <span class="value">
                        {{ $project->installation_start_date ? \Carbon\Carbon::parse($project->installation_start_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : 'ยังไม่ได้กำหนด' }}
                    </span>
                </div>
                <div>
                    <span class="label">วันจบงาน</span>
                    <span class="value">
                        {{ $project->installation_end_date ? \Carbon\Carbon::parse($project->installation_end_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') : 'ยังไม่ได้กำหนด' }}
                    </span>
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
        </div>

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:15px;">
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
                                <img src="data:image/jpeg;base64,{{ base64_encode($need->installation_image) }}" style="width:100px; height:100px; object-fit:cover;">
                            @else
                                <span style="color:#999;">ไม่มีภาพ</span>
                            @endif
                        </td>
                        <td>
                            @if($need->imageafter)
                                <img src="data:image/jpeg;base64,{{ base64_encode($need->imageafter) }}" style="width:150px; height:100px; object-fit:cover;">
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
            if (!this.value) {
                document.getElementById('installation_end_date').value = '';
                return;
            }
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