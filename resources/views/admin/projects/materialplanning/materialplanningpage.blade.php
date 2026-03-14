@extends('layouts.admin')

@section('content')
<style>
    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            text-shadow: none !important;
        }
        span, p, h1, h2, h3, h4, h5, h6, b, strong, td, th {
            background-color: transparent !important;
        }
        ::selection {
            background: transparent !important;
            color: inherit !important;
        }
        .sidebar, .navbar, header, footer, .hide-on-print, .btn {
            display: none !important;
        }
        body, .main-content {
            background-color: white !important;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 14px !important; 
        }
        .box, .boxmaterial {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important; 
            margin: 0 !important;
        }
        h3, h4 { margin-top: 10px !important; margin-bottom: 5px !important; }
        p { margin-bottom: 2px !important; }
        hr { margin: 5px 0 !important; }
    }
</style>

<div class="main-content">
    <div class="hide-on-print">
        @include('components.successanderror')
    </div>
    
    <div class="boxmaterial hide-on-print" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; ">
            <h3 style="margin: 0;">วางแผนวัสดุ</h3>
            <div style="display: flex; gap: 10px;">
                @if ($project->status == 'material_planning')
                <a href="{{ route('admin.projects.index',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
                @else
                <a href="{{ route('admin.projects.alldetail',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
                @endif

                <button type="button" onclick="window.print()" class="btn btn-danger" style="background-color: #00CED1; color: white; border: none;">
                    <i class="fas fa-print"></i> พิมพ์ใบสั่งซื้อวัสดุ
                </button>
            </div>
        </div>
    </div>

    <div class="boxmaterial" style="margin-top: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h3 style="margin-bottom: 5px;">รายการวัสดุที่ต้องสั่งซื้อเพิ่ม</h3>
            <p style="margin: 0;"><strong>งาน:</strong> {{ $project->projectname->name }} | <strong>วันที่พิมพ์:</strong> {{ date('d/m/Y') }}</p>
        </div>
        <hr>

        @php 
            $grandTotalBuy = 0; 
        @endphp

        @foreach ($project->customerneed as $need)
        @php
            $sumbuy = $need->productset->productsetitem->whereIn('calculated_lot', ['ไม่มีของหรือขนาดไม่พอ', 'ไม่มีของ/ขนาดไม่พอ', 'ไม่มีของ'])->sum('calculated_total');
            $grandTotalBuy += $sumbuy;
        @endphp

        <div style=" display: flex; justify-content: space-between; align-items: flex-end; margin-top:20px; margin-bottom: 10px;">
            <h4 style="margin: 0;">ชุดงาน: {{ $need->productset->productSetName->name }}</h4>
            <h4 style="margin: 0;">
                รวม : {{ number_format($sumbuy,2) }} บาท
            </h4>
        </div>

        <table border="1" width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
            <thead style="background:#333; color:#fff; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                <tr align="center">
                    <th>ประเภท</th>
                    <th>รายละเอียด</th>
                    <th>ราคา/หน่วย</th>
                    <th>จำนวนใช้</th>
                    <th>ราคารวม</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($need->productset->productsetitem->whereIn('calculated_lot', ['ไม่มีของหรือขนาดไม่พอ', 'ไม่มีของ/ขนาดไม่พอ', 'ไม่มีของ'])->sortBy('material.material_type') as $item)
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
                    <td align="right">
                        {{ number_format($item->calculated_unit_price, 2) }}
                    </td>
                    <td align="center">
                        {{ $item->calculated_qty }}
                    </td>
                    <td align="right">
                        <b>{{ number_format($item->calculated_total, 2) }}</b>
                    </td>
                </tr>
                @endforeach

                @if($need->productset->productsetitem->whereIn('calculated_lot', ['ไม่มีของหรือขนาดไม่พอ', 'ไม่มีของ/ขนาดไม่พอ', 'ไม่มีของ'])->isEmpty())
                <tr>
                    <td colspan="6" align="center" style="color: gray;">มีวัสดุครบในสต็อกแล้ว (ไม่ต้องสั่งซื้อเพิ่ม)</td>
                </tr>
                @endif
            </tbody>
        </table>
        @endforeach

        <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
            <table width="40%" border="1" cellspacing="0" cellpadding="8" style="border-collapse: collapse;">
                <tr style="background-color: #f8d7da !important; font-size: 1.2em; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    <td align="right"><strong>ยอดรวมที่ต้องสั่งซื้อทั้งหมด:</strong></td>
                    <td align="right" ><strong>{{ number_format($grandTotalBuy, 2) }} บาท</strong></td>
                </tr>
            </table>
        </div>

        @if ($project->status == 'material_planning')
        <div class="hide-on-print" style="margin-top: 30px; display: flex; justify-content: flex-end;">
            <form action="{{ route('admin.projects.updatestatuswaitingpurchase', $project->id) }}" method="post">
            @csrf
                <button type="submit" class="btn btn-secondary" style="height:max-content;">
                    ยืนยันการวางแผนวัสดุ
                </button>
            </form>
        </div>
        @endif
        
    </div>

</div>
@endsection