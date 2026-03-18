@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>บันทึกการสำรวจหน้างานและความต้องการของลูกค้า</h3>
        <a href="{{ route('admin.projects.index',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    @include('components.progress-steps2')

    <div style="margin-top :20px;">
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
                        <input type="file" name="homeimg" id="homeimg" class="form-input" accept="image/*" required>

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
            <a href="{{ route('admin.projects.formprojectimage',$project->id) }}" class="btn btn-secondary">เพิ่มรูปภาพงาน</a>
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
    </div>
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
            <a href="{{ route('admin.projects.formcustomerneed',$project->id) }}" class="btn btn-secondary">เพิ่มความต้องการของลูกค้า</a>
        </div>

        <div>
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
                    <td>{{ $customerneed->projectImage->imagetype->name }}</td>
                    <td>{{ $customerneed->width.' * '.$customerneed->height.' ซม.' }}</td>
                    <td>{{ $customerneed->quantity.' ชุด' }}</td>
                    <td>
                        {{ $customerneed->note_need ?? 'ไม่มี' }}
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
            @if($project->customerneed->count() > 0)
            @if ($project->status == 'surveying')
            <div style="margin-top: 20px; text-align: right;">
                <form action="{{ route('admin.projects.updatestatuspendingquotation') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $project->id }}">
                    <button type="submit" class="btn btn-secondary">บันทึกความต้องการ</button>
                </form>
            </div>
            @endif
            @endif
        </div>
    </div>








</div>

@endsection