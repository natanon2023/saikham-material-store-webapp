@extends('layouts.technician')

@section('content')
<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between;">
        <div>
            <h5>รายละเอียดงาน: {{ $project->projectname->name ?? 'ไม่ระบุชื่อโครงการ' }} : {{ $statusesthiname }}</h5>
        </div>
        <div style=" height: max-content;">
           <a href="{{ route('technician.dashboard') }}" class="btn btn-secondary">กลับหน้าปฏิทิน</a> 
           @if ($project->status == 'pending_survey')
            <form action="{{ route('technician.projects.updatestatussurveying', $project->id) }}" method="post">
                @csrf
                <button class="btn" style="background-color:{{ $statusColor }};">
                    สำรวจหน้างาน
                </button>
            </form>
           @elseif ($project->status == 'surveying')
            <a href="{{ route('technician.projects.formsurveying',$project->id) }}" class="btn" style="background-color:{{ $statusColor }};">สำรวจต่อ</a>
           @elseif ($project->status == 'pending_quotation')
            <a href="{{ route('technician.projects.formsurveying',$project->id) }}" class="btn" style="background-color:{{ $statusColor }};">แก้ไขการสำรวจ</a>
            <a href="" class="btn btn-secondary">เสนอราคา</a>
           @endif
        </div>
        
    </div>
    <div class="box">
        <div style="display: flex; flex-direction: row; flex-wrap: wrap;">
            <div class="form-group">
                <label for="" class="form-label">ชื่อลูกค้า:</label>
                <label for="" class="form-input">{{ $project->customer->first_name }} {{ $project->customer->last_name }}</label>
            </div>
            <div class="form-group">
                <label for="" class="form-label">เบอร์โทรศัพท์:</label>
                <label for="" class="form-input">{{ $project->customer->phone }}</label>
            </div>
            <div class="form-group">
                <label for="" class="form-label">ที่อยู่:</label>
                <label for="" class="form-input">{{ $project->customer->house_number }}
                    ต.{{ $project->customer->tambon->name_th ?? '-' }}
                    อ.{{ $project->customer->amphure->name_th ?? '-' }}
                    จ.{{ $project->customer->province->name_th ?? '-' }}
                </label>
            </div>
            <div class="form-group">
                <label for="" class="form-label">วันที่นัดหมาย:</label>
                <label for="" class="form-input">{{ date('d/m/Y', strtotime($project->survey_date)) }}</label>
            </div>
            <div class="form-group">
                <label for="" class="form-label">หมายเหตุ:</label>
                <label for="" class="form-input">{{ $project->note ?? '-' }}</label>
            </div>
        </div>

    </div>



    <div class="boxmaterial" style="margin-top: 20px;">รูปภาพหน้างาน</div>
    <div>
        @if ($project->projectimage->count() > 0)
            <table width="100%" style="text-align: center; border-collapse: collapse;" border="1">
                <tr style="background: #f8f9fa;">
                    <th>ภาพ</th>
                    <th>มุมภาพ</th>
                    <th>รายละเอียด</th>
                </tr>
                @foreach ($project->projectimage as $image)
                <tr>
                    <td style="padding: 10px;">
                        <img src="data:image/jpeg;base64,{{ base64_encode($image->image_path) }}" style="width: 120px;">
                    </td>
                    <td>{{ $image->image_type }}</td>
                    <td>{{ $image->description ?? '-' }}</td>
                </tr>
                @endforeach
            </table>
        @else
            <p style="text-align: center; color: #888;">ยังไม่มีการอัปโหลดรูปภาพ</p>
        @endif
    </div>

    <div class="boxmaterial" style="margin-top: 20px;">รายการความต้องการของลูกค้า</div>
    <div>
        @if ($project->customerneed->count() > 0)
            <table width="100%" style="text-align: center; border-collapse: collapse;" border="1">
                <tr style="background: #f8f9fa;">
                    <th>ชุดรายการ</th>
                    <th>รูปภาพ</th>
                    <th>ตำแหน่งติดตั้ง</th>
                    <th>ขนาด (ก*ส)</th>
                    <th>จำนวน</th>
                </tr>
                @foreach ($project->customerneed as $need)
                <tr>
                    <td style="padding: 10px;">{{ $need->productset->productSetName->name }}</td>
                    <td>
                        <img src="data:image/jpeg;base64,{{ base64_encode($need->productset->product_image) }}" style="width: 80px;">
                    </td>
                    <td>{{ $need->location }}</td>
                    <td>{{ $need->width.' * '.$need->high.' ซม.' }}</td>
                    <td>{{ $need->quantity.' ชุด' }}</td>
                </tr>
                @endforeach
            </table>
        @else
            <p style="text-align: center; color: #888;">ยังไม่มีรายการความต้องการ</p>
        @endif
    </div>

</div>
@endsection