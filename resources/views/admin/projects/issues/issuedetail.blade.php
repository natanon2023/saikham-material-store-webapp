@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;" class=" boxmaterial">
        <h3>รายละเอียดปัญหาโครงการ: {{ $project->projectname->name ?? '-' }}</h3>
        <a href="{{ route('admin.projects.manageproblemsindex') }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr align="center">
                <th>วันที่แจ้ง</th>
                <th>ผู้รายงาน</th>
                <th>ประเภทปัญหา</th>
                <th>สถานะ</th>
                <th>รายละเอียด</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($issues as $issue)
                <tr  style="{{ $issue->trashed() ? 'text-decoration: line-through; color: gray;' : '' }}" align="center">
                    <td >
                        {{ $issue->created_at->locale('th')->translatedFormat('d M') }} {{ $issue->created_at->year + 543 }} เวลา {{ $issue->created_at->format('H:i') }} น.
                    </td>
                    <td>{{ $issue->reporter->name ?? '-' }}</td>
                    <td>
                        @if($issue->category == 'material_problems')
                            ปัญหาวัสดุ
                        @else
                            ปัญหาทั่วไป
                        @endif
                    </td>
                    <td>
                        @if($issue->status == 'pending')
                            <span  style=" background-color: #e74c3c; color: white; padding: 5px; border-radius:20px; font-size: 10px; ">รอดำเนินการ</span>
                        @elseif($issue->status == 'in_progress')
                            <span style=" background-color: #f39c12; color: white; padding: 5px; border-radius:20px; font-size: 10px;">กำลังแก้ไข</span>
                        @elseif($issue->status == 'resolved')
                            <span style=" background-color: #2ecc71; color: white;  padding: 5px; border-radius: 20px; font-size: 10px;"  >แก้ไขแล้ว</span>
                        @else
                            {{ $issue->status }}
                        @endif
                    </td>
                    <td>
                        @if($issue->category == 'material_problems' && $issue->withdrawalitemdamaged)
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
                                    <span >(จำนวน {{ $issue->damaged_amount ?? 0 }})</span>
                            </div>
                        @endif
                        <div>{{ $issue->description ?? ' ' }}</div>
                    </td>
                    <td>
                        @if($issue->trashed())
                            <form action="{{ route('admin.projects.issues.restore', $issue->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('ยืนยันการกู้คืน?');">
                                @csrf
                                <button type="submit" class="btn-icon " title="กู้คืน">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.projects.showissuedetail',$issue->id) }}" class="btn-icon btn-show" title="ดูรายละเอียด" style="margin-bottom: 10px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if ($issue->status == 'pending' or $issue->status == 'in_progress')
                            <a href="{{ route('admin.projects.issues.edit',$issue->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            
                            <form action="{{ route('admin.projects.issues.destroy', $issue->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="ลบ">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">ไม่พบข้อมูล</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection