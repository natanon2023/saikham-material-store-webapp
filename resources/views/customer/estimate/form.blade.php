@extends('layouts.customer')
@section('content')

<style>
    .est-wrap {
        max-width: 600px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .est-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        padding: 1.5rem 1.75rem;
    }

    .est-product-row {
        display: flex;
        gap: 14px;
        align-items: center;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
        margin-bottom: 1.25rem;
    }

    .est-product-img {
        width: 72px;
        height: 72px;
        object-fit: cover;
        flex-shrink: 0;
        border: 1px solid var(--border);
    }

    .est-product-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--navy);
    }

    .est-product-sub {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-top: 3px;
    }

    .form-row {
        display: flex;
        gap: 16px;
        margin-bottom: 1rem;
    }

    .form-group {
        flex: 1;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--navy);
        display: block;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid var(--border);
        font-size: 0.95rem;
        color: var(--text-primary);
        background: var(--bg-card);
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--navy);
    }

    .unit-hint {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 3px;
    }

    .btn-estimate {
        width: 100%;
        padding: 10px;
        background: var(--gold);
        color: #fff;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 6px;
    }

    .btn-estimate:hover {
        background: #243a50;
    }

    .back-link {
        font-size: 0.85rem;
        color: var(--text-muted);
        text-decoration: none;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .back-link:hover {
        color: var(--navy, #334E68);
    }
</style>

<div style="max-width: 1100px; margin: 0 auto; padding: 0 1rem;" >
    <div style="display: flex; justify-content:end; margin-bottom:20px;">
      <a href="{{ url()->previous() }}" class=" btn btn-primary">ย้อนกลับ</a>
    </div>
    
    <div class="est-card">
        <div class="est-product-row">
            <img src="data:image/jpeg;base64,{{ base64_encode($productset->product_image) }}"
                class="est-product-img" alt="">
            <div>
                <div class="est-product-name">{{ $productset->productSetName->name ?? '-' }}</div>
                <div class="est-product-sub">
                    อลูมิเนียม{{ $productset->aluminumSurfaceFinish->name ?? '' }}
                    &nbsp;|&nbsp; กระจก{{ $productset->glasscolouritem->name ?? '' }}
                    &nbsp;|&nbsp; {{ $productset->glasstype->name ?? '' }}
                </div>
            </div>
        </div>

        <p style="font-size:0.9rem; color:var(--text-muted); margin-bottom:1rem;">
            กรอกขนาดหน้าต่าง/ประตู เพื่อประเมินราคาเบื้องต้น
        </p>

        <form method="POST" action="{{ route('customer.estimate.calculate', $productset->id) }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>ความกว้าง</label>
                    <input type="number" name="width" min="10" max="5000" step="1"
                        value="{{ old('width') }}" placeholder="เช่น 120" required>
                    <div class="unit-hint">หน่วย: เซนติเมตร (ซม.)</div>
                    @error('width')
                    <div style="color:red; font-size:0.8rem;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>ความสูง</label>
                    <input type="number" name="height" min="10" max="5000" step="1"
                        value="{{ old('height') }}" placeholder="เช่น 150" required>
                    <div class="unit-hint">หน่วย: เซนติเมตร (ซม.)</div>
                    @error('height')
                    <div style="color:red; font-size:0.8rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-estimate">คำนวณราคา</button>
        </form>
    </div>
</div>

@endsection