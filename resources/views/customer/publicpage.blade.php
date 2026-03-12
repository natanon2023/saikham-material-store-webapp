@extends('layouts.customer')

@section('content')
<div class="boxdetialproductset-control">
        <div class="boxdetialproductset-control">
        @foreach ($productset as $set)
        <div class="boxdetialproductset">
            <div class="imgproductset">
                    <img src="data:image/jpeg;base64,{{ base64_encode($set->product_image) }}" class="imgposition">
            </div>
            <div class="boxsmallproductset">
               <strong>{{$set->productSetName->name }} </p></strong><p>
               <p style="font-size: small;"><strong>สีอลูมิเนียม:</strong> {{ $set->aluminumSurfaceFinish->name }}</p>
               <p style="font-size: small;"><strong>ประเภทกระจก:</strong> {{ $set->glasstype->name }}</p>
               <p style="font-size: small;"><strong>สีกระจก:</strong> {{ $set->glasscolouritem->name }}</p>
               <p style="font-size: small;"><strong>รายละเอียด:</strong> {{ $set->detail }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection