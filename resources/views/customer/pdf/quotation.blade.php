<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    @include('customer.pdf._style')
    <style>
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .info-table td {
            padding: 2px 8px;
            border: none;
            vertical-align: top;
        }

        .info-divider {
            width: 1px;
            background: #ccc;
            padding: 0 !important;
        }

        .sum-table-full {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            margin-top: 6px;
        }

        .sum-table-full td {
            padding: 5px 10px;
            border: 1px solid #000;
        }

        .sum-label-full {
            text-align: right;
            width: 78%;
        }

        .sum-value-full {
            text-align: right;
            width: 22%;
            white-space: nowrap;
        }

        .sum-total-full td {
            font-weight: bold;
            font-size: 17px;
            border-top: 2px solid #000;
        }
    </style>
</head>

<body>
    <div class="page">

        <div class="doc-header">
            <div class="col-left">
                <div class="shop-name">ร้านทรายคำวัสดุ</div>
                <p style="font-size:15px;">193 หมู่ 13 ต.โพธิ์ไทร อ.โพธิ์ไทร จ.อุบลราชธานี 34340</p>
                <p style="font-size:15px;">เลขประจำตัวผู้เสียภาษี 5342100004679</p>
                <p style="font-size:15px;">โทร 0895284181</p>
            </div>
            <div class="col-right">
                <div class="doc-type">ใบเสนอราคา</div>
                <div class="doc-meta">
                    <p>เลขที่ {{ $project->quotation_number ?? '-' }}</p>
                    <p>วันที่
                        @if($quotation?->created_at)
                        {{ \Carbon\Carbon::parse($quotation->created_at)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') }}
                        @else
                        {{ \Carbon\Carbon::now()->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <hr class="line-thick">
        <hr class="line-thin">

        <table class="info-table">
            <tr>
                <td style="width:48%; padding-bottom:4px;">
                    <strong>ผู้ขาย</strong>
                </td>
                <td class="info-divider" style="width:1%;"></td>
                <td style="width:48%; padding-bottom:4px; padding-left:16px;">
                    <strong>ลูกค้า (ผู้ซื้อ)</strong>
                </td>
            </tr>
            <tr>
                <td>ไพร์ยนร์ ทรายคำ</td>
                <td class="info-divider"></td>
                <td style="padding-left:16px;">{{ ($project->customer->prefix ?? '') }}{{ $project->customer->first_name ?? '' }} {{ $project->customer->last_name ?? '' }}</td>
            </tr>
            <tr>
                <td>ร้านทรายคำวัสดุ</td>
                <td class="info-divider"></td>
                <td style="padding-left:16px;">
                    {{ $project->customer->house_number ?? '' }}
                    ต.{{ $project->customer->tambon->name_th ?? '-' }}
                    อ.{{ $project->customer->amphure->name_th ?? '-' }}
                    จ.{{ $project->customer->province->name_th ?? '-' }}
                    {{ $project->customer->tambon->zip_code ?? '' }}
                </td>
            </tr>
            <tr>
                <td>โทร 0895284181</td>
                <td class="info-divider"></td>
                <td style="padding-left:16px;">โทร {{ $project->customer->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="info-divider"></td>
                <td style="padding-left:16px;">เลขประจำตัวผู้เสียภาษี {{ $project->customer->tax_id_number ?? '-' }}</td>
            </tr>
        </table>

        <p style="font-size:15px; margin-bottom:10px;">
            <strong>ชื่องาน:</strong> {{ $project->projectname->name ?? '-' }}
        </p>

        <hr class="line-thin">

        <div class="section-title">1. ค่าใช้จ่ายอื่นๆ</div>
        <table>
            <thead>
                <tr>
                    <th width="7%">ลำดับ</th>
                    <th>ประเภทค่าใช้จ่าย</th>
                    <th width="20%">จำนวนเงิน (บาท)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($project->projectexpenses as $expense)
                <tr>
                    <td class="td-center">{{ $loop->iteration }}</td>
                    <td>{{ $expense->type?->name }}</td>
                    <td class="td-right">{{ number_format($expense->amount, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="td-center">ไม่มีรายการค่าใช้จ่ายเพิ่มเติม</td>
                </tr>
                @endforelse
                <tr class="td-total">
                    <td colspan="2" class="td-right">รวม</td>
                    <td class="td-right">{{ number_format($totalExpenses, 2) }} บาท</td>
                </tr>
            </tbody>
        </table>
        <div class="section-title">2. ค่าแรงช่าง</div>
        <table>
            <thead>
                <tr>
                    <th width="6%">ลำดับ</th>
                    <th>รายการ</th>
                    <th width="10%">จำนวนวัน</th>
                    <th width="16%">ค่าแรง/วัน/คน (บาท)</th>
                    <th width="12%">จำนวนช่าง</th>
                    <th width="16%">รวม (บาท)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="td-center">1</td>
                    <td>ค่าแรงวันออกสำรวจหน้างาน</td>
                    <td class="td-center">1 วัน</td>
                    <td class="td-right">{{ number_format($project->labor_cost_surveying, 2) }}</td>
                    <td class="td-center">1 คน</td>
                    <td class="td-right">{{ number_format($project->labor_cost_surveying, 2) }}</td>
                </tr>
                <tr>
                    <td class="td-center">2</td>
                    <td>ค่าแรงวันติดตั้ง</td>
                    <td class="td-center">{{ $project->estimated_work_days }} วัน</td>
                    <td class="td-right">{{ number_format($project->daily_labor_rate, 2) }}</td>
                    <td class="td-center">{{ $installerCount }} คน</td>
                    <td class="td-right">{{ number_format($project->estimated_work_days * $project->daily_labor_rate * $installerCount, 2) }}</td>
                </tr>
                <tr class="td-total">
                    <td colspan="5" class="td-right">รวม</td>
                    <td class="td-right">{{ number_format($totalLabor, 2) }} บาท</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title" style="margin-top: 200px;">3. รายการชุดที่สั่ง</div>
        <table>
            <thead>
                <tr>
                    <th width="6%">ลำดับ</th>
                    <th>ชื่อสินค้า/บริการ</th>
                    <th width="18%">ขนาด</th>
                    <th width="10%">จำนวน</th>
                    <th width="15%">ราคาต่อชุด (บาท)</th>
                </tr>
            </thead>
            <tbody>
                @php $grandProductTotal = 0; @endphp
                @if($quotation && $quotation->items->count() > 0)
                @foreach($quotation->items as $item)
                @php $grandProductTotal += $item->total_price; @endphp
                <tr>
                    <td class="td-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td class="td-center">{{ $item->description }}</td>
                    <td class="td-center">{{ $item->quantity }} ชุด</td>
                    <td class="td-right">{{ number_format($item->unit_price, 2) }}</td>
                </tr>
                @endforeach
                @endif
                <tr class="td-total">
                    <td colspan="4" class="td-right">รวม</td>
                    <td class="td-right">{{ number_format($grandProductTotal ?: $sumProductTotal, 2) }} บาท</td>
                </tr>
            </tbody>
        </table>

        <table class="sum-table-full">
            <tr>
                <td class="sum-label-full">ค่าใช้จ่ายอื่นๆ</td>
                <td class="sum-value-full">{{ number_format($totalExpenses, 2) }}</td>
            </tr>
            <tr>
                <td class="sum-label-full">ค่าสินค้า/บริการ</td>
                <td class="sum-value-full">{{ number_format($sumProductTotal, 2) }}</td>
            </tr>
            <tr>
                <td class="sum-label-full">ค่าแรงช่าง</td>
                <td class="sum-value-full">{{ number_format($totalLabor, 2) }}</td>
            </tr>
            <tr>
                <td class="sum-label-full">ค่าบริการ 20%</td>
                <td class="sum-value-full">{{ number_format($sevic, 2) }}</td>
            </tr>
            <tr>
                <td class="sum-label-full">รวมก่อนภาษี</td>
                <td class="sum-value-full">{{ number_format($sumincome, 2) }}</td>
            </tr>
            <tr>
                <td class="sum-label-full">ภาษีมูลค่าเพิ่ม 7%</td>
                <td class="sum-value-full">{{ number_format($pricevat, 2) }}</td>
            </tr>
            <tr class="sum-total-full">
                <td class="sum-label-full"><strong>ยอดรวมสุทธิ</strong></td>
                <td class="sum-value-full"><strong>{{ number_format($sumvattotal, 2) }} บาท</strong></td>
            </tr>
        </table>

        <div class="sign-section">
            <div class="sign-cell">
                <span class="sign-line"></span>
                <p>ผู้เสนอราคา</p>
                <p>( ไพร์ยนร์ ทรายคำ )</p>
                <p style="font-size:14px; color:#555;">วันที่ ............/............/............</p>
            </div>
            <div class="sign-cell">
                <span class="sign-line"></span>
                <p>ผู้อนุมัติ / ผู้ซื้อ</p>
                <p>( {{ ($project->customer->first_name ?? '') }} {{ ($project->customer->last_name ?? '') }} )</p>
                <p style="font-size:14px; color:#555;">วันที่ ............/............/............</p>
            </div>
        </div>

        <div class="remark">
            <strong>หมายเหตุ:</strong> ใบเสนอราคานี้มีอายุ 7 วัน นับจากวันที่ออกเอกสาร
        </div>

    </div>
</body>

</html>