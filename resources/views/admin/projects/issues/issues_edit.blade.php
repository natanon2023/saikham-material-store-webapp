@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div style="background-color: #ffffff; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h4 style="margin-bottom: 20px;">แก้ไขข้อมูลการรายงานปัญหา</h4>
            <a href="{{ route('admin.projects.issues.detail', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
    </div>
    
    @include('components.successanderror')

    @if ($issue->category == 'material_problems')
    <div class="boxmaterial" style="margin-bottom: 20px;">
        <h4 style="margin-bottom: 15px;">แก้ไขรายการปัญหา</h4>
        <form action="{{ route('admin.projects.issues.update', $issue->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">เลือกวัสดุ</label>
                    <select name="withdrawal_item_damaged" class="form-input" required>
                        <option value="">เลือกวัสดุที่เบิกไปแล้ว</option>
                        @foreach($withdrawnItems as $item)
                            <option value="{{ $item->id }}" {{ $issue->withdrawal_item_damaged == $item->id ? 'selected' : '' }}>
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
                                | ล็อต: {{ $item->lot }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ภาพวัสดุ (เลือกใหม่หากต้องการเปลี่ยนรูป)</label>
                    <input type="file" name="image_data" class="form-input" accept="image/*">
                </div>
            
                <div class="form-group">
                    <label class="form-label">จำนวนที่</label>
                    <input type="number" name="damaged_amount" class="form-input" min="1" value="{{ $issue->damaged_amount }}" required>
                </div>
                
                <div class="form-group" style="margin-top: 15px;">
                    <label class="form-label">รายละเอียดเพิ่มเติม (ถ้ามี)</label>
                    <textarea name="description" class="form-input" rows="3">{{ $issue->description }}</textarea>
                </div>

                <div style="margin-top: 15px;">
                    <button type="submit" class="btn btn-secondary">อัปเดตข้อมูล</button>
                </div>
            </div>
        </form>
    </div>
    @elseif ($issue->category == 'general_problems')
    <div class="boxmaterial" style="margin-bottom: 20px;">
        <h4 style="margin-bottom: 15px;">แก้ไขรายการปัญหา</h4>
        <form action="{{ route('admin.projects.issues.updateIssuegeneralproblems', $issue->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="box-control">

                <div class="form-group" style="margin-top: 15px;">
                    <label class="form-label">รายละเอียดเพิ่มเติม (ถ้ามี)</label>
                    <textarea name="description" class="form-input" rows="3">{{ $issue->description }}</textarea>
                </div>

                <div style="margin-top: 15px;">
                    <button type="submit" class="btn btn-secondary">อัปเดตข้อมูล</button>
                </div>
            </div>
        </form>
    </div>
    @endif

    
</div>
@endsection