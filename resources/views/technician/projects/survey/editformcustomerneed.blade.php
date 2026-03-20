@extends('layouts.technician')

@section('content')
<div class="main-content">

    <div style="margin-top: 20px;">
        @include('components.successanderror')
    </div>

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>แก้ไขความต้องการของลูกค้า</h3>
        @if ($project->status == 'surveying')
        <a href="{{ route('technician.projects.formsurveying',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
        @else
        <a href="{{ route('technician.projects.alldetail',$project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
        @endif
    </div>
    
    <div class="image-preview-group" style="display: flex; gap: 20px; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; margin-bottom:15px; flex-wrap: wrap;">

        <div class="preview-column" style="flex: 1; text-align: center; min-width: 250px;">
            <h4 class="preview-title" style="color: #555; margin-bottom: 10px;">แบบผลิตภัณฑ์</h4>
            <div class="preview-box" style="height: 300px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; background: #fff; overflow: hidden;">
                <span id="text-product" class="preview-placeholder" style="color: #999;">ยังไม่ได้เลือก</span>
                <img id="preview-product" src="" class="preview-image" style="max-width: 90%; max-height: 90%; display: none; object-fit: contain;">
            </div>
        </div>

        <div class="preview-column" style="flex: 1; text-align: center; min-width: 250px;">
            <h4 class="preview-title" style="color: #555; margin-bottom: 10px;">ภาพหน้างาน (ตำแหน่งติดตั้ง)</h4>
            <div class="preview-box" style="height: 300px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; background: #fff; overflow: hidden;">
                <span id="text-location" class="preview-placeholder" style="color: #999;">ยังไม่ได้เลือก</span>
                <img id="preview-location" src="" class="preview-image" style="max-width: 90%; max-height: 90%; display: none; object-fit: contain;">
            </div>
        </div>

        <div class="preview-column" style="flex: 1; text-align: center; min-width: 250px;">
            <h4 class="preview-title" style="color: #555; margin-bottom: 10px;">ภาพพื้นที่ว่างที่จะติดตั้ง</h4>
            <div class="preview-box" style="height: 300px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; background: #fff; overflow: hidden;">
                @php
                    $existImg = $customerNeed->installation_image ? 'data:image/jpeg;base64,' . base64_encode($customerNeed->installation_image) : '';
                @endphp
                
                <span id="text-detail" class="preview-placeholder" style="color: #999; {{ $existImg ? 'display: none;' : '' }}">ยังไม่ได้เลือก</span>
                <img id="preview-detail" src="{{ $existImg }}" class="preview-image" style="max-width: 90%; max-height: 90%; {{ $existImg ? 'display: block;' : 'display: none;' }} object-fit: contain;">
            </div>
        </div>

    </div>

    <div class="box">
        <form action="{{ route('technician.projects.updatecustomerneed', $customerNeed->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" value="{{ $project->id }}" name="project_id">

            <div class="box-control" style="display: flex; gap: 30px; flex-wrap: wrap;">
                    <div class="form-group" style="flex: 1; min-width: 300px;">
                        <label class="form-label">เลือกชุดผลิตภัณฑ์</label>
                        <select name="product_set_id" id="select-product" class="form-select" required>
                            <option value="" data-img="">เลือกชุดผลิตภัณฑ์</option>
                            @foreach ($productset as $pds)
                            @php
                            $pdsImg = $pds->product_image ? 'data:image/jpeg;base64,' . base64_encode($pds->product_image) : '';
                            @endphp
                            <option value="{{ $pds->id }}" data-img="{{ $pdsImg }}" {{ $customerNeed->product_set_id == $pds->id ? 'selected' : '' }}>
                                {{ $pds->productSetName->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1; min-width: 300px;">
                        <label class="form-label">ตำแหน่งที่จะติดตั้ง</label>
                        <select name="location" id="select-location" class="form-input" required>
                            <option value="" data-img="">เลือกตำแหน่งที่จะติดตั้ง</option>
                            @foreach ($projectimg as $img)
                            @php
                            $locImg = $img->image_path ? 'data:image/jpeg;base64,' . base64_encode($img->image_path) : '';
                            @endphp
                            <option value="{{ $img->id }}" data-img="{{ $locImg }}" {{ $customerNeed->location == $img->id ? 'selected' : '' }}>
                                {{ $img->imagetype->name ?? 'ไม่มีข้อมูล' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1; min-width: 300px;">
                        <label class="form-label">ภาพพื้นที่ว่างที่จะติดตั้ง (เลือกใหม่เพื่อเปลี่ยนภาพ)</label>
                        <input type="file" name="installation_image" class="form-input" accept="image/*" onchange="simplePreview(this)">
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">ความกว้าง (เซนติเมตร)</label>
                        <input type="number" step="0.01" class="form-input" name="width" value="{{ $customerNeed->width }}" required>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">ความสูง (เซนติเมตร)</label>
                        <input type="number" step="0.01" class="form-input" name="height" value="{{ $customerNeed->height }}" required>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">หมายเหตุหรือความต้องการเบื้องต้น (ถ้ามี)</label>
                        <textarea name="note_need" class="form-input" >{{ $customerNeed->note_need }}</textarea>
                    </div>

                    
                    <div class="form-group" style="width: 100%; margin-top: 10px;">
                        <button class="btn btn-secondary" type="submit" style="padding: 10px 30px;">อัปเดตข้อมูล</button>
                    </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setupImagePreview(selectId, imgId, textId) {
            const selectBox = document.getElementById(selectId);
            const imgDisplay = document.getElementById(imgId);
            const textDisplay = document.getElementById(textId);

            function updateImage() {
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
            }

            selectBox.addEventListener('change', updateImage);

            updateImage(); 
        }

        setupImagePreview('select-product', 'preview-product', 'text-product');
        setupImagePreview('select-location', 'preview-location', 'text-location');
    });

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