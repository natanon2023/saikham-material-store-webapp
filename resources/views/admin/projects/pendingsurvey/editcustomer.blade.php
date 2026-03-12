@extends('layouts.admin')

@section('content')



<div class="main-content">
    @include('components.successanderror')
    <div class="boxmaterial customer-detail">
        <div  style="display: flex; justify-content :space-between; margin-bottom: 20px;">
            <h3>รายละเอียดข้อมูล</h3>
            <a href="{{ route('admin.projects.formnewcustomer') }}" class="btn btn-primary">ย้อนกลับ</a>
        </div>

        <div class="detail-grid">
            <div>
                <span class="label">เลขประจำตัวผู้เสียภาษี</span>
                <span class="value">
                   {{ $customer->tax_id_number }}
                </span>
            </div>


            <div>
                <span class="label">ชื่อ-นามสกุล</span>
                <span class="value">
                    {{ $customer->prefix }} {{ $customer->first_name }} {{ $customer->last_name }}
                </span>
            </div>

            <div>
                <span class="label">เพศ</span>
                <span class="value">{{ $customer->gender }}</span>
            </div>

            <div>
                <span class="label">เบอร์โทร</span>
                <span class="value">{{ $customer->phone ?? '-' }}</span>
            </div>

            <div>
                <span class="label">อีเมล</span>
                <span class="value">{{ $customer->email ?? '-' }}</span>
            </div>

            <div class="full">
                <span class="label">ที่อยู่</span>
                <span class="value">
                    เลขที่ {{ $customer->house_number ?? '-' }}
                    หมู่ {{ $customer->village ?? '-' }}
                    {{ $customer->house_name ?? '' }}
                    ซอย {{ $customer->alley ?? '-' }}
                    ถนน {{ $customer->road ?? '-' }} <br>
                    ต.{{ $customer->tambon->name_th ?? '-' }}
                    อ.{{ $customer->amphure->name_th ?? '-' }}
                    จ.{{ $customer->province->name_th ?? '-' }}
                    {{ $customer->tambon->zip_code ?? '-' }}
                </span>
            </div>
        </div>
    </div>

    <div class="boxmaterial" style="display: flex; justify-content :space-between">
        <h3>แก้ไขข้อมูลลูกค้า</h3>
    </div>
    

    <div class="box">
        <form action="{{ route('admin.projects.updatecustomer', $customer->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">เลขประจำตัวผู้เสียภาษี</label>
                    <input type="text" name="tax_id_number" class="form-input"  value="{{ $customer->tax_id_number }}">
                </div>


                <div class="form-group">
                    <label class="form-label">คำนำหน้าชื่อ</label>
                    <select name="prefix" class="form-input" >
                        <option value="นาย" {{ $customer->prefix == 'นาย' ? 'selected' : '' }}>นาย</option>
                        <option value="นาง" {{ $customer->prefix == 'นาง' ? 'selected' : '' }}>นาง</option>
                        <option value="นางสาว" {{ $customer->prefix == 'นางสาว' ? 'selected' : '' }}>นางสาว</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">เพศ</label>
                    <select name="gender" class="form-input">
                        <option value="ชาย" {{ $customer->gender == 'ชาย' ? 'selected' : '' }}>ชาย</option>
                        <option value="หญิง" {{ $customer->gender == 'หญิง' ? 'selected' : '' }}>หญิง</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ชื่อ</label>
                    <input type="text" name="first_name" class="form-input" value="{{ $customer->first_name }}" >
                </div>
                <div class="form-group">
                    <label class="form-label">นามสกุล</label>
                    <input type="text" name="last_name" class="form-input" value="{{ $customer->last_name }}" >
                </div>
                <div class="form-group">
                    <label class="form-label">เบอร์โทร</label>
                    <input type="text" name="phone" class="form-input" value="{{ $customer->phone }}" >
                </div>
                <div class="form-group">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-input" value="{{ $customer->email }}">
                </div>

                <div class="form-group">
                    <label class="form-label">เลขที่</label>
                    <input type="text" name="house_number" class="form-input" value="{{ $customer->house_number }}" >
                </div>
                <div class="form-group">
                    <label class="form-label">หมู่ที่</label>
                    <input type="text" name="village" class="form-input" value="{{ $customer->village }}">
                </div>
                <div class="form-group">
                    <label class="form-label">ชื่อหมู่บ้าน</label>
                    <input type="text" name="house_name" class="form-input" value="{{ $customer->house_name }}">
                </div>
                <div class="form-group">
                    <label class="form-label">ซอย</label>
                    <input type="text" name="alley" class="form-input" value="{{ $customer->alley }}">
                </div>
                <div class="form-group">
                    <label class="form-label">ถนน</label>
                    <input type="text" name="road" class="form-input" value="{{ $customer->road }}">
                </div>

                <div class="form-group">
                    <label class="form-label">จังหวัด</label>
                    <select name="province_id" id="province_id" class="form-input" >
                        @foreach ($province as $pv)
                        <option value="{{ $pv->id }}" {{ $customer->province_id == $pv->id ? 'selected' : '' }}>
                            {{ $pv->name_th }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">อำเภอ/เขต</label>
                    <select name="amphure_id" id="amphure_id" class="form-input" >
                        @foreach ($amphure as $ap)
                        <option value="{{ $ap->id }}" {{ $customer->amphure_id == $ap->id ? 'selected' : '' }}>
                            {{ $ap->name_th }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ตำบล/แขวง</label>
                    <select name="tambon_id" id="tambon_id" class="form-input" >
                        @foreach ($tambon as $tb)
                        <option value="{{ $tb->id }}" data-zip="{{ $tb->zip_code }}" {{ $customer->tambon_id == $tb->id ? 'selected' : '' }}>
                            {{ $tb->name_th }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">รหัสไปรษณีย์</label>
                    <input type="text" name="zip_code" id="zip_code" class="form-input" value="{{ $customer->zip_code }}" readonly >
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">บันทึกการแก้ไข</button>
                </div>
            </div>
        </form>
    </div>


    




</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province_id');
        const amphureSelect = document.getElementById('amphure_id');
        const tambonSelect = document.getElementById('tambon_id');
        const zipCodeInput = document.getElementById('zip_code');

        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            amphureSelect.innerHTML = '<option value="">เลือกอำเภอ</option>';
            tambonSelect.innerHTML = '<option value="">เลือกตำบล</option>';
            zipCodeInput.value = '';
            amphureSelect.disabled = true;
            tambonSelect.disabled = true;

            if (provinceId) {
                fetch(`/api/get-amphures/${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(amphure => {
                            amphureSelect.innerHTML += `<option value="${amphure.id}">${amphure.name_th}</option>`;
                        });
                        amphureSelect.disabled = false;
                    });
            }
        });

        amphureSelect.addEventListener('change', function() {
            const amphureId = this.value;
            tambonSelect.innerHTML = '<option value="">เลือกตำบล</option>';
            zipCodeInput.value = '';
            tambonSelect.disabled = true;

            if (amphureId) {
                fetch(`/api/get-tambons/${amphureId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(tambon => {
                            tambonSelect.innerHTML += `<option value="${tambon.id}" data-zip="${tambon.zip_code}">${tambon.name_th}</option>`;
                        });
                        tambonSelect.disabled = false;
                    });
            }
        });

        tambonSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const zipCode = selectedOption.getAttribute('data-zip');
            zipCodeInput.value = zipCode ? zipCode : '';
        });
    });
</script>

@endsection