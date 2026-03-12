@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')
    <div class="boxmaterial">
        <div style="display: flex; justify-content: space-between; align-items: center; ">
            <h3>วางแผนวัสดุ</h3>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>
    </div>

    <div class="boxmaterial" style="margin-top: 20px;">
        <h3>ข้อมูลรายการวัสดุที่ต้องซื้อเพิ่ม | งาน{{ $project->projectname->name }}</h3>
        <hr>

        @foreach ($project->customerneed as $need)
        <div style=" display: flex; justify-content: end; margin-top:20px; margin-bottom: 20px;">
            @php
                $sumbuy = $need->productset->productsetitem->whereIn('calculated_lot', ['ไม่มีของหรือขนาดไม่พอ', 'ไม่มีของ/ขนาดไม่พอ', 'วัสดุหมด','สินค้าหมด'])->sum('calculated_total')
            @endphp
            <h4>
                รวม : {{ number_format($sumbuy,2) }} บาท
            </h4>
        </div>

        <table border="1" width="100%" cellpadding="5" cellspacing="0">
            <thead style="background:#333;color:#fff;">
                <tr align="center">
                    <th>ประเภท</th>
                    <th>รายละเอียด</th>
                    <th>ล็อต</th>
                    <th>ราคา/หน่วย</th>
                    <th>จำนวนใช้</th>
                    <th>ราคารวม</th>
                    <th>หมายเหตุ</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($need->productset->productsetitem->whereIn('calculated_lot', ['ไม่มีของหรือขนาดไม่พอ', 'ไม่มีของ/ขนาดไม่พอ', 'วัสดุหมด','สินค้าหมด'])->sortBy('material.material_type') as $item)
                @php $mat = $item->material; @endphp
                <tr>
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
                    <td align="right">
                        {{ number_format($item->calculated_unit_price, 2) }}
                    </td>
                    <td align="center">
                        {{ $item->calculated_qty }}
                    </td>
                    <td align="right">
                        <b>{{ number_format($item->calculated_total, 2) }}</b>
                    </td>
                    <td align="center">
                        {{ $item->calculated_remark }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

        @endforeach

        <div style="margin-top: 20px; ">
            <form action="{{ route('admin.projects.updatestatuswaitingpurchase', $project->id) }}" method="post">
            @csrf
                <button class="btn btn-secondary">
                    วางแผนวัสดุ
                </button>
            </form>

        </div>
    </div>

</div>

@endsection