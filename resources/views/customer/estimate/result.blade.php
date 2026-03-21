@extends('layouts.customer')
@section('content')

<style>
    .est-wrap  { max-width: 760px; margin: 2rem auto; padding: 0 1rem 3rem; }
    .est-card  { background: var(--bg-card); border: 1px solid var(--border); margin-bottom: 1.25rem; }
    .est-head  { padding: 0.75rem 1.25rem; border-bottom: 2px solid var(--gold, #D4B483); display:flex; justify-content:space-between; align-items:center; }
    .est-head h4 { margin:0; font-size:0.95rem; font-weight:700; color:var(--navy,#334E68); }
    .est-body  { padding: 1rem 1.25rem; }

    .result-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
    .result-table th { padding:8px 10px; border-bottom:2px solid var(--border); font-size:0.8rem; font-weight:600; color:var(--text-muted); text-align:left; background:var(--bg-page); }
    .result-table td { padding:9px 10px; border-bottom:1px solid var(--border); }
    .result-table tr:last-child td { border-bottom:none; }
    .no-stock { font-size:0.75rem; color:#856404; background:#fff3cd; padding:2px 7px; display:inline-block; margin-left:4px; }

    .sum-block { padding: 1rem 1.25rem; }
    .sum-row   { display:flex; justify-content:space-between; padding:5px 0; border-bottom:1px solid var(--border); font-size:0.9rem; }
    .sum-row:last-child { border-bottom:none; }
    .sum-label { color:var(--text-muted); }
    .sum-value { font-weight:500; }
    .sum-total { font-size:1.1rem; font-weight:700; color:var(--navy,#334E68); background:var(--bg-page); padding:10px 1.25rem; display:flex; justify-content:space-between; border-top:2px solid var(--border); }

    .notice { font-size:0.8rem; color:var(--text-muted); padding:0.75rem 1.25rem; border-top:1px dashed var(--border); line-height:1.6; }

    .actions { display:flex; gap:10px; }
    .btn-back { padding:8px 20px; border:1.5px solid var(--border); background:var(--bg-card); color:var(--text-primary); font-size:0.88rem; text-decoration:none; cursor:pointer; }
    .btn-pdf  { padding:8px 20px; background:var(--navy,#334E68); color:#fff; border:none; font-size:0.88rem; cursor:pointer; font-weight:600; }
    .btn-pdf:hover { background:#243a50; }
</style>

<div style="max-width: 1100px; margin: 0 auto; padding: 0 1rem;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
        <div>
            <h3 style="font-size:1.2rem; font-weight:700; color:var(--navy,#334E68); margin:0 0 4px 0; border-left:4px solid var(--gold,#D4B483); padding-left:0.75rem;">
                ใบประเมินราคาเบื้องต้น
            </h3>
            <div style="font-size:0.85rem; color:var(--text-muted); padding-left:0.9rem;">
                {{ $result['productset']->productSetName->name ?? '-' }}
                — ขนาด {{ $result['width'] }} × {{ $result['height'] }} ซม.
            </div>
        </div>
        <div class="actions">
            <a href="{{ url()->previous() }}" class="btn btn-primary">แก้ไขขนาด</a>
        </div>
    </div>

    <div class="est-card">
        <div class="est-head"><h4>รายการวัสดุที่ใช้</h4></div>
        <div style="padding:0;">
            <table class="result-table">
                <thead>
                    <tr>
                        <th>ประเภท / รายละเอียด</th>
                        <th style="text-align:center; width:60px;">ล็อต</th>
                        <th style="text-align:center; width:60px;">จำนวน</th>
                        <th style="text-align:right; width:100px;">ราคา/หน่วย</th>
                        <th style="text-align:right; width:100px;">รวม</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result['items'] as $item)
                    <tr>
                        <td>
                            <div style="font-size:0.78rem; color:var(--text-muted);">{{ $item['type'] }}</div>
                            <div style="font-weight:500;">
                                {{ $item['description'] }}
                                @if(!$item['has_stock'])
                                    <span class="no-stock">ราคาประมาณ</span>
                                @endif
                            </div>
                            <div style="font-size:0.78rem; color:var(--text-muted); margin-top:2px;">{{ $item['remark'] }}</div>
                        </td>
                        <td style="text-align:center; color:var(--text-muted); font-size:0.82rem;">{{ $item['lot'] }}</td>
                        <td style="text-align:center;">{{ $item['qty'] }}</td>
                        <td style="text-align:right;">{{ number_format($item['unit_price'], 2) }}</td>
                        <td style="text-align:right; font-weight:500;">{{ number_format($item['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="est-card">
        <div class="est-head"><h4>สรุปราคาประเมิน</h4></div>
        <div class="sum-block">
            <div class="sum-row">
                <span class="sum-label">ราคาวัสดุรวม</span>
                <span class="sum-value">{{ number_format($result['subtotal'], 2) }} บาท</span>
            </div>
            <div class="sum-row">
                <span class="sum-label">ค่าบริการ 20%</span>
                <span class="sum-value">{{ number_format($result['serviceCharge'], 2) }} บาท</span>
            </div>
            <div class="sum-row">
                <span class="sum-label">รวมก่อนภาษี</span>
                <span class="sum-value">{{ number_format($result['beforeVat'], 2) }} บาท</span>
            </div>
            <div class="sum-row">
                <span class="sum-label">ภาษีมูลค่าเพิ่ม 7%</span>
                <span class="sum-value">{{ number_format($result['vat'], 2) }} บาท</span>
            </div>
        </div>
        <div class="sum-total">
            <span>ราคาประเมินทั้งสิ้น</span>
            <span>{{ number_format($result['grandTotal'], 2) }} บาท</span>
        </div>
        <div class="notice">
            * ราคานี้เป็นการประเมินเบื้องต้นเท่านั้น ยังไม่รวมค่าแรงช่างและค่าใช้จ่ายอื่นๆ<br>
            * รายการที่แสดง "ราคาประมาณ" หมายถึงไม่มีสินค้าในสต็อก ราคาอาจเปลี่ยนแปลงได้<br>
            * กรุณาติดต่อร้านเพื่อรับใบเสนอราคาจริง
        </div>
    </div>

</div>

@endsection