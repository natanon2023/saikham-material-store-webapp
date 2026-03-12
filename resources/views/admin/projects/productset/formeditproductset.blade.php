@extends('layouts.admin')

@section('content')
<div class="main-content">
    
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between;">
        <h4>แก้ไขผลิตภัณฑ์</h4>
        <a href="{{ route('admin.projects.productsetdetail') }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    

    <div class="box">
        <div style=" margin-bottom: 8px; text-align: center;">
            <img src="data:image/jpeg;base64,{{ base64_encode($productset->product_image) }}" alt="product image" style="max-width: 300px; ">
        </div>
        
        <form action="{{ route('admin.projects.editproductset', $productset->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ชื่อผลิตภัณฑ์ใหม่</label>
                    <div class="createname">
                        <select name="product_set_name_id" class="form-select">
                            <option value="">เลือกชื่อผลิตภัณฑ์ใหม่</option>
                            @foreach ($productsetname as $pdsn)
                                <option value="{{ $pdsn->id }}"
                                    @selected($productset->product_set_name_id == $pdsn->id)>
                                    {{ $pdsn->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">รูปภาพผลิตภัณฑ์</label>
                    <input type="file" name="product_image" class="form-input" accept="image/*">
                </div>

                <div class="form-group">
                    <label class="form-label">สีอลูมิเนียม</label>
                    <select name="aluminum_surface_finish_id" class="form-select" >
                        <option value="">เลือกสีอลูมิเนียม</option>
                        @foreach ($aluminumsurfacefinish as $als)
                            <option value="{{ $als->id }}"
                                @selected($productset->aluminum_surface_finish_id == $als->id)>
                                {{ $als->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ประเภทกระจก</label>
                    <select name="glasstype_id" class="form-select" >
                        <option value="">เลือกประเภทกระจก</option>
                        @foreach ($glasstype as $gt)
                            <option value="{{ $gt->id }}"
                                @selected($productset->glasstype_id == $gt->id)>
                                {{ $gt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">สีกระจก</label>
                    <select name="glass_colouritem_id" class="form-select" >
                        <option value="">เลือกสีกระจก</option>
                        @foreach ($glasscolouritem as $gct)
                            <option value="{{ $gct->id }}"
                                @selected($productset->glass_colouritem_id == $gct->id)>
                                {{ $gct->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">รายละเอียด</label>
                    <textarea name="detail" class="form-input" >{{ $productset->detail }}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">บันทึกข้อมูล</button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
