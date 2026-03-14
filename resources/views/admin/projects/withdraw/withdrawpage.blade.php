@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial">
        <div style="display: flex; justify-content: space-between; align-items: center; ">
            <h3>รายละเอียดการเบิก</h3>
            <a href="{{ route('admin.projects.index',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
    </div>

    <div class="boxmaterial" style="margin-top: 20px;">
        <form action="{{ route('admin.projects.withdrawstore', $project->id) }}" method="POST">
            @csrf
            <div class="box-control">
                <div class="form-group">
                <label for="withdrawn_by" class="form-label">โครงการ</label>
                <div class="form-input">
                    {{ $project->projectname->name }}
                </div>
            </div>

            <div class="form-group">
                <label for="withdrawn_by" class="form-label">ลูกค้า</label>
                <div class="form-input">
                    {{ $project->customer->first_name }} {{ $project->customer->last_name }}
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px; max-width: 100%;">
                <label for="withdrawn_by" class="form-label">เลือกผู้เบิกวัสดุ </label>
                <select name="withdrawn_by" id="withdrawn_by" class="form-input" required>
                    <option value="">กรุณาเลือกผู้เบิก</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} {{ $user->last_name }}</option>
                    @endforeach
                </select>
            </div>

           
            </div>
            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-secondary">
                    ยืนยันการเบิกวัสดุ
                </button>
            </div>
             
        </form>
    </div>
    <div class="boxmaterial" style="margin-top: 20px;">
        <h3>ข้อมูลวัสดุที่จะเบิก</h3>
        <hr>

        @foreach ($project->customerneed as $need)
        <table>
            <thead style="background:#333;color:#fff;">
                <tr align="center">
                    <th>ลำดับ</th>
                    <th>ประเภท</th>
                    <th>รายละเอียด</th>
                    <th>ล็อต</th>
                    <th>จำนวนใช้</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($need->productset->productsetitem->sortBy('material.material_type') as $item)
                @php $mat = $item->material; @endphp
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td align="center">
                        <b>{{ $mat->material_type }}</b>
                    </td>
                    <td align="center">
                        @if($mat->aluminiumItem)
                        {{ $mat->aluminiumItem->aluminiumType->name ?? '-' }} <br>
                        สี {{ $mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-' }}

                        @elseif($mat->glassItem)
                        {{ $mat->glassItem->glassType->name ?? '-' }} <br>
                        สี {{ $mat->glassItem->colourItem->name ?? '-' }}

                        @elseif($mat->accessoryItem)
                        {{ $mat->accessoryItem->accessoryType->name ?? '-' }}

                        @elseif($mat->consumableItem)
                        {{ $mat->consumableItem->consumabletype->name ?? '-' }}
                        @endif
                    </td>
                    <td align="center">
                        {{ $item->calculated_lot }}
                    </td>
                    <td align="center">
                        {{ $item->calculated_qty }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

        @endforeach
    </div>

    <br>




</div>

@endsection