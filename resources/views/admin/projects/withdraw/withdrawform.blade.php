@extends('layouts.admin')

@section('content')
<div class="main-content">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;" class="boxmaterial">
        <h3 style="margin: 0; color: #333;">ยืนยันการเบิกวัสดุ</h3>
        <a href="{{ route('admin.projects.withdrawpage', $project->id) }}" class="btn btn-primary" >ย้อนกลับ</a>
    </div>

    <form action="{{ route('admin.projects.withdrawstore', $project->id) }}" method="POST">
        @csrf
        
        @foreach($selecteditems as $id_item)
            <input type="hidden" name="selected_items[]" value="{{ $id_item }}">
        @endforeach

        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="font-size: 0.85em; display:block; margin-bottom:5px;">โครงการ</label>
                    <input type="text" class="form-input" value="{{ $project->projectname->name }}" readonly >
                </div>
                
                <div style="flex: 1; min-width: 250px;">
                    <label style="font-size: 0.85em; display:block; margin-bottom:5px; ">เลือกผู้เบิก</label>
                    <select name="withdrawn_by" class="form-input" required style="width: 100%; padding: 6px;">
                        <option value="">กรุณาเลือกผู้เบิก</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} {{ $user->last_name }}
                                @if ( $user->role == 'admin')
                                (แอดมิน)
                                @else
                                (ช่าง)
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="boxmaterial">
            <h4 style="margin-bottom: 10px;">สรุปรายการที่กำลังจะเบิก</h4>
            <div style="background: #fff; font-size: 0.9em;">
                <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; border: 1px solid #eee;">
                    <thead style="background:#f9f9f9;">
                        <tr align=" center">
                            <th>ลำดับ</th>
                            <th>ประเภท</th>
                            <th>รายละเอียด</th>
                            <th>จำนวนที่จะเบิก</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itemstowithdraw as $index => $item)
                        <tr style="border-bottom: 1px solid #eee; text-align:center;">
                            <td>{{ $index + 1 }}</td>
                            <td><b>{{ $item->material->material_type }}</b></td>
                            <td align="left">{{ $item->calculated_description }}</td>
                            <td>{{ $item->calculated_qty }}</b></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <button type="submit" class="btn btn-secondary">ยืนยันการเบิก</button>
        </div>
    </form>
</div>
@endsection