@extends('layouts.admin')

@section('content')
    <div class="main-content">
        @include('components.selectcreateForm1')
        <div class="boxmaterial" >
            <h4>เลือกประเภทวัสดุหรืออุปกรณ์</h4>
        </div>

        <div class="box-selecttype">
            <a href="{{ route('admin.materials.formaluminium') }}" class="a-selecttype" >1. อลูมิเนียม</a>
        </div>
        <div class="box-selecttype">
            <a href="{{ route('admin.materials.formglass') }}" class="a-selecttype">2. กระจก</a>
        </div>
        <div class="box-selecttype">
            <a href="{{ route('admin.materials.formaccessory') }}" class="a-selecttype">3. อุปกรณ์เสริม</a>
        </div>
        <div class="box-selecttype">
            <a href="{{ route('admin.materials.formtool') }}" class="a-selecttype">4. เครื่องมือช่าง</a>
        </div>
        <div class="box-selecttype">
            <a href="{{ route('admin.materials.formconsumable') }}" class="a-selecttype">5. วัสดุสิ้นเปลือง</a>
        </div>
    </div>
@endsection
