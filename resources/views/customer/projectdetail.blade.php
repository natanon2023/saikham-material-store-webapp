@extends('layouts.customer')
@section('content')

<style>
    .detail-wrap {
        max-width: 960px;
        margin: 0 auto;
        padding: 0 1rem 3rem;
    }

    .detail-section {
        background: var(--bg-card);
        border: 1px solid var(--border);
        margin-bottom: 1.25rem;
    }

    .section-head {
        padding: 0.75rem 1.25rem;
        border-bottom: 2px solid var(--gold);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-head h4 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--navy);
    }

    .section-body { padding: 1.1rem 1.25rem; }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 24px;
    }

    @media (max-width: 580px) { .info-grid { grid-template-columns: 1fr; } }

    .info-row { font-size: 0.875rem; line-height: 1.7; }
    .info-row .lbl { color: var(--text-muted); font-size: 0.8rem; display: block; }
    .info-row .val { color: var(--text-main); font-weight: 500; }

    .status-pill {
        display: inline-block;
        padding: 4px 14px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #fff;
    }

    .detail-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .detail-table th {
        padding: 8px 10px;
        text-align: left;
        border-bottom: 2px solid var(--border);
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
        background: var(--bg-page);
    }
    .detail-table td { padding: 9px 10px; border-bottom: 1px solid var(--border); }
    .detail-table tr:last-child td { border-bottom: none; }

    .doc-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    @media (max-width: 640px) { .doc-grid { grid-template-columns: 1fr; } }

    .doc-card {
        border: 1px solid var(--border);
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .doc-card-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--navy);
    }

    .doc-card-sub {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .doc-card-num {
        font-size: 0.78rem;
        color: var(--text-muted);
    }

    .doc-card-num span {
        font-weight: 600;
        color: var(--navy);
    }

    .doc-pending {
        opacity: 0.45;
        filter: grayscale(1);
        pointer-events: none;
    }

    .badge-pending {
        display: inline-block;
        font-size: 0.75rem;
        padding: 2px 8px;
        background: var(--bg-page);
        color: var(--text-muted);
        border: 1px solid var(--border);
    }

    .before-after-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        padding: 1.1rem 1.25rem;
    }

    @media (max-width: 560px) {
        .before-after-grid { grid-template-columns: 1fr; }
    }

    .ba-item {
        border: 1px solid var(--border);
    }

    .ba-label {
        padding: 6px 10px;
        font-size: 0.78rem;
        font-weight: 600;
        border-bottom: 1px solid var(--border);
        background: var(--bg-page);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ba-label .ba-tag {
        font-size: 0.72rem;
        padding: 2px 8px;
        font-weight: 700;
    }

    .ba-tag-before {
        background: #fff3e0;
        color: #e65100;
    }

    .ba-tag-after {
        background: #e8f5e9;
        color: #1e8e3e;
    }

    .ba-img-wrap {
        height: 200px;
        overflow: hidden;
        background: var(--bg-page);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ba-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .ba-no-img {
        font-size: 0.82rem;
        color: var(--text-muted);
    }

    .ba-set-divider {
        grid-column: span 2;
        padding: 6px 0 2px;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--navy);
        border-bottom: 1px solid var(--gold);
        margin-bottom: 4px;
    }

    @media (max-width: 560px) {
        .ba-set-divider { grid-column: span 1; }
    }
</style>

<div class="detail-wrap">

    <div style="padding: 1.5rem 0 1rem; border-bottom: 1px solid var(--border); margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--navy); margin: 0 0 6px 0; border-left: 4px solid var(--gold); padding-left: 0.75rem;">
                {{ $project->projectname->name ?? 'รายละเอียดโครงการ' }}
            </h3>
        </div>
        @php
            $statusColors = [
                'pending_survey'      => '#D4AF37', 'waiting_survey'  => '#FF8C00',
                'surveying'           => '#1E90FF',  'pending_quotation' => '#E91E63',
                'waiting_approval'    => '#9C27B0',  'approved'        => '#2e9e42',
                'material_planning'   => '#00CED1',  'waiting_purchase'=> '#FF4500',
                'ready_to_withdraw'   => '#008080',  'materials_withdrawn' => '#8B4513',
                'installing'          => '#4CAF50',  'completed'       => '#708090',
                'cancelled'           => '#DC143C',
            ];
            $sc = $statusColors[$project->status] ?? '#999';
        @endphp
        <span class="status-pill" style="background: {{ $sc }};">{{ $statusesthiname }}</span>
    </div>

    <div class="detail-section">
        <div class="section-head"><h4>ข้อมูลโครงการ</h4></div>
        <div class="section-body">
            <div class="info-grid">
                <div class="info-row">
                    <span class="lbl">ชื่อลูกค้า</span>
                    <span class="val">{{ $project->customer->prefix ?? '' }}{{ $project->customer->first_name }} {{ $project->customer->last_name }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">เบอร์โทร</span>
                    <span class="val">{{ $project->customer->phone ?? '-' }}</span>
                </div>
                <div class="info-row" style="grid-column: span 2;">
                    <span class="lbl">ที่อยู่</span>
                    <span class="val">
                        {{ $project->customer->house_number ?? '' }}
                        ต.{{ $project->customer->tambon->name_th ?? '-' }}
                        อ.{{ $project->customer->amphure->name_th ?? '-' }}
                        จ.{{ $project->customer->province->name_th ?? '-' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="lbl">วันที่เปิดงาน</span>
                    <span class="val">
                        {{ \Carbon\Carbon::parse($project->created_at)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }}
                    </span>
                </div>
                @if($project->installation_start_date)
                <div class="info-row">
                    <span class="lbl">วันเริ่มติดตั้ง</span>
                    <span class="val">
                        {{ \Carbon\Carbon::parse($project->installation_start_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }}
                        @if($project->installation_end_date)
                            ถึง {{ \Carbon\Carbon::parse($project->installation_end_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }}
                        @endif
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="detail-section">
        <div class="section-head"><h4>รายการติดตั้ง</h4></div>
        <div class="section-body" style="padding: 0;">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>รายการ</th>
                        <th>ขนาด (ซม.)</th>
                        <th style="text-align: center;">จำนวน</th>
                        <th style="text-align: center;">ตำแหน่งที่ติดตั้ง</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project->customerneed as $need)
                    <tr>
                        <td>{{ $need->productset->productSetName->name ?? '-' }}</td>
                        <td>{{ $need->width }} × {{ $need->height }} ซม.</td>
                        <td style="text-align: center;">{{ $need->quantity }} ชุด</td>
                        <td style="text-align: center;">{{ $need->projectimage->imagetype->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px;">ยังไม่มีข้อมูล</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($project->projectexpenses->count() > 0)
    <div class="detail-section">
        <div class="section-head"><h4>ค่าใช้จ่ายเพิ่มเติม</h4></div>
        <div class="section-body" style="padding: 0;">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>ประเภท</th>
                        <th>รายละเอียด</th>
                        <th style="text-align: right;">จำนวนเงิน</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalExpense = 0; @endphp
                    @foreach($project->projectexpenses as $expense)
                    <tr>
                        <td>{{ $expense->type->name }}</td>
                        <td style="color: var(--text-muted); font-size: 0.85rem;">{{ $expense->description ?? '-' }}</td>
                        <td style="text-align: right;">{{ number_format($expense->amount, 2) }} บาท</td>
                    </tr>
                    @php $totalExpense += $expense->amount; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: 700; color: var(--navy);">รวม</td>
                        <td style="text-align: right; font-weight: 700; color: var(--navy);">{{ number_format($totalExpense, 2) }} บาท</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    <div class="detail-section">
        <div class="section-head"><h4>เอกสารโครงการ</h4></div>
        <div class="section-body">
            <div class="doc-grid">

                <div class="doc-card {{ $hasQuotation ? '' : 'doc-pending' }}">
                    <div class="doc-card-title">ใบเสนอราคา</div>
                    <div class="doc-card-sub">รายละเอียดราคาและรายการ</div>
                    @if($hasQuotation)
                        <div class="doc-card-num">เลขที่ <span>{{ $project->quotation_number ?? '-' }}</span></div>
                        <div style="border-top: 1px solid #D4B483; margin-top: 20px;"></div>
                        <a href="{{ route('admin.projects.addbiddocument', $project->id) }}"
                           class="btn-outline-navy" style="margin-top: 4px; text-align:center; display:block; text-decoration:none; padding: 6px 0; border-radius: 0px;">
                            ดูเอกสาร
                        </a>
                    @else
                        <span class="badge-pending">ยังไม่ออกเอกสาร</span>
                    @endif
                </div>

                <div class="doc-card {{ $hasReceipt ? '' : 'doc-pending' }}">
                    <div class="doc-card-title">ใบเสร็จรับเงิน</div>
                    <div class="doc-card-sub">หลักฐานการชำระเงิน</div>
                    @if($hasReceipt)
                        <div class="doc-card-num">เลขที่ <span>{{ $project->receipt_number ?? '-' }}</span></div>
                        <div style="border-top: 1px solid #D4B483; margin-top: 20px;"></div>
                        <a href="{{ route('admin.projects.receipt', $project->id) }}"
                           class="btn-outline-navy" style="margin-top: 4px; text-align:center; display:block; text-decoration:none; padding: 6px 0; border-radius: 0px;">
                            ดูเอกสาร
                        </a>
                    @else
                        <span class="badge-pending">ยังไม่ออกเอกสาร</span>
                    @endif
                </div>

                <div class="doc-card {{ $hasReceipt ? '' : 'doc-pending' }}">
                    <div class="doc-card-title">ใบกำกับภาษี</div>
                    <div class="doc-card-sub">เอกสารภาษีมูลค่าเพิ่ม (VAT)</div>
                    @if($hasReceipt)
                        <div class="doc-card-num">
                            เลขที่ <span>{{ $project->tax_invoice_number ?? '-' }}</span>
                        </div>
                        <div style="border-top: 1px solid #D4B483; margin-top: 20px;"></div>
                        <div >
                            <a href="{{ route('admin.projects.taxInvoice', $project->id) }}"
                            class="btn-outline-navy" style="margin-top: 4px; text-align:center; display:block; text-decoration:none; padding: 6px 0; border-radius: 0;">
                                ดูเอกสาร
                            </a>
                        </div>
                        
                    @else
                        <span class="badge-pending">ยังไม่ออกเอกสาร</span>
                    @endif
                </div>

            </div>
        </div>
    </div>

    

    <div class="detail-section">
        <div class="section-head"><h4>ภาพก่อนและหลังทำเสร็จ</h4></div>
        <div class="before-after-grid">

            @foreach($project->customerneed as $index => $need)

                <div class="ba-set-divider">
                    ชุดที่ {{ $index + 1 }} — {{ $need->productset->productSetName->name ?? '-' }}
                    <span style="font-weight: 400; color: var(--text-muted); font-size: 0.8rem; margin-left: 8px;">
                        {{ $need->width }} × {{ $need->height }} ซม.
                    </span>
                </div>

                <div class="ba-item">
                    <div class="ba-label">
                        ภาพก่อนติดตั้ง
                        <span class="ba-tag ba-tag-before">ก่อน</span>
                    </div>
                    <div class="ba-img-wrap">
                        @if($need->installation_image)
                            <img src="data:image/jpeg;base64,{{ base64_encode($need->installation_image) }}" alt="before">
                        @else
                            <span class="ba-no-img">ยังไม่มีภาพ</span>
                        @endif
                    </div>
                </div>

                <div class="ba-item">
                    <div class="ba-label">
                        ภาพหลังติดตั้ง
                        <span class="ba-tag ba-tag-after">หลัง</span>
                    </div>
                    <div class="ba-img-wrap">
                        @if($need->imageafter)
                            <img src="data:image/jpeg;base64,{{ base64_encode($need->imageafter) }}" alt="after">
                        @else
                            <span class="ba-no-img">ยังไม่ได้อัปโหลด</span>
                        @endif
                    </div>
                </div>

            @endforeach

        </div>
    </div>

</div>
@endsection