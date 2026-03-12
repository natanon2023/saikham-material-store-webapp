@extends('layouts.admin')

@section('content')
<div class="main-content">

    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex ; justify-content: space-between;">
        <h3>ผลิตภัณฑ์ทั้งหมด</h3>
        <div style=" height: max-content;">
          <a href="{{ route('admin.projects.formproductset') }}" class="btn btn-secondary">+ เพิ่มผลิตภัณฑ์ใหม่</a>
          <a href="{{ route('admin.projects.showdeletproductset') }}" class="btn btn-primary">กู้คืนข้อมูล</a>
        </div>
        
    </div>

    

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
               <div style="display: flex; justify-content: end; margin-bottom: 10px; gap:5px;">
                  <a href="{{ route('admin.projects.formaddproductsetitem',$set->id) }}" class="btn-icon btn-show" title="จัดการผลิตภัณฑ์">
                    <i class="fa-solid fa-door-open"></i>
                  </a> 
                  <a href="{{route('admin.projects.formeditproductset',$set->id)  }}" class="btn-icon btn-edit" title="แก้ไข">
                    <i class="fa-solid fas fa-edit"></i>
                  </a> 
                  <form action="{{ route('admin.projects.deleteproductset', $set->id) }}" method="POST"style="display:inline;" onsubmit="return confirm('คุณต้องการลบผลิตภัณฑ์นี้ใช่หรือไม่?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-icon btn-delete" title="ลบ">
                      <i class="fa-solid fas fa-trash"></i>
                    </button>
                  </form>
               </div>
            </div>
        </div>
        @endforeach
    </div>






</div>

@endsection