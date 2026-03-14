@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="margin-bottom: 20px; display: flex; justify-content: space-between;">
        <h3>รายงานปัญหาโครงการ</h3>
        <a href="{{ route('admin.projects.index', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px;">เพิ่มการรายงานปัญหา</h3>
        <form action="{{ route('admin.projects.issues.store', $project->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ประเภทปัญหา <span style="color:red;">*</span></label>
                    <select name="category" id="issue_category" class="form-input" required onchange="toggleIssueForm()">
                        <option value="">เลือกประเภทปัญหา</option>
                        <option value="material_damage">วัสดุชำรุดหรือเสียหาย</option>
                        <option value="general">ปัญหาทั่วไป</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">หัวข้อปัญหา <span style="color:red;">*</span></label>
                    <input type="text" name="title" class="form-input" placeholder="เช่น กระจกบานเลื่อนแตก" required>
                </div>

                <div class="form-group">
                    <label class="form-label">รูปภาพประกอบ (ถ้ามี)</label>
                    <input type="file" name="images[]" class="form-input" accept="image/*" multiple>
                </div>
            </div>

            <div id="material_damage_section" style="display: none;  padding: 15px;   margin-top: 10px; margin-bottom: 10px;">
                <div style="margin-bottom: 10px; font-weight: bold; ">ระบุวัสดุที่เสียหายจากประวัติการเบิก</div>
                <div class="box-control" style="margin-bottom: 0;">
                    <div class="form-group">
                        <label class="form-label">เลือกวัสดุ <span style="color:red;">*</span></label>
                        <select name="withdrawal_item_id" id="withdrawal_item_id" class="form-input">
                            <option value="">เลือกวัสดุที่เบิกไปแล้ว</option>
                            @foreach($withdrawnItems as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->material->material_type ?? '-' }} | ล็อต: {{ $item->lot }} | (เบิกไป: {{ $item->quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">จำนวนที่เสียหาย <span style="color:red;">*</span></label>
                        <input type="number" name="damaged_quantity" id="damaged_quantity" class="form-input" min="1" placeholder="จำนวน">
                    </div>
                </div>
                
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label class="form-label">รายละเอียดเพิ่มเติม</label>
                <textarea name="description" class="form-input" rows="3" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
            </div>

            <div style="margin-top: 15px;">
                <button type="submit" class="btn btn-secondary">บันทึก</button>
            </div>
        </form>
    </div>

    <div class="boxmaterial">
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between;">
            ประวัติการแจ้งปัญหา
        </div>
        <table>
            <tr align="center">
                <th>วันที่แจ้ง</th>
                <th>ประเภท</th>
                <th>หัวข้อปัญหา</th>
                <th>สถานะ</th>
            </tr>
            @forelse($issues as $issue)
            <tr>
                <td align="center">{{ \Carbon\Carbon::parse($issue->created_at)->format('d/m/Y H:i') }}</td>
                <td align="center">
                    @if($issue->category == 'material_damage')
                        วัสดุเสียหาย
                    @else
                        ปัญหาทั่วไป
                    @endif
                </td>
                <td>{{ $issue->title }}</td>
                <td align="center">
                    @if($issue->status == 'pending')
                        รอดำเนินการ
                    @elseif($issue->status == 'in_progress')
                        กำลังแก้ไข
                    @elseif($issue->status == 'resolved')
                        แก้ไขแล้ว
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" align="center" style="padding: 20px; color: gray;">
                    ยังไม่มีการแจ้งปัญหาสำหรับโครงการนี้
                </td>
            </tr>
            @endforelse
        </table>
    </div>

</div>

<script>
    function toggleIssueForm() {
        var category = document.getElementById('issue_category').value;
        var section = document.getElementById('material_damage_section');
        var inputId = document.getElementById('withdrawal_item_id');
        var inputQty = document.getElementById('damaged_quantity');

        if (category === 'material_damage') {
            section.style.display = 'block';
            inputId.required = true;
            inputQty.required = true;
        } else {
            section.style.display = 'none';
            inputId.required = false;
            inputQty.required = false;
            inputId.value = '';
            inputQty.value = '';
        }
    }
</script>
@endsection