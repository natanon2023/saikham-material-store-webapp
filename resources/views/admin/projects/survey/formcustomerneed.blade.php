@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>เพิ่มความต้องการของลูกค้า</h3>
        <a href="{{ route('admin.projects.formsurveying', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>
    <div style="flex: 1.5; min-width: 400px; display: flex; gap: 20px; background: #f9f9f9; padding: 20px;  border: 1px solid #ddd; margin-bottom:15px;">

        <div style="flex: 1; text-align: center;">
            <h4 style="color: #555; margin-bottom: 10px;">แบบผลิตภัณฑ์</h4>
            <div style="height: 300px; border: 2px dashed #ccc;  display: flex; align-items: center; justify-content: center; background: #fff; overflow: hidden;">
                <span id="text-product" style="color: #999;">ยังไม่ได้เลือก</span>
                <img id="preview-product" src="" style="max-width: 100%; max-height: 100%; display: none; object-fit: contain;">
            </div>
        </div>

        <div style="flex: 1; text-align: center;">
            <h4 style="color: #555; margin-bottom: 10px;">ภาพหน้างาน (ตำแหน่งติดตั้ง)</h4>
            <div style="height: 300px; border: 2px dashed #ccc;  display: flex; align-items: center; justify-content: center; background: #fff; overflow: hidden;">
                <span id="text-location" style="color: #999;">ยังไม่ได้เลือก</span>
                <img id="preview-location" src="" style="max-width: 100%; max-height: 100%; display: none; object-fit: contain;">
            </div>
        </div>

    </div>

    <div class="box">
        <form action="{{ route('admin.projects.addcustomerneed') }}" method="post">
            @csrf
            <input type="hidden" value="{{ $project->id }}" name="project_id">

            <div class="box-control" style="display: flex; gap: 30px; flex-wrap: wrap;">
                    <div class="form-group">
                        <label for="" class="form-label">เลือกชุดผลิตภัณฑ์</label>
                        <select name="product_set_id" id="select-product" class="form-select" required>
                            <option value="" data-img="">เลือกชุดผลิตภัณฑ์</option>
                            @foreach ($productset as $pds)
                            @php
                            $pdsImg = $pds->product_image ? 'data:image/jpeg;base64,' . base64_encode($pds->product_image) : '';
                            @endphp
                            <option value="{{ $pds->id }}" data-img="{{ $pdsImg }}">
                                {{ $pds->productSetName->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">ตำแหน่งที่จะติดตั้ง</label>
                        <select name="location" id="select-location" class="form-input" required>
                            <option value="" data-img="">เลือกตำแหน่งที่จะติดตั้ง</option>
                            @foreach ($projectimg as $img)
                            @php
                            $locImg = $img->image_path ? 'data:image/jpeg;base64,' . base64_encode($img->image_path) : '';
                            @endphp
                            <option value="{{ $img->id }}" data-img="{{ $locImg }}">
                                {{ $img->imagetype->name ?? 'ไม่มีข้อมูล' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">ความกว้าง (เซนติเมตร)</label>
                        <input type="number" step="0.01" class="form-input" name="width" required>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">ความสูง (เซนติเมตร)</label>
                        <input type="number" step="0.01" class="form-input" name="high" required>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">จำนวน (ชุด)</label>
                        <input type="number" class="form-input" name="quantity" required>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <button class="btn btn-secondary" type="submit">บันทึกข้อมูล</button>
                    </div>



            </div>
        </form>
    </div>
</div>

<script>
    function setupImagePreview(selectId, imgId, textId) {
        const selectBox = document.getElementById(selectId);
        const imgDisplay = document.getElementById(imgId);
        const textDisplay = document.getElementById(textId);

        selectBox.addEventListener('change', function() {
            const selectedOption = selectBox.options[selectBox.selectedIndex];
            const base64Image = selectedOption.getAttribute('data-img');

            if (base64Image) {
                imgDisplay.src = base64Image;
                imgDisplay.style.display = 'block';
                textDisplay.style.display = 'none';
            } else {
                imgDisplay.src = '';
                imgDisplay.style.display = 'none';
                textDisplay.style.display = 'block';
            }
        });
    }

    setupImagePreview('select-product', 'preview-product', 'text-product');
    setupImagePreview('select-location', 'preview-location', 'text-location');
</script>
@endsection