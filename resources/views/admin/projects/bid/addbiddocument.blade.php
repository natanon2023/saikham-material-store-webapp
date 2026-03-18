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

@php 
    $quotation = \App\Models\Quotation::where('project_id', $project->id)->latest()->first();
    if ($quotation) {
        $totalExpenses   = $quotation->total_expense_amount;
        $sumProductTotal = $quotation->total_product_amount;
        $totalLabor      = $quotation->total_labor_amount;
        $sevic           = $quotation->service_charge_amount;
        $sumincome       = $totalExpenses + $sumProductTotal + $totalLabor + $sevic;
        $pricevat        = $quotation->vat_amount;
        $sumvattotal     = $quotation->grand_total;
    } else {
        $totalExpenses = 0;
        foreach ($project->projectexpenses as $expense) {
            $totalExpenses += $expense->amount;
        }

        $totalLabor = $project->labor_cost_surveying + ($project->estimated_work_days * $project->daily_labor_rate);

        $sumProductTotal = 0;
        foreach ($project->customerneed as $need) {
            $sumProductTotal += ($need->quantity * $need->calculated_total);
        }

        $sumtotal    = $sumProductTotal + $totalExpenses + $totalLabor;
        $sevic       = $sumtotal * 0.20;
        $sumincome   = $sumtotal + $sevic;
        $pricevat    = $sumincome * 0.07;
        $sumvattotal = $sumincome + $pricevat;
    }
@endphp

<div class="main-content">
    <div class="boxmaterial hide-on-print" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0;">ออกใบเสนอราคา</h3>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('admin.projects.alldetail', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
            @if (in_array($project->status,$statusopenqtc))
                <form action="{{ route('admin.projects.reviseQuotation', $project->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะขอแก้ไขใบเสนอราคา?\n\nระบบจะทำการลบใบเสนอราคาฉบับนี้ทิ้ง เพื่อให้คุณกลับไปแก้ไขรายการสินค้าและออกบิลใหม่ได้');">
                    @csrf
                    <button type="submit" class="btn btn-warning" >
                        ขอแก้ไขใบเสนอราคา
                    </button>
                </form>
            @endif
            <button type="button" onclick="window.print()" class="btn" style="background-color: #17a2b8; color: white;">
                <i class="fas fa-print"></i> พิมพ์ใบเสนอราคา
            </button>
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
                <h4 style="margin: 0 0 5px 0; color:#17a2b8;">ใบเสนอราคา</h4>
                <p><strong>เลขที่</strong> {{ $project->quotation_number ?? '-' }}</p>
                <p><strong>งาน </strong> {{ $project->projectname->name ?? '-' }}</p>
                <p><strong>วันที่ออก </strong>
                    @if($quotation && $quotation->created_at)
                        {{ \Carbon\Carbon::parse($quotation->created_at)->locale('th')->translatedFormat('d F') . ' ' . (\Carbon\Carbon::parse($quotation->created_at)->year + 543) }}
                    @else
                        {{ \Carbon\Carbon::now()->locale('th')->translatedFormat('d F') . ' ' . (\Carbon\Carbon::now()->year + 543) }}
                    @endif
                </p>
                <p><strong>ผู้ขาย</strong> ไพร์ยนร์ ทรายคำ</p>
            </div>
        </div>
        <hr>
        <div style="display: flex; justify-content: space-between; margin-top: 5px; margin-bottom: 10px;">
            <div>
                <h4 style="margin: 0 0 5px 0;"><strong>ลูกค้า</strong></h4>
                <p>{{ $project->customer->first_name ?? '-' }} {{ $project->customer->last_name ?? '-' }}</p>
                <p>
                    {{ $project->customer->house_number ?? '-' }}
                    ต.{{ $project->customer->tambon->name_th ?? '-' }}
                    อ.{{ $project->customer->amphure->name_th ?? '-' }}
                    จ.{{ $project->customer->province->name_th ?? '-' }}
                    {{ $project->customer->tambon->zip_code ?? '-' }}
                </p>
                <p>เบอร์มือถือ {{ $project->customer->phone ?? '-' }} | เลขประจำตัวผู้เสียภาษี {{ $project->customer->tax_id_number ?? '-' }}</p>
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
            @forelse($project->projectexpenses as $expense)
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td>{{ $expense->type?->name }}</td>
                    <td align="right">{{ number_format($expense->amount, 2) }}</td>
                </tr>
                
            @empty
                <tr><td colspan="3" align="center">ไม่มีรายการค่าใช้จ่ายเพิ่มเติม</td></tr>
            @endforelse
            <tr style="background-color: #f9f9f9 !important;">
                <td colspan="2" align="right"><strong>รวม</strong></td>
                <td align="right">
                    <strong>{{ number_format($project->projectexpenses->sum('amount'), 2) }} บาท</strong>
                </td>
            </tr>
        </table>

        <h3 style="margin-top: 15px; color: black;">ค่าแรงช่าง</h3>
        <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">
            <tr align="center" style="background-color: #f2f2f2 !important;">
                <td>ลำดับ</td>
                <td>รายการ</td>
                <td>จำนวนวันทำงาน</td>
                <td>อัตราค่าแรงต่อวัน</td>
                <td>ค่าแรงรวม</td>
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
            </tr>
            

            @if($quotation && $quotation->items->count() > 0)
            @php $grandTotal = 0; @endphp
                @foreach ($quotation->items as $item)
                    @php $grandTotal += $item->total_price; @endphp
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td>{{ $item->item_name }}</td>          
                        <td align="center">{{ $item->description }}</td>
                        <td align="center">{{ $item->quantity }} ชุด</td> 
                        <td align="right">{{ number_format($item->unit_price, 2) }}</td>
                    </tr>
                    
                @endforeach
                    <tr style="background-color: #f9f9f9 !important;">
                        <td colspan="4" align="right"><strong>รวม</strong></td>
                        <td align="right"><strong>{{ number_format($grandTotal, 2) }} บาท</strong></td>  
                    </tr>
            @else
                @foreach ($project->customerneed as $need)
                    @php $rowTotal = $need->quantity * $need->calculated_total; @endphp
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td>{{ $need->productset->productSetName->name }}</td>
                        <td align="center">{{ $need->projectImage->imagetype->name ?? '-' }} ({{ $need->width }} x {{ $need->height }} ซม.)</td>
                        <td align="center">{{ $need->quantity }} ชุด</td>
                        <td align="right">{{ number_format($need->calculated_total, 2) }}</td>
                        <td align="right">{{ number_format($rowTotal, 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </table>

        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <table width="40%" border="0" cellspacing="0" cellpadding="5">
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

        <div style="margin-top: 40px; display: flex; justify-content: space-between; text-align: center;">
            <div style="width: 40%;">
                <br><br>
                <p>ลงชื่อ <strong style="border-bottom: 1px solid #000000; padding: 0 20px; margin: 0 10px;">ไพร์ยนร์</strong> ผู้เสนอราคา</p>
                <p>( ไพร์ยนร์ ทรายคำ )</p>
            </div>
            <div style="width: 40%;">
                <br><br>
                <p>ลงชื่อ <strong style="border-bottom: 1px solid #000000; padding: 0 20px; margin: 0 10px;">{{ $project->customer->first_name ?? '' }}</strong> ผู้อนุมัติ</p>
                <p>( {{ ($project->customer->first_name ?? '') . ' ' . ($project->customer->last_name ?? '') }} )</p>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <p><strong>หมายเหตุ:</strong> ยืนยันราคาภายใน 7 วัน</p>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="boxmaterial">
        <h3>ข้อมูลสต็อกวัสดุเพื่อการเสนอราคา (แบบละเอียด)</h3>
        <hr>

        @if($quotation && $quotation->quotationMaterials->count() > 0)
            <table border="1" width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse; margin-top: 20px;">
                <thead style="background-color: #000000 !important; color: white;">
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
                    @foreach ($quotation->quotationMaterials as $mat)
                    <tr>
                        <td align="center"><b>{{ $mat->material_type }}</b></td>
                        <td align="center">{{ $mat->description }}</td>
                        <td align="center">{{ $mat->lot_number }}</td>
                        <td align="right">{{ number_format($mat->unit_price, 2) }}</td>
                        <td align="center">{{ $mat->quantity }}</td>
                        <td align="right"><b>{{ number_format($mat->total_price, 2) }}</b></td>
                        <td align="center">{{ $mat->remark }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            @foreach ($project->customerneed as $need)
            <div style="display: flex; justify-content: space-between; margin-top:15px; margin-bottom: 10px;">
                <h4 style="margin: 0;">ชุดงาน: {{ $need->productset->productSetName->name }} ({{ $need->width }} × {{ $need->height ?? $need->high }} ซม.)</h4>
                <h4 style="margin: 0;">ราคาต่อชุด : {{ number_format($need->calculated_total, 2) }} บาท</h4>
            </div>
            <table border="1" width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
                <thead style="background-color: #000000 !important; color: white;">
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
                            @if($mat->aluminiumItem)
                                {{ $mat->aluminiumItem->aluminiumType->name ?? 'ไม่ระบุประเภท' }} <br> สี {{ $mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-' }}
                            @elseif($mat->glassItem)
                                {{ optional($mat->glassItem->glassType)->name ?? 'กระจก' }} <br>
                                สี {{ optional($mat->glassItem->colourItem)->name ?? '-' }}
                            @elseif($mat->accessoryItem)
                                {{ $mat->accessoryItem->accessoryType->name ?? '-' }}
                            @elseif($mat->consumableItem)
                                {{ $mat->consumableItem->consumabletype->name ?? '-' }}
                            @else
                                {{ $mat->name }}
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
        @endif
    </div>

</div>
@endsection