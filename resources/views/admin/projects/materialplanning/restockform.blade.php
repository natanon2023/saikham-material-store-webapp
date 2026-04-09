@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;" class="boxmaterial">
        <h3 style="margin: 0; color: #333;">กรอกข้อมูลเพื่อเติมสต็อกวัสดุ</h3>
        <a href="{{ route('admin.projects.restockpage', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <form action="{{ route('admin.projects.processrestock', $project->id) }}" method="POST">
        @csrf

        @foreach ($selectedMaterials as $mat)
            @php
                $matName = '-';
                if ($mat->material_type == 'อลูมิเนียม') {
                    $matName = ($mat->aluminiumItem->aluminiumType->name ?? '') . " (" . ($mat->aluminiumItem->aluminumSurfaceFinish->name ?? '') . ")";
                } elseif ($mat->material_type == 'กระจก') {
                    $matName = ($mat->glassItem->glassType->name ?? '') . " (" . ($mat->glassItem->colourItem->name ?? '') . ")";
                } elseif ($mat->material_type == 'อุปกรณ์เสริม') {
                    $matName = $mat->accessoryItem->accessoryType->name ?? '';
                } elseif ($mat->material_type == 'วัสดุสิ้นเปลือง') {
                    $matName = $mat->consumableItem->consumabletype->name ?? '';
                }
            @endphp

            <div class="boxmaterial" style="margin-bottom: 20px;">
                <div style="margin-bottom: 15px;">
                    <h4 style="margin: 0; color: #333; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                        [{{ $mat->material_type }}] {{ $matName }}
                    </h4>
                </div>

                <table width="100%" class="restock-table" data-material-id="{{ $mat->id }}" style="border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; font-size: 13px; color: #666;">
                            <th style="padding-bottom: 10px;">ร้านตัวแทนจำหน่าย</th>
                            <th style="padding-bottom: 10px; width: 120px;">จำนวน</th>
                            <th style="padding-bottom: 10px; width: 150px;">ราคา/หน่วย</th>
                            
                            @if($mat->material_type == 'อลูมิเนียม')
                                <th style="padding-bottom: 10px; width: 150px;">ความยาว (ม.)</th>
                            @elseif($mat->material_type == 'กระจก')
                                <th style="padding-bottom: 10px; width: 120px;">กว้าง (ม.)</th>
                                <th style="padding-bottom: 10px; width: 120px;">ยาว (ม.)</th>
                                <th style="padding-bottom: 10px; width: 120px;">หนา (มม.)</th>
                            @endif
                            
                        </tr>
                    </thead>
                    <tbody class="variation-body">
                        <tr class="variation-row">
                            <td style="padding-bottom: 10px; padding-right: 10px;">
                                <select name="restock[{{ $mat->id }}][0][dealer_id]" class="form-input" required style="width: 100%;">
                                    <option value="">เลือกร้าน</option>
                                    @foreach($dealers as $dealer)
                                        <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding-bottom: 10px; padding-right: 10px;">
                                <input type="number" name="restock[{{ $mat->id }}][0][qty]" class="form-input" min="1" required style="width: 100%;">
                            </td>
                            <td style="padding-bottom: 10px; padding-right: 10px;">
                                <input type="number" name="restock[{{ $mat->id }}][0][price]" class="form-input" step="0.01" min="0" required style="width: 100%;">
                            </td>

                            @if($mat->material_type == 'อลูมิเนียม')
                                <td style="padding-bottom: 10px; padding-right: 10px;">
                                    <input type="number" name="restock[{{ $mat->id }}][0][length_meter]" class="form-input" step="0.01" min="0.01" required style="width: 100%;">
                                </td>
                            @elseif($mat->material_type == 'กระจก')
                                <td style="padding-bottom: 10px; padding-right: 10px;">
                                    <input type="number" name="restock[{{ $mat->id }}][0][width_meter]" class="form-input" step="0.01" min="0.01" required style="width: 100%;">
                                </td>
                                <td style="padding-bottom: 10px; padding-right: 10px;">
                                    <input type="number" name="restock[{{ $mat->id }}][0][length_meter]" class="form-input" step="0.01" min="0.01" required style="width: 100%;">
                                </td>
                                <td style="padding-bottom: 10px; padding-right: 10px;">
                                    <input type="number" name="restock[{{ $mat->id }}][0][thickness]" class="form-input" step="0.1" min="0.1" required style="width: 100%;">
                                </td>
                            @endif

                        </tr>
                    </tbody>
                </table>
                <div style="margin-top: 5px;">
                    <button type="button" class="btn btn-secondary btn-add-variation" style="font-size: 12px; padding: 6px 12px;">
                        + เพิ่มรายการสเปคอื่น
                    </button>
                </div>
            </div>
        @endforeach

        <div style="display: flex; justify-content: flex-end; margin-bottom: 40px;">
            <button type="submit" class="btn btn-secondary" style="padding: 12px 30px; font-size: 16px;">
                บันทึกสต็อกทั้งหมด
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-add-variation').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const table = this.closest('.boxmaterial').querySelector('.restock-table');
            const tbody = table.querySelector('.variation-body');
            const newIndex = Date.now();
            const firstRow = tbody.querySelector('.variation-row');
            const newRow = firstRow.cloneNode(true);
            
            newRow.querySelectorAll('input, select').forEach(function(input) {
                input.value = '';
                if (input.name) {
                    input.name = input.name.replace(/\[\d+\](\[[a-zA-Z_]+\])$/, `[${newIndex}]$1`);
                }
            });
            
            const deleteBtn = newRow.querySelector('.btn-remove-row');
            if(deleteBtn) {
                deleteBtn.style.display = 'inline-block';
            }
            
            tbody.appendChild(newRow);
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-row')) {
            const row = e.target.closest('.variation-row');
            const tbody = row.closest('.variation-body');
            
            if (tbody.querySelectorAll('.variation-row').length > 1) {
                row.remove();
            }
        }
    });
});
</script>
@endsection