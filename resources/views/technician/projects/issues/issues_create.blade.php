@extends('layouts.technician')

@section('content')
<div class="main-content">
    <div style="background-color: #ffffff; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h4 style="margin-bottom: 20px;">ขั้นตอนการบันทึก</h4>
        <a href="{{ route('technician.projects.choosetypeissues', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>
    <div style="display: flex; flex-direction: row; text-align: center;">
        <div style="flex: auto; padding: 10px; border: 1px solid #334E68; text-align: center;" >
            ขั้นตอนที่ 1 เลือกประเภทปัญหาที่ต้องการแจ้ง
        </div>
        <div style="flex: auto; background-color: #334E68; padding: 10px; color:#ffffff ;">
            ขั้นตอนที่ 2 กรอกข้อมูลและกดบันทึก
        </div>
    </div>
</div>
    @include('components.successanderror')
    

    <div class="boxmaterial" style="margin-bottom: 20px; display: flex; justify-content: space-between;">
        <h3>รายงานปัญหาวัสดุ</h3>
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px;">เพิ่มการรายงานปัญหา</h3>
        <form action="{{ route('technician.projects.issues.store', $project->id) }}" method="POST" enctype="multipart/form-data" >
            @csrf
            
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">เลือกวัสดุ </label>
                    <select name="withdrawal_item_damaged"  class="form-input" required>
                        <option value="">เลือกวัสดุที่เบิกไปแล้ว</option>
                        @foreach($withdrawnItems as $item)
                            <option value="{{ $item->id }}">
                                @if ($item->material->material_type == 'อลูมิเนียม')
                                    อลูมิเนียม - {{ $item->material->aluminiumItem->aluminiumType->name ?? '' }} - {{ $item->material->aluminiumItem->aluminumSurfaceFinish->name ?? '' }} - {{ $item->material->aluminiumItem->aluminiumLengths->length_meter ?? '' }} เมตร
                                @elseif ($item->material->material_type == 'กระจก')
                                    กระจก - {{ $item->material->glassItem->glassType->name ?? '' }} - สี{{ $item->material->glassItem->colourItem->name ?? '' }} - ({{ $item->material->glassItem->glassSize->width_meter ?? '' }}*{{ $item->material->glassItem->glassSize->length_meter ?? '' }}) ซม. - {{ $item->material->glassItem->glassSize->thickness ?? '' }} มิลลิเมตร
                                @elseif ($item->material->material_type == 'อุปกรณ์เสริม')
                                    อุปกรณ์เสริม - {{ $item->material->accessoryItem->accessoryType->name ?? '' }} - {{ $item->material->accessoryItem->aluminumSurfaceFinish->name ?? '' }}
                                @elseif ($item->material->material_type == 'วัสดุสิ้นเปลือง')
                                    วัสดุสิ้นเปลือง - {{ $item->material->consumableItem->consumabletype->name ?? '' }}
                                @elseif ($item->material->material_type == 'เครื่องมือช่าง')
                                    เครื่องมือช่าง - {{ $item->material->toolItem->toolType->name ?? '' }} - {{ $item->material->toolItem->description ?? '' }}
                                @endif
                                | ล็อต: {{ $item->lot }} | (เบิกไป: {{ $item->quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ภาพวัสดุ</label>
                    <input type="file" name="image_data" class="form-input" accept="image/*" multiple required>
                </div>
            

                <div class="form-group">
                    <label class="form-label">จำนวน</label>
                    <input type="number" name="damaged_amount"  class="form-input" min="1" placeholder="จำนวน" required>
                </div>
                
            

            <div class="form-group" style="margin-top: 15px;">
                <label class="form-label">รายละเอียดเพิ่มเติม (ถ้ามี)</label>
                <textarea name="description" class="form-input" rows="3" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
            </div>

            <div style="margin-top: 15px;">
                <button type="submit" class="btn btn-secondary">บันทึก</button>
            </div>
        </div>
        </form>
    </div>

        <div style="margin-bottom: 20px; display: flex; justify-content: space-between;">
            ประวัติการแจ้งปัญหา
        </div>
        <table>
            <tr align="center">
                <th>วันที่แจ้ง</th>
                <th>ประเภท</th>
                <th>รายละเอียด</th>
                <th>สถานะ</th>
                <th>ผู้รายงาน</th>
            </tr>
            @forelse($issues as $issue)
            <tr>
                <td align="center">
                    {{ $issue->created_at->locale('th')->translatedFormat('d M') }} {{ $issue->created_at->year + 543 }} เวลา {{ $issue->created_at->format('H:i') }} น.
                </td>
                <td align="center">
                    @if($issue->category == 'material_problems')
                        ปัญหาวัสดุ
                    @else
                        ปัญหาทั่วไป
                    @endif
                </td>
                <td align="center">{{ $issue->description ?? 'ไม่มีความคิดเห็น' }}</td>
                <td align="center">
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
                <td align="center">{{ $issue->reporter->name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" align="center" style="padding: 20px; color: gray;">
                    ยังไม่มีการแจ้งปัญหาสำหรับโครงการนี้
                </td>
            </tr>
            @endforelse
        </table>

</div>


@endsection