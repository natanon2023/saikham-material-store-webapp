@extends('layouts.admin')
@section('content')
<style>
    @media print {
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; text-shadow: none !important; }
        span, p, h1, h2, h3, h4, h5, h6, b, strong, td, th { background-color: transparent !important; }
        .sidebar, .navbar, header, footer, .hide-on-print, .btn { display: none !important; }
        body, .main-content { background-color: white !important; margin: 0 !important; padding: 0 !important; font-size: 14px !important; }
        .box, .boxmaterial { box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 !important; }
        h3, h4 { margin-top: 10px !important; margin-bottom: 5px !important; }
        p { margin-bottom: 2px !important; }
        hr { margin: 5px 0 !important; }
    }
</style>

@php 
    $sumvattotal = $project->quotation->grand_total;

    $pricevat = $project->quotation->vat_amount;

    $sumincome = $sumvattotal-$pricevat;
@endphp

<div class="main-content">
    <div class="boxmaterial hide-on-print" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0;">ออกใบกำกับภาษี</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.projects.alldetail', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
            <button type="button" onclick="window.print()" class="btn btn-danger" style="background-color: #dc3545; color: white;">
                <i class="fas fa-print"></i> พิมพ์ใบกำกับภาษี
            </button>
        </div>
    </div>
    
    <div class="box" style="padding: 30px;">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <h4 style="margin: 0 0 5px 0;">ร้านทรายคำวัสดุ</h4>
                <p>193 หมู่ 13 ต.โพธิ์ไทร อ.โพธิ์ไทร จ.อุบลราชธานี 34340<br>เลขประจำตัวผู้เสียภาษี 5342100004679 (สำนักงานใหญ่)<br>เบอร์มือถือ 0895284181</p>
            </div>
            <div style="text-align: right;">
                <h4 style="margin: 0 0 5px 0; color: #dc3545;">ใบกำกับภาษี</h4>
                <p><strong>เลขที่เอกสาร</strong> {{ $project->tax_invoice_number ?? '-' }} <br>
                <strong>อ้างอิงใบเสนอราคา</strong> {{ $project->quotation_number }} <br>
                <strong>วันที่ออก</strong> {{ date('d-m-Y') }}</p>
            </div>
        </div>
        <hr>

        <div style="margin-top: 5px; margin-bottom: 10px;">
            <h4 style="margin: 0 0 5px 0;"><strong>ชื่อลูกค้า</strong></h4>
            <p>{{ $project->customer->first_name.' '.$project->customer->last_name }}<br>
            {{ $project->customer->house_number }} ต.{{ $project->customer->tambon->name_th}} 
            อ.{{ $project->customer->amphure->name_th }} จ.{{ $project->customer->province->name_th}} 
            {{ $project->customer->tambon->zip_code }}<br>
            เบอร์มือถือ {{ $project->customer->phone }} | เลขประจำตัวผู้เสียภาษี {{ $project->customer->tax_id_number ?? '-' }}</p>
        </div>
        <hr>

        <h3 style="margin-top: 15px;">รายการ (งาน {{ $project->projectname->name }})</h3>
        <table border="1" width="100%" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
            <tr align="center" style="background-color: #f2f2f2 !important;">
                <td width="10%">ลำดับ</td><td width="50%">รายการ</td><td width="40%">จำนวนเงินรวม (บาท)</td>
            </tr>
            <tr>
                <td align="center">1</td>
                <td>รับเหมาติดตั้งกระจกอลูมิเนียม</td>
                <td align="right">{{ number_format($sumincome, 2) }}</td>
            </tr>
        </table>

        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <table width="40%" border="0" cellspacing="0" cellpadding="5">
                <tr><td align="right"><strong>มูลค่าสินค้า/บริการ</strong></td><td align="right">{{ number_format($sumincome, 2) }} บาท</td></tr>
                <tr><td align="right"><strong>ภาษีมูลค่าเพิ่ม 7%</strong></td><td align="right">{{ number_format($pricevat, 2) }} บาท</td></tr>
                <tr style="background-color: #fdf0f0 !important; font-size: 1.2em;">
                    <td align="right"><strong>ยอดรวมสุทธิ</strong></td><td align="right"><strong>{{ number_format($sumvattotal, 2) }} บาท</strong></td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 50px; display: flex; justify-content: flex-end; text-align: center;">
            <div style="width: 40%;">
                <p style="margin-bottom: 5px;">ลงชื่อ   <strong style="border-bottom: 1px solid #000000; padding: 0 20px; margin: 0 10px;">ไพร์ยนร์</strong>  ผู้รับเงิน</p>
                <p>( ไพร์ยนร์ ทรายคำ )</p>
            </div>
        </div>
    </div>
</div>
@endsection