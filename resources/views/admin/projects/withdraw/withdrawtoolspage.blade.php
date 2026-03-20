@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #333;">เบิกเครื่องมือช่าง</h3>
        <a href="{{ route('admin.projects.withdrawdetails', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <form action="{{ route('admin.projects.withdrawtoolsstore', $project->id) }}" method="POST">
        @csrf
        
        <div class="boxmaterial" style="margin-bottom: 20px;">
            <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="font-size: 0.85em; display:block; margin-bottom:5px;">โครงการ</label>
                    <input type="text" class="form-input" value="{{ $project->projectname->name }}" readonly style="width: 100%; ">
                </div>
                
                <div style="flex: 1; min-width: 250px;">
                    <label style="font-size: 0.85em; display:block; margin-bottom:5px;">เลือกช่างผู้เบิก</label>
                    <select name="withdrawn_by" class="form-input" required style="width: 100%; padding: 6px;">
                        <option value="">กรุณาเลือกช่าง</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} {{ $user->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="boxmaterial">
            <h3 style="margin-bottom: 15px;">รายการเครื่องมือช่างในคลัง</h3>
            
            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
                <thead style="background: #333; color: #fff;">
                    <tr align="left">
                        <th width="5%" style="text-align: center;">เลือก</th>
                        <th width="40%">ชื่อเครื่องมือช่าง</th>
                        <th width="15%" style="text-align: center;">ล็อต</th>
                        <th width="20%" style="text-align: center;">จำนวนที่มีในสต็อก</th>
                        <th width="20%" style="text-align: center;">ระบุจำนวนที่เบิก</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($toolsstock as $stock)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td align="center">
                                <input type="checkbox" name="selected_items[]" value="{{ $stock->id }}" style="transform: scale(1.2); cursor: pointer;">
                            </td>
                            <td>
                                <b>{{ $stock->material->toolItem->toolType->name ?? $stock->material->name ?? 'เครื่องมือช่าง' }}</b>
                            </td>
                            <td align="center">{{ $stock->lot }}</td>
                            <td align="center"><b style="color: #1e8e3e;">{{ $stock->quantity }}</b></td>
                            <td align="center">
                                <input type="number" name="custom_qty[{{ $stock->id }}]" value="1" min="1" max="{{ $stock->quantity }}" class="form-input" style="width: 80px; text-align: center;">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" align="center" style="padding: 20px; ">ไม่มีเครื่องมือช่างเหลือในสต็อก</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-secondary">ยืนยันเบิกเครื่องมือช่าง</button>
            </div>
        </div>
    </form>
</div>
@endsection