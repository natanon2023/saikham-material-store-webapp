@extends('layouts.admin')

@section('content')
<div class="main-content" >
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;" class="boxmaterial">
        <h3 style="margin: 0; color: #333;">เติมสต็อกวัสดุตามประเภทวัสดุ</h3>
        <a href="{{ route('admin.projects.restockpage', $project->id) }}" class="btn btn-primary" >ย้อนกลับ</a>
    </div>

    <form action="{{ route('admin.projects.processrestock', $project->id) }}" method="POST">
        @csrf

        @foreach ($groupedMaterials as $type => $mats)
            <div class="boxmaterial" style="margin-bottom: 20px;">
                
                <div style="margin-bottom: 20px;">
                    <h4 style="margin-bottom: 10px;">{{ $type }}</h4>
                    <div style="background: #fff;  font-size: 0.9em; ">
                        <b style="color:#333;">รายการที่เลือก</b>
                        <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                            @foreach($mats as $mat)
                                <li>
                                    {{ $mat->display_detail }}
                                    <input type="hidden" name="restock_group[{{ $type }}][material_ids][]" value="{{ $mat->id }}">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 15px; background: #fff; padding: 15px;  border: 1px solid #eee;">
                    <div style="flex: 1; min-width: 150px;">
                        <label style="font-size: 0.85em;  display:block; margin-bottom:5px;">ร้านตัวแทนจำหน่าย</label>
                        <select name="restock_group[{{ $type }}][dealer_id]" class="form-input" required style="width: 100%; padding: 6px;">
                            <option value="">เลือกร้าน</option>
                            @foreach($dealers as $dealer)
                                <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 100px;">
                        <label style="font-size: 0.85em;  display:block; margin-bottom:5px;">จำนวน (ต่อชิ้น)</label>
                        <input type="number" name="restock_group[{{ $type }}][qty]" class="form-input" required min="1" style="width: 100%; padding: 6px;">
                    </div>
                    <div style="width: 120px;">
                        <label style="font-size: 0.85em;  display:block; margin-bottom:5px;">ต้นทุน/หน่วย</label>
                        <input type="number" name="restock_group[{{ $type }}][price]" class="form-input" step="0.01" required min="0" style="width: 100%; padding: 6px;">
                    </div>

                    @if($type == 'อลูมิเนียม')
                        <div style="width: 120px;">
                            <label style="font-size: 0.85em;  display:block; margin-bottom:5px;">ความยาว (ม.)</label>
                            <input type="number" step="0.01" name="restock_group[{{ $type }}][length_meter]" class="form-input" required style="width: 100%; padding: 6px;">
                        </div>
                    @elseif($type == 'กระจก')
                        <div style="width: 90px;">
                            <label style="font-size: 0.85em;  display:block; margin-bottom:5px;">กว้าง (ม.)</label>
                            <input type="number" step="0.01" name="restock_group[{{ $type }}][width_meter]" class="form-input" required style="width: 100%; padding: 6px;">
                        </div>
                        <div style="width: 90px;">
                            <label style="font-size: 0.85em;  display:block; margin-bottom:5px;">ยาว (ม.)</label>
                            <input type="number" step="0.01" name="restock_group[{{ $type }}][length_meter]" class="form-input" required style="width: 100%; padding: 6px;">
                        </div>
                        <div style="width: 90px;">
                            <label style="font-size: 0.85em;  display:block; margin-bottom:5px;">หนา (มม.)</label>
                            <input type="number" step="0.1" name="restock_group[{{ $type }}][thickness]" class="form-input" required style="width: 100%; padding: 6px;">
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-secondary" >
                บันทึกสต็อกทั้งหมด
            </button>
        </div>
    </form>
</div>
@endsection