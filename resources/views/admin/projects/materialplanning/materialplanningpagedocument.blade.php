@extends('layouts.admin')

@section('content')
<style>
    @media print {
        /* ... CSS เดิมของคุณแจ็กกี้ ... */
        .sidebar, .navbar, header, footer, .hide-on-print, .btn { display: none !important; }
        body { background-color: white !important; margin: 0 !important; }
        .boxmaterial { box-shadow: none !important; border: none !important; }
    }
</style>

<div class="main-content">
    <div class="hide-on-print">
        @include('components.successanderror')
    </div>
    
    <div class="boxmaterial hide-on-print" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; ">
            <h3 style="margin: 0;">ใบสั่งซื้อวัสดุ</h3>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.projects.alldetail', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
                <button type="button" onclick="window.print()" class="btn btn-danger" style="background-color: #00CED1; color: white; border: none;">
                    <i class="fas fa-print"></i> พิมพ์ใบสั่งซื้อวัสดุ
                </button>
            </div>
        </div>
    </div>

    <div class="boxmaterial" style="margin-top: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h3 style="margin-bottom: 5px;">รายการวัสดุที่ต้องสั่งซื้อ</h3>
            <p style="margin: 0;"><strong>งาน:</strong> {{ $project->projectname->name }} | <strong>วันที่ออกบิล:</strong> {{ $project->projectPurchase ? $project->projectPurchase->created_at->locale('th')->translatedFormat('d F') . ' ' . ($project->projectPurchase->created_at->year + 543) : '-' }}</p>
        </div>
        <hr>

        <table border="1" width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
            <thead style="background:#333; color:#fff; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                <tr align="center">
                    <th width="5%">ลำดับ</th>
                    <th width="15%">ประเภท</th>
                    <th>รายละเอียดวัสดุ</th>
                    <th width="15%">ราคา/หน่วย</th>
                    <th width="10%">จำนวน</th>
                    <th width="15%">ราคารวม</th>
                </tr>
            </thead>

            <tbody>
                @if($project->projectPurchase && $project->projectPurchase->items->count() > 0)
                    @foreach ($project->projectPurchase->items as $item)
                    @php $mat = $item->material; @endphp
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td align="center"><b>{{ $mat->material_type }}</b></td>
                        <td align="center">
                            @if($mat->aluminiumItem)
                                {{ $mat->aluminiumItem->aluminiumType->name ?? '-' }} <br>
                                {{ $mat->aluminiumItem->aluminumSurfaceFinish->name ?? '-' }} ยาว 6 เมตร
                            @elseif($mat->glassItem)
                                {{ $mat->glassItem->glassType->name ?? '-' }} <br>
                                สี{{ $mat->glassItem->colourItem->name ?? '-' }} ขนาด 2*2 เมตร
                            @elseif($mat->accessoryItem)
                                {{ $mat->accessoryItem->accessoryType->name ?? '-' }}
                            @elseif($mat->consumableItem)
                                {{ $mat->consumableItem->consumabletype->name ?? '-' }}
                            @endif
                        </td>
                        <td align="center">{{ number_format($item->unit_price, 2) }}</td>
                        <td align="center">{{ $item->quantity }}</td>
                        <td align="center"><b>{{ number_format($item->total_price, 2) }}</b></td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" align="center" style="padding: 20px; color: gray;">ไม่พบรายการที่ต้องสั่งซื้อ (วัสดุในสต็อกอาจเพียงพอแล้ว)</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
            <table width="40%" border="1" cellspacing="0" cellpadding="8" style="border-collapse: collapse;">
                <tr style="background-color: #f2f2f2 !important; font-size: 1.2em; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    <td align="right"><strong>ยอดรวมสุทธิ:</strong></td>
                    <td align="right">
                        <strong>{{ number_format($project->projectPurchase->total_amount ?? 0, 2) }} บาท</strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection