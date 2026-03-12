@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="box">
        <div style="margin-top: 30px; margin-bottom: 20px; display: flex; justify-content: space-between;">
           <h4>แก้ไข{{ $material->material_type }}</h4> 
           <div style="width: max-content; height: max-content;">
                <a href="{{ route('admin.materials.historystock') }}" class="btn btn-primary">ประวัติสต็อก</a>
                <a href="{{ route('admin.materials.showdetailmaterial',$material->id) }}" class="btn btn-primary">รายละเอียดวัสดุ</a>
           </div>
           
        </div>
        <form action="{{ route('admin.materials.editstock', $price->id) }}" method="POST">
            @csrf

            <div class="box-control">

                <div class="form-group">
                    <label class="form-label">จำนวนสต็อก</label>
                    <input type="number" name="quantity" class="form-input"
                        value="{{ $price->quantity }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">ร้านตัวแทนจำหน่าย</label>
                    <select name="dealer_id" class="form-input" required>
                        @foreach($dealers as $dealer)
                        <option value="{{ $dealer->id }}"
                            {{ $price->dealer_id == $dealer->id ? 'selected' : '' }}>
                            {{ $dealer->name }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label class="form-label">ราคาต้นทุนต่อหน่วย</label>
                    <input type="number" step="0.01" name="price"
                        value="{{ $price->price }}" class="form-input">
                </div>

                @if($material->material_type == 'อลูมิเนียม')
                <div class="form-group">
                    <label class="form-label">ความยาว (เมตร)</label>
                    <input type="number" name="length_meter"
                        value="{{ $price->aluminiumLength->length_meter ?? '' }}"
                        class="form-input" required>
                </div>
                @endif

                @if($material->material_type == 'กระจก')
                <div class="form-group">
                    <label class="form-label">ความกว้าง</label>
                    <input type="number" name="width_meter"
                        value="{{ $price->glassSize->width_meter ?? '' }}"
                        class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">ความยาว</label>
                    <input type="number" name="length_meter"
                        value="{{ $price->glassSize->length_meter ?? '' }}"
                        class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">ความหนา</label>
                    <input type="number" name="thickness"
                        value="{{ $price->glassSize->thickness ?? '' }}"
                        class="form-input">
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">เหตุผลในการแก้ไข</label>
                    <textarea name="reason" class="form-input" required></textarea>
                </div>
                
                <button class="btn btn-secondary">
                    บันทึกการแก้ไข
                </button>

            </div>

            

        </form>
    </div>
    <hr>
    <h4>ประวัติการแก้ไข</h4>

    <table class="table table-bordered w-full">
        <thead>
            <tr>
                <th>เวลา</th>
                <th>ผู้แก้ไข</th>
                <th>รายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            @forelse($editLogs as $log)
            <tr>
                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $log->user->name }}</td>
                <td>
                    <table class="table table-sm">
                        <tr>
                            <td>จำนวน</td>
                            <td>{{ $log->old_quantity }}</td>
                            <td>=></td>
                            <td>{{ $log->new_quantity }}</td>
                        </tr>

                        @if($log->old_price != $log->new_price)
                        <tr>
                            <td>ราคา</td>
                            <td>{{ $log->old_price }}</td>
                            <td>=></td>
                            <td>{{ $log->new_price }}</td>
                        </tr>
                        @endif

                        @if($log->old_length_meter != $log->new_length_meter)
                        <tr>
                            <td>ความยาว</td>
                            <td>{{ $log->old_length_meter }}</td>
                            <td>=></td>
                            <td>{{ $log->new_length_meter }}</td>
                        </tr>
                        @endif

                        @if($log->old_width_meter)
                        <tr>
                            <td>ความกว้าง</td>
                            <td>{{ $log->old_width_meter }}</td>
                            <td>=></td>
                            <td>{{ $log->new_width_meter }}</td>
                        </tr>
                        @endif

                        @if($log->old_thickness)
                        <tr>
                            <td>ความหนา</td>
                            <td>{{ $log->old_thickness }}</td>
                            <td>=></td>
                            <td>{{ $log->new_thickness }}</td>
                        </tr>
                        @endif
                    </table>

                    <small><b>เหตุผล:</b> {{ $log->reason }}</small>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">ยังไม่มีประวัติการแก้ไข</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection