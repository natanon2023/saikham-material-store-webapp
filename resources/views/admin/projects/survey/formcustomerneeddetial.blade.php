@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>เพิ่มความต้องการของลูกค้า</h3>
        <a href="{{ route('admin.projects.alldetail', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>
    <div class="image-preview-group">

        <div class="preview-column">
            <h4 class="preview-title">แบบผลิตภัณฑ์</h4>
            <div class="preview-box">
                <span id="text-product" class="preview-placeholder">ยังไม่ได้เลือก</span>
                <img id="preview-product" src="" class="preview-image">
            </div>
        </div>

        <div class="preview-column">
            <h4 class="preview-title">ภาพหน้างาน (ตำแหน่งติดตั้ง)</h4>
            <div class="preview-box">
                <span id="text-location" class="preview-placeholder">ยังไม่ได้เลือก</span>
                <img id="preview-location" src="" class="preview-image">
            </div>
        </div>

        <div class="preview-column">
            <h4 class="preview-title">ภาพพื้นที่ว่างที่จะติดตั้ง</h4>
            <div class="preview-box">
                <span id="text-detail" class="preview-placeholder">ยังไม่ได้เลือก</span>
                <img id="preview-detail" src="" class="preview-image">
            </div>
        </div>

    </div>

    <div class="box">
        <form action="{{ route('admin.projects.addcustomerneeddetial') }}" method="post" enctype="multipart/form-data">
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
                                {{ $pds->productSetName->name.'|'.'อลูมิเนียม'.$pds->aluminumSurfaceFinish->name.'|'.'กระจก'.$pds->glasscolouritem->name }}
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
                        <label class="form-label">ภาพพื้นที่ว่างที่จะติดตั้ง</label>
                        <input type="file" name="installation_image" class="form-input" accept="image/*"  onchange="simplePreview(this)" required>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">ความกว้าง (เซนติเมตร)</label>
                        <input type="number" step="0.01" class="form-input" name="width" required>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">ความสูง (เซนติเมตร)</label>
                        <input type="number" step="0.01" class="form-input" name="height" required>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">หมายเหตุหรือความต้องการเบื้องต้น (ถ้ามี)</label>
                        <textarea name="note_need" class="form-input" ></textarea>
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

    function simplePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader(); 

        reader.onload = function(e) {
            document.getElementById('preview-detail').src = e.target.result;
            document.getElementById('preview-detail').style.display = 'block';
            document.getElementById('text-detail').style.display = 'none';
        };

        reader.readAsDataURL(input.files[0]);
    }

}
</script>
@endsection