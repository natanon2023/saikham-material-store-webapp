@extends('layouts.technician')

@section('content')
<div class="main-content" >
    <div class="boxmaterial" style="display: flex; justify-content: space-between;">
        <h3>เพิ่มความต้องการของลูกค้า</h3>
        <a href="{{ route('technician.projects.formsurveying', $project->id) }}" class="btn btn-secondary">ย้อนกลับ</a>
    </div>

    <div class="box">
        <form action="{{ route('technician.projects.addcustomerneed') }}" method="post">
            @csrf
            <input type="hidden" value="{{ $project->id }}" name="project_id">
            <div class="box-control">
                <div class="form-group">
                    <label for="" class="form-label">เลือกชุดผลิตภัณฑ์</label>
                    <select name="product_set_id" id="" class="form-select" required>
                        <option value="">เลือกชุดผลิตภัณฑ์</option>
                        @foreach ($productset as $pds )
                            <option value="{{ $pds->id }}">{{ $pds->productSetName->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="" class="form-label">ตำแหน่งที่จะติดตั้ง</label>
                    <input type="text" class="form-input" name="location" required>
                </div>

                <div class="form-group">
                    <label for="" class="form-label">ความกว้าง (เซนติเมตร)</label>
                    <input type="number" class="form-input" name="width" required>
                </div>

                <div class="form-group">
                    <label for="" class="form-label">ความสูง (เซนติเมตร)</label>
                    <input type="number" class="form-input" name="high" required>
                </div>

                <div class="form-group">
                    <label for="" class="form-label">จำนวน</label>
                    <input type="number" class="form-input" name="quantity" required>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">บันทึกข้อมูล</button>
                </div>
            </div>
        </form>  
    </div>
    
    

</div>

@endsection