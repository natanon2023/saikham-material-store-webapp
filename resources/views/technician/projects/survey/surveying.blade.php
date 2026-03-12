@extends('layouts.technician')

@section('content')
<div class="main-content">
    @include('components.successanderror')
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>บันทึกการสำรวจหน้างานและความต้องการของลูกค้า</h3>
        <a href="{{ route('technician.projects.show',$project->id) }}" class="btn btn-secondary">ย้อนกลับ</a>
    </div>

    @include('components.progress-steps2')

    <div style="margin-top :20px;">
        <div class="boxmaterial" style="display: flex; justify-content: space-between;">
            รูปภาพหน้างาน
            <a href="{{ route('technician.projects.formprojectimage',$project->id) }}" class="btn btn-secondary">เพิ่มรูปภาพงาน</a>
        </div>
        @if ($project->projectimage->count() > 0)
            
                <table style="text-align: center;">
                    <tr>
                        <th>ภาพที่</th> 
                        <th>ภาพ</th>
                        <th>มุมภาพ</th>
                        <th>รายละเอียดภาพ </th>
                        <th>จัดการ</th>
                    </tr>
                    @foreach ($project->projectimage as $image)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><img src="data:image/jpeg;base64,{{ base64_encode($image->image_path) }}" class="project-image1"></td>
                        <td>{{ $image->image_type }}</td>
                        <td>{{ $image->description ?? 'ไม่มีรายละเอียดภาพ' }}</td>
                        <td>
                            <form action="{{ route('technician.projects.deleteprojectimage',$image->id) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
            <a href="{{ route('technician.projects.formcustomerneed',$project->id) }}" class="btn btn-secondary">เพิ่มความต้องการของลูกค้า</a>
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
                    <th>จัดการ</th>
                </tr>
                @foreach ($project->customerneed as $customerneed)
                <tr style="text-align: center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $customerneed->productset->productSetName->name }}</td>
                    <td><img src="data:image/jpeg;base64,{{ base64_encode($customerneed->productset->product_image) }}" class="project-image1"></td>
                    <td>{{ $customerneed->location }}</td>
                    <td>{{ $customerneed->width.' * '.$customerneed->high.' ซม.' }}</td>
                    <td>{{ $customerneed->quantity.' ชุด' }}</td>
                    <td>
                        <form action="{{ route('technician.projects.deletecustomerneed',$customerneed->id) }}" method="post">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                
                @endforeach
            </table>
            @if($project->customerneed->count() > 0)
                @if ($project->status == 'surveying')
                    <div style="margin-top: 20px; text-align: right;">
                        <form action="{{ route('technician.projects.updatestatuspendingquotation') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $project->id }}">
                            <button type="submit" class="btn btn-primary">บันทึกความต้องการ</button>
                        </form> 
                    </div>
                @endif
            @endif
        </div>
    </div>








</div>

@endsection