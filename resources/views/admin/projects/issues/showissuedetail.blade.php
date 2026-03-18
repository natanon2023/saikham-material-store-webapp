@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div style="background-color: #ffffff; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0;">รายละเอียดการแจ้งปัญหา</h4>
            <a href="{{ route('admin.projects.issues.detail', $issue->project_id) }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
    </div>

    @include('components.successanderror')

    <div class="boxmaterial" style="margin-bottom: 20px; line-height: 1.8;">
    
    @if($issue->images->isNotEmpty())
    <div style="display: flex; justify-content: center; margin-bottom: 30px; margin-top: 10px;">
        <div style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center;">
            @foreach($issue->images as $img)
                <img src="data:image/jpeg;base64,{{ base64_encode($img->image_data) }}" style="max-width: 100%; max-height: 350px; object-fit: contain;  border: 1px solid #ddd; padding: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            @endforeach
        </div>
    </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;  padding: 25px;  ">
        
        <div>
            <div style="color: #888; font-size: 0.9em; margin-bottom: 3px;">โครงการ</div>
            <div >{{ $issue->project->projectname->name ?? '-' }}
                @if($issue->status == 'pending')
                    <span  style=" background-color: #e74c3c; color: white; padding: 5px; border-radius:20px;">รอดำเนินการ</span>
                @elseif($issue->status == 'in_progress')
                    <span style=" background-color: #f39c12; color: white; padding: 5px; border-radius:20px;">กำลังแก้ไข</span>
                @elseif($issue->status == 'resolved')
                    <span style=" background-color: #2ecc71; color: white;  padding: 5px; border-radius: 20px;"  >แก้ไขแล้ว</span>
                @else
                    {{ $issue->status }}
                @endif
            </div>
        </div>

        <div>
            <div style="color: #888; font-size: 0.9em; margin-bottom: 3px;">ผู้รายงาน</div>
            <div >{{ $issue->reporter->name ?? '-' }}</div>
        </div>

        <div>
            <div style="color: #888; font-size: 0.9em; margin-bottom: 3px;">วันที่แจ้ง</div>
            <div >{{ $issue->created_at->locale('th')->translatedFormat('d F Y H:i น.') }}</div>
        </div>

        <div>
            <div style="color: #888; font-size: 0.9em; margin-bottom: 3px;">ประเภทปัญหา</div>
            <div>
                @if($issue->category == 'material_problems')
                    วัสดุเสียหาย
                @else
                    ปัญหาทั่วไป
                @endif
            </div>
        </div>


        <div >
            <div style="color: #888; font-size: 0.9em; margin-bottom: 3px;">รายละเอียด</div>
                {{ $issue->description ?? 'ไม่มีรายละเอียด' }}
        </div>

        @if($issue->category == 'material_problems' && $issue->withdrawalitemdamaged)
        <div style="grid-column: 1 / -1; border-top: 1px dashed #ccc; padding-top: 20px; margin-top: 10px;">
            <div style="color: #888; font-size: 0.9em; margin-bottom: 5px;">วัสดุที่เสียหาย</div>
            <div>
                @php $material = $issue->withdrawalitemdamaged->material; @endphp
                @if ($material->material_type == 'อลูมิเนียม')
                    อลูมิเนียม - {{ $material->aluminiumItem->aluminiumType->name ?? '' }} - {{ $material->aluminiumItem->aluminumSurfaceFinish->name ?? '' }} - {{ $material->aluminiumItem->aluminiumLengths->length_meter ?? '' }} เมตร
                @elseif ($material->material_type == 'กระจก')
                    กระจก - {{ $material->glassItem->glassType->name ?? '' }} - สี{{ $material->glassItem->colourItem->name ?? '' }} - ({{ $material->glassItem->glassSize->width_meter ?? '' }}*{{ $material->glassItem->glassSize->length_meter ?? '' }}) ซม. - {{ $material->glassItem->glassSize->thickness ?? '' }} มิลลิเมตร
                @elseif ($material->material_type == 'อุปกรณ์เสริม')
                    อุปกรณ์เสริม - {{ $material->accessoryItem->accessoryType->name ?? '' }} - {{ $material->accessoryItem->aluminumSurfaceFinish->name ?? '' }}
                @elseif ($material->material_type == 'วัสดุสิ้นเปลือง')
                    วัสดุสิ้นเปลือง - {{ $material->consumableItem->consumabletype->name ?? '' }}
                @elseif ($material->material_type == 'เครื่องมือช่าง')
                    เครื่องมือช่าง - {{ $material->toolItem->toolType->name ?? '' }} - {{ $material->toolItem->description ?? '' }}
                @endif
                <span style="margin-left: 10px; padding: 3px 10px; background-color: #fdebd0; border-radius: 20px; ">
                    จำนวน: {{ $issue->damaged_amount }} รายการ
                </span>
            </div>
        </div>
        @endif

    </div>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">
    @if ($issue->category == 'general_problems' && $issue->status != 'resolved')
    <div style="text-align: right;">
        <form action="{{ route('admin.projects.updateresolved',$issue->id) }}" method="POST"  style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-secondary" style="padding: 10px 20px;">ดำเนินการเสร็จสิ้น</button>
        </form>
    </div>
    @elseif($issue->category == 'general_problems' && $issue->status == 'resolved')
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px;  padding: 15px; ">
                <div style="font-size: 16px; ">
                    ดำเนินการเสร็จสิ้นแล้วหากต้องการแก้ไขกดยกเลิก
                </div>
                <form action="{{ route('admin.projects.undoIssuegeneralproblems', $issue->id) }}" method="POST" onsubmit="return confirm('ต้องการยกเลิกการเติมวัสดุเพื่อแก้ไขใหม่ใช่หรือไม่?');" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="padding: 10px 20px;">ยกเลิกและทำรายการใหม่</button>
                </form>
            </div>
    @endif
    

    <div style="text-align: right;">
        @if($issue->category == 'material_problems' && $issue->status != 'resolved')
            <a href="{{ route('admin.projects.issues.refill', $issue->id) }}" class="btn btn-secondary" style="padding: 12px 25px; font-size: 16px;">
                จัดการวัสดุ
            </a>
        @elseif($issue->category == 'material_problems' && $issue->status == 'resolved')
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px;  padding: 15px; ">
                <div style="font-size: 16px; ">
                    ดำเนินการแก้ไขและเติมวัสดุเสร็จสิ้นแล้ว (จำนวน {{ $issue->refilled_amount }} รายการ)
                </div>
                <form action="{{ route('admin.projects.issues.refill.undo', $issue->id) }}" method="POST" onsubmit="return confirm('ต้องการยกเลิกการเติมวัสดุเพื่อแก้ไขใหม่ใช่หรือไม่?');" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="padding: 10px 20px;">ยกเลิกและทำรายการใหม่</button>
                </form>
            </div>
        @endif
    </div>
</div>
</div>
@endsection