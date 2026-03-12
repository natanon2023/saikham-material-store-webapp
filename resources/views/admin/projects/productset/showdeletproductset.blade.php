@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex ; justify-content: space-between;">
        <h3>ผลิตภัณฑ์ที่ถูกลบ</h3>
        <a href="{{ route('admin.projects.productsetdetail') }}" class="btn btn-secondary">ย้อนกลับ</a>
    </div>

    <div>
        @forelse ($productset as $set)
        <div class="boxdetialproductset">
            <div class="imgproductset" style="background-color:#b2dbf6ff;">
                <div style="display:flex;justify-content:center;">
                <img src="data:image/jpeg;base64,{{ base64_encode($set->product_image) }}" class="imgposition">
                </div>
            </div>

            <div class="boxsmallproductset">
                <p><strong>{{ $set->productSetName->name }}</strong></p>
                <p style="font-size:small;"><strong>สีกระอลูมิเนียม:</strong> {{ $set->aluminumSurfaceFinish->name ?? '-' }} </p>
                <p style="font-size:small;"> <strong>ประเภทกระจก:</strong> {{ $set->glasstype->name ?? '-' }} </p>
                <p style="font-size:small;"><strong>สีกระจก:</strong>{{ $set->glasscolouritem->name ?? '-' }}</p>
                <p style="font-size:small;"> <strong>รายละเอียด:</strong>{{ $set->detail }}</p>
                <p style="font-size:small;"><strong>วันที่ลบ:</strong>{{ $set->deleted_at->format('d/m/Y H:i') }}</p>
                <div style="display:flex;justify-content:end; margin-top:10px; ">
                    <form action="{{ route('admin.projects.restoreproductset', $set->id) }}"  method="POST" onsubmit="return confirm('ต้องการกู้คืนผลิตภัณฑ์นี้หรือไม่?');">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-primary" style="height: 100%;">กู้คืน</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
            <div style="margin-top:10px; background-color: white; padding: 10px; width: 100%; height: 100%; text-align: center;">
                <p>ไม่มีข้อมูลที่ถูกลบ</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
