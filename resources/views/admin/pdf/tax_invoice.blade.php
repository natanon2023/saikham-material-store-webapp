<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    @include('customer.pdf._style')
</head>

<body>
    <div class="page">

        <div class="doc-header">
            <div class="col-left">
                <div class="shop-name">ร้านทรายคำวัสดุ</div>
                <p>193 หมู่ 13 ต.โพธิ์ไทร อ.โพธิ์ไทร จ.อุบลราชธานี 34340</p>
                <p>เลขประจำตัวผู้เสียภาษี 5342100004679 (สำนักงานใหญ่)</p>
                <p>เบอร์มือถือ 0895284181</p>
            </div>
            <div class="col-right">
                <div class="doc-type">ใบกำกับภาษี</div>
                <p>เลขที่เอกสาร {{ $project->tax_invoice_number ?? '-' }}</p>
                <p>อ้างอิงใบเสนอราคา {{ $project->quotation_number ?? '-' }}</p>
                <p>วันที่ออก {{ \Carbon\Carbon::now()->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') }}</p>
            </div>
        </div>
        <hr>

        <h4>ชื่อลูกค้า</h4>
        <p>{{ $project->customer->first_name ?? '' }} {{ $project->customer->last_name ?? '' }}</p>
        <p>
            {{ $project->customer->house_number ?? '' }}
            ต.{{ $project->customer->tambon->name_th ?? '-' }}
            อ.{{ $project->customer->amphure->name_th ?? '-' }}
            จ.{{ $project->customer->province->name_th ?? '-' }}
            {{ $project->customer->tambon->zip_code ?? '' }}
        </p>
        <p>โทร {{ $project->customer->phone ?? '-' }} | เลขผู้เสียภาษี {{ $project->customer->tax_id_number ?? '-' }}</p>
        <hr>

        <div class="section-title">รายการ (งาน {{ $project->projectname->name ?? '-' }})</div>
        <table>
            <thead>
                <tr>
                    <th width="7%">ลำดับ</th>
                    <th>รายการ</th>
                    <th width="28%">จำนวนเงิน (บาท)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>รับเหมาติดตั้งกระจกอลูมิเนียม</td>
                    <td class="text-right">{{ number_format($sumincome, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="sum-table-full">
            <tr>
                <td class="sum-label-full">มูลค่าสินค้า/บริการ</td>
                <td class="sum-value-full">{{ number_format($sumincome, 2) }} บาท</td>
            </tr>
            <tr>
                <td class="sum-label-full">ภาษีมูลค่าเพิ่ม 7%</td>
                <td class="sum-value-full">{{ number_format($pricevat, 2) }} บาท</td>
            </tr>
            <tr class="sum-total-full">
                <td class="sum-label-full">ยอดรวมสุทธิ</td>
                <td class="sum-value-full"><strong>{{ number_format($sumvattotal, 2) }} </strong>บาท</td>
            </tr>
        </table>



        <div class="sign-section" style="width:50%; margin-left:auto;">
            <div class="sign-cell">
                ลงชื่อ
                <span class="sign-line"></span>
                <p>ผู้รับเงิน</p>
                <p>( ไพร์ยนร์ ทรายคำ )</p>
            </div>
        </div>

    </div>
</body>

</html>