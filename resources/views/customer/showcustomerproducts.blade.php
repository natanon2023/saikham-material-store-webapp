@extends('layouts.customer')
@section('content')

<style>
    .btn-gold-outline {
        background: transparent;
        color: var(--gold-dark);
        border: 1.5px solid var(--gold-dark);
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1rem;
        text-decoration: none;
        display: block;
        text-align: center;
        transition: all 0.18s;
        width: 100%;
    }

    .btn-gold-outline:hover {
        background-color: var(--gold);
        color: var(--navy-dark);
        border-color: var(--gold);
    }
    .products-header {
        padding: 1.5rem 0 1rem;
        border-bottom: 1px solid var(--border);
        margin-bottom: 1.5rem;
    }

    .products-header h3 {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--navy);
        border-left: 4px solid var(--gold);
        padding-left: 0.75rem;
        margin: 0;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        padding-bottom: 2rem;
    }

    @media (max-width: 900px) {
        .product-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 560px) {
        .product-grid { grid-template-columns: 1fr; }
    }

    .p-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.2s;
    }

    .p-card:hover {
        box-shadow: 0 4px 18px rgba(51,78,104,0.11);
    }

    .p-card-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
        border-bottom: 1px solid var(--border);
    }

    .p-card-body {
        padding: 1rem 1.1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .p-card-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--navy);
        margin: 0;
    }

    .p-card-detail {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.55;
        margin: 0;
        flex: 1;
    }

    .p-card-footer {
        padding: 0.75rem 1.1rem;
        border-top: 1px solid var(--border);
    }

    .modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(30,45,61,0.45);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-backdrop.open {
        display: flex;
    }

    .modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        width: 100%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-head {
        padding: 1rem 1.25rem;
        border-bottom: 2px solid var(--gold);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-head h5 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--navy);
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: var(--text-muted);
        cursor: pointer;
        line-height: 1;
        padding: 0;
    }

    .modal-close:hover { color: var(--navy); }

    .modal-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
        border-bottom: 1px solid var(--border);
    }

    .modal-body {
        padding: 1.1rem 1.25rem;
    }

    .modal-spec-row {
        display: flex;
        justify-content: space-between;
        padding: 7px 0;
        border-bottom: 1px dashed var(--border);
        font-size: 0.875rem;
    }

    .modal-spec-row:last-child { border-bottom: none; }

    .modal-spec-label {
        font-weight: 600;
        color: var(--navy);
    }

    .modal-spec-value {
        color: var(--text-muted);
        text-align: right;
    }

    .modal-detail-block {
        margin-top: 1rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border);
        font-size: 0.875rem;
        color: var(--text-muted);
        line-height: 1.65;
    }

    .modal-detail-block strong {
        display: block;
        color: var(--navy);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .modal-foot {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 0;
        color: var(--text-muted);
        font-size: 0.95rem;
    }
</style>

<div style="max-width: 1100px; margin: 0 auto; padding: 0 1rem;">

    <div class="products-header">
        <h3>ผลิตภัณฑ์ของเรา</h3>
    </div>

    @if($productsets->isEmpty())
        <div class="empty-state">ยังไม่มีผลิตภัณฑ์ในระบบ</div>
    @else
    <div class="product-grid">
        @foreach($productsets as $product)
        <div class="p-card">
            <img src="data:image/jpeg;base64,{{ base64_encode($product->product_image) }}"
                 alt="{{ $product->productSetName->name ?? '' }}"
                 class="p-card-img">
            <div class="p-card-body">
                <p class="p-card-name">{{ $product->productSetName->name ?? 'ไม่ระบุชื่อผลิตภัณฑ์' }}</p>
                <p class="p-card-detail">{{ Str::limit($product->detail, 60) }}</p>
            </div>
            <div class="p-card-footer" style="display: flex; flex-direction: column; gap: 8px;">
                <button class="btn-outline-navy" style="width:100%; border-radius: 0px;"
                        onclick="openModal('modal-{{ $product->id }}')">
                    ดูรายละเอียด
                </button>
                <a href="" class="btn-gold-outline">ประเมินราคา</a>
            </div>
        </div>

        <div class="modal-backdrop" id="modal-{{ $product->id }}"
             onclick="closeModalOutside(event, 'modal-{{ $product->id }}')">
            <div class="modal-box">
                <div class="modal-head">
                    <h5>{{ $product->productSetName->name ?? 'รายละเอียดผลิตภัณฑ์' }}</h5>
                    <button class="modal-close" onclick="closeModal('modal-{{ $product->id }}')">&times;</button>
                </div>
                <img src="data:image/jpeg;base64,{{ base64_encode($product->product_image) }}"
                     alt="{{ $product->productSetName->name ?? '' }}"
                     class="modal-img">
                <div class="modal-body">
                    <div class="modal-spec-row">
                        <span class="modal-spec-label">สีอลูมิเนียม</span>
                        <span class="modal-spec-value">{{ $product->aluminumSurfaceFinish->name ?? '-' }}</span>
                    </div>
                    <div class="modal-spec-row">
                        <span class="modal-spec-label">สีกระจก</span>
                        <span class="modal-spec-value">{{ $product->glasscolouritem->name ?? '-' }}</span>
                    </div>
                    <div class="modal-spec-row">
                        <span class="modal-spec-label">ประเภทกระจก</span>
                        <span class="modal-spec-value">{{ $product->glasstype->name ?? '-' }}</span>
                    </div>
                    <div class="modal-detail-block">
                        <strong>รายละเอียดเพิ่มเติม</strong>
                        {{ $product->detail }}
                    </div>
                </div>
                <div class="modal-foot" style="gap:5px;">
                    <button class="btn-navy" onclick="closeModal('modal-{{ $product->id }}')" style="border-radius: 0px;"  >ปิด</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
        document.body.style.overflow = '';
    }
    function closeModalOutside(event, id) {
        if (event.target === document.getElementById(id)) closeModal(id);
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-backdrop.open').forEach(m => {
                m.classList.remove('open');
                document.body.style.overflow = '';
            });
        }
    });
</script>

@endsection