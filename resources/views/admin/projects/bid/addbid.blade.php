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

        .page-break {
            page-break-before: always;
        }
    }
</style>

<div class="main-content">
    
    <div class="boxmaterial hide-on-print" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0;">ออกใบเสนอราคา</h3>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            
            @if ($project->status == 'pending_quotation')
                <a href="{{ route('admin.projects.index',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
                <form action="{{ route('admin.projects.satatuswaitingapproval') }}" method="post" style="margin: 0;">
                    @csrf
                    <input type="hidden" value="{{ $project->id }}" name="id">
                    <button type="submit" class="btn btn-secondary">ยืนยันการเสนอราคา</button>
                </form>
                <button type="button" onclick="window.print()" class="btn btn-danger" style="background-color: #dc3545; color: white;">
                    <i class="fas fa-print"></i> ส่งออกเป็น PDF
                </button>
            @else
                <a href="{{ route('admin.projects.alldetail',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
                <button type="button" onclick="window.print()" class="btn btn-danger" style="background-color: #dc3545; color: white;">
                    <i class="fas fa-print"></i> ส่งออกเป็น PDF
                </button>
            @endif
        </div>
    </div>
    
    <div class="box" style="padding: 30px;">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <h4 style="margin: 0 0 5px 0;">ร้านทรายคำวัสดุ</h4>
                <p>193 หมู่ 13 ต.โพธิ์ไทร อ.โพธิ์ไทร จ.อุบลราชธานี 34340</p>
                <p>เลขประจำตัวผู้เสียภาษี 5342100004679</p>
                <p>เบอร์มือถือ 0895284181</p>
            </div>
            <div style="text-align: right;">
                <h4 style="margin: 0 0 5px 0;">ใบเสนอราคา</h4>
                <p><strong>เลขที่</strong> {{ $project->quotation_number }} </p>
                <p><strong>งาน </strong> {{ $project->projectname->name }}</p>
                <p><strong>วันที่ออก </strong> {{ date('d-m-Y') }}</p>
                <p><strong>ผู้ขาย</strong> ไพร์ยนร์ ทรายคำ</p>
            </div>
        </div>
        <hr>

        <div style="display: flex; justify-content: space-between; margin-top: 5px; margin-bottom: 10px;">
            <div>
                <h4 style="margin: 0 0 5px 0;"><strong>ลูกค้า</strong></h4>
                <p>{{ $project->customer->first_name.' '.$project->customer->last_name }}</p>
                <p>
                    {{ $project->customer->house_number }}
                    ต.{{ $project->customer->tambon->name_th}}
                    อ.{{ $project->customer->amphure->name_th }}
                    จ.{{ $project->customer->province->name_th}}
                    {{ $project->customer->tambon->zip_code }}
                </p>
                <p>เบอร์มือถือ {{ $project->customer->phone }} | เลขประจำตัวผู้เสียภาษี {{ $project->customer->tax_id_number }}</p>
            </div>
        </div>
        <hr>

        <h3 style="margin-top: 15px; color: black;">ค่าใช้จ่ายอื่นๆ</h3>
        <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">
            <tr align="center" style="background-color: #f2f2f2 !important;">
                <td width="10%">ลำดับ</td>
                <td>ประเภทค่าใช้จ่าย</td>
                <td width="20%">จำนวนเงิน (บาท)</td>
            </tr>
            @php $totalExpenses = 0; @endphp
            @foreach($project->projectexpenses as $expense)
                @php $totalExpenses += $expense->amount; @endphp
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td>{{ $expense->type?->name }}</td>
                    <td align="right">{{ number_format($expense->amount, 2) }}</td>
                </tr>
            @endforeach
            @if($project->projectexpenses->isEmpty())
                <tr><td colspan="3" align="center">ไม่มีรายการค่าใช้จ่ายเพิ่มเติม</td></tr>
            @endif
        </table>

        <h3 style="margin-top: 15px; color: black;">ค่าแรงช่าง</h3>
        <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">
            <tr align="center" style="background-color: #f2f2f2 !important;">
                <td>ลำดับ</td>
                <td>รายการ</td>
                <td>จำนวนวันทำงาน</td>
                <td>อัตราค่าแรงต่อวัน</td>
                <td>ค่าแรงต่อวันรวม</td>
            </tr>
            <tr>
                <td align="center">1</td>
                <td>วันออกสำรวจหน้างาน</td>
                <td align="center">1</td>
                <td align="center">{{ number_format($project->labor_cost_surveying, 2) }}</td>
                <td align="right">{{ number_format($project->labor_cost_surveying, 2) }}</td>
            </tr>
            <tr>
                <td align="center">2</td>
                <td>วันติดตั้ง</td>
                <td align="center">{{ $project->estimated_work_days }}</td>
                <td align="center">{{ number_format($project->daily_labor_rate, 2) }}</td>
                <td align="right">{{ number_format($project->estimated_work_days * $project->daily_labor_rate, 2) }}</td>
            </tr>
            @php 
                $totalLabor = $project->labor_cost_surveying + ($project->estimated_work_days * $project->daily_labor_rate); 
            @endphp
            <tr style="background-color: #f9f9f9 !important;">
                <td colspan="4" align="right"><strong>รวม</strong></td>
                <td align="right"><strong>{{ number_format($totalLabor, 2) }} บาท</strong></td>
            </tr>
        </table>

        <h3 style="margin-top: 15px; color: black;">รายการชุดที่สั่ง</h3>
        <table border="1" width="100%" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
            <tr align="center" style="background-color: #f2f2f2 !important;">
                <td width="5%">ลำดับ</td>
                <td width="30%">รายการ</td>
                <td width="20%">ขนาด (ซม.)</td>
                <td width="10%">จำนวน</td>
                <td width="10%">ราคาต่อชุด</td>
                <td width="20%">ราคารวม (บาท)</td>
            </tr>
            @php $sumProductTotal = 0; @endphp
            @foreach ($project->customerneed as $need)
                @php 
                    $rowTotal = $need->quantity * $need->calculated_total;
                    $sumProductTotal += $rowTotal; 
                @endphp
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td>{{ $need->productset->productSetName->name }}</td>
                    <td align="center">{{ $need->location }} ({{ $need->width }} x {{ $need->high }} ซม.)</td>
                    <td align="center">{{ $need->quantity }} ชุด</td>
                    <td align="right">{{ number_format($need->calculated_total, 2) }}</td>
                    <td align="right">{{ number_format($rowTotal, 2) }}</td>
                </tr>
            @endforeach
        </table>

        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <table width="40%" border="0" cellspacing="0" cellpadding="5">
                @php
                    $sumtotal = $sumProductTotal + $totalExpenses + $totalLabor;
                    $income = 0.20;
                    $sevic = $sumtotal * $income;
                    $sumincome = $sumtotal + $sevic;
                    $vatPercent = 0.07;
                    $pricevat = $sumincome * $vatPercent;
                    $sumvattotal = $sumincome + $pricevat;
                @endphp
                <tr>
                    <td align="right"><strong>ค่าใช้จ่ายอื่นๆ</strong></td>
                    <td align="right">{{ number_format($totalExpenses, 2) }} บาท</td>
                </tr>
                <tr>
                    <td align="right"><strong>รายการชุดที่สั่ง</strong></td>
                    <td align="right">{{ number_format($sumProductTotal, 2) }} บาท</td>
                </tr>
                <tr>
                    <td align="right"><strong>ค่าแรงช่าง</strong></td>
                    <td align="right">{{ number_format($totalLabor, 2) }} บาท</td>
                </tr>
                <tr>
                    <td align="right" style="border-top: 1px solid #ddd;"><strong>ค่าบริการ 20%</strong></td>
                    <td align="right" style="border-top: 1px solid #ddd;">{{ number_format($sevic, 2) }} บาท</td>
                </tr>
                <tr>
                    <td align="right" style="border-top: 1px solid #ddd;"><strong>รวมเป็นเงิน (ก่อน VAT)</strong></td>
                    <td align="right" style="border-top: 1px solid #ddd;">{{ number_format($sumincome, 2) }} บาท</td>
                </tr>
                <tr>
                    <td align="right"><strong>ภาษีมูลค่าเพิ่ม 7%</strong></td>
                    <td align="right">{{ number_format($pricevat, 2) }} บาท</td>
                </tr>
                <tr style="background-color: #f2f2f2 !important; font-size: 1.2em;">
                    <td align="right"><strong>ยอดสุทธิ</strong></td>
                    <td align="right"><strong>{{ number_format($sumvattotal, 2) }} บาท</strong></td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 20px;">
            <p><strong>หมายเหตุ:</strong> ยืนยันราคาภายใน 7 วัน</p>
        </div>
    </div>
    
    <div class="page-break"></div>
    
    <div class="boxmaterial">
        <h3>ข้อมูลสต็อกวัสดุเพื่อการเสนอราคา (แบบละเอียด)</h3>
        <hr>

        @foreach ($project->customerneed as $need)
        <div style="display: flex; justify-content: space-between; margin-top:15px; margin-bottom: 10px;">
            <h4 style="margin: 0;">ชุดงาน: {{ $need->productset->productSetName->name }} ({{ $need->width }} × {{ $need->high }} ซม.)</h4>
            <h4 style="margin: 0;">ราคาต่อชุด : {{ number_format($need->calculated_total, 2) }} บาท</h4>
        </div>

        <table border="1" width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
            <thead style="background-color: #000000 !important;">
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
                @foreach ($need->productset->productsetitem->sortBy('material.material_type') as $item)
                @php $mat = $item->material; @endphp
                <tr>
                    <td align="center"><b>{{ $mat->material_type }}</b></td>
                    <td align="center">
                        @if($mat->aluminiumItem) {{ $mat->aluminiumItem->aluminiumType->name ?? '-' }} <br> สี {{ $mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-' }}
                        @elseif($mat->glassItem) {{ $mat->glassItem->glassType->name ?? '-' }} <br> สี {{ $mat->glassItem->colourItem->name ?? '-' }}
                        @elseif($mat->accessoryItem) {{ $mat->accessoryItem->accessoryType->name ?? '-' }}
                        @elseif($mat->consumableItem) {{ $mat->consumableItem->consumabletype->name ?? '-' }}
                        @endif
                    </td>
                    <td align="center">{{ $item->calculated_lot }}</td>
                    <td align="right">{{ number_format($item->calculated_unit_price, 2) }}</td>
                    <td align="center">{{ $item->calculated_qty }}</td>
                    <td align="right"><b>{{ number_format($item->calculated_total, 2) }}</b></td>
                    <td align="center">{{ $item->calculated_remark }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    </div>

</div>
@endsection