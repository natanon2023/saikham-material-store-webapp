@extends('layouts.admin')

@section('content')



<div class="main-content">
    @include('components.successanderror')
    <div class="boxmaterial" style="display: flex; justify-content :space-between">
        <h3>เพิ่มข้อมูลค้าใหม่</h3>
        <a href="{{ route('admin.projects.formpendingsurvey') }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div class="box">

        <form action="{{ route('admin.projects.createnewcustomer')}}" method="post">
            @csrf
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">เลขประจำตัวผู้เสียภาษี</label>
                    <input type="text" name="tax_id_number" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">คำนำหน้าชื่อ</label>
                    <select name="prefix" class="form-input" required>
                        <option value="">เลือกคำนำหน้า</option>
                        <option value="นาย">นาย</option>
                        <option value="นาง">นาง</option>
                        <option value="นางสาว">นางสาว</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" required>เพศ</label>
                    <select name="gender" class="form-input">
                        <option value="">เลือกเพศ</option>
                        <option value="ชาย">ชาย</option>
                        <option value="หญิง">หญิง</option>
                    </select>
                </div>


                <div class="form-group">
                    <label class="form-label">ชื่อ</label>
                    <input type="text" name="first_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">นามสกุล</label>
                    <input type="text" name="last_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">เบอร์โทร</label>
                    <input type="text" name="phone" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">อีเมล (ถ้ามี)</label>
                    <input type="email" name="email" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">เลขที่</label>
                    <input type="text" name="house_number" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">หมู่ที่ (ถ้ามี)</label>
                    <input type="text" name="village" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">ชื่อหมู่บ้าน (ถ้ามี)</label>
                    <input type="text" name="house_name" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">ซอย (ถ้ามี)</label>
                    <input type="text" name="alley" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">ถนน (ถ้ามี)</label>
                    <input type="text" name="road" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">จังหวัด</label>
                    <select name="province_id" id="province_id" class="form-input" required>
                        <option value="">เลือกจังหวัด</option>
                        @foreach ($province as $pv)
                        <option value="{{ $pv->id }}">{{ $pv->name_th }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">อำเภอ/เขต</label>
                    <select name="amphure_id" id="amphure_id" class="form-input" required disabled>
                        <option value="">เลือกอำเภอ</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ตำบล/แขวง</label>
                    <select name="tambon_id" id="tambon_id" class="form-input" required disabled>
                        <option value="">เลือกตำบล </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">รหัสไปรษณีย์</label>
                    <input type="text" name="zip_code" id="zip_code" class="form-input" readonly required>
                </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">เพิ่มข้อมูลค้าใหม่</button>
                </div>
            </div>
        </form>
    </div>

    <h3 style="margin-bottom: 20px; margin-top: 30px;">ข้อมูลลูกค้าทั้งหมด</h3>
    <table width="100%" border="1" style="border-collapse: collapse;">
        <tr align="center" style="background-color: #f4f4f4;">
            <td width="10%">ลำดับ</td>
            <td width="40%">ชื่อ-นามสกุล</td>
            <td width="30%">สถานะข้อมูล</td>
            <td width="20%">จัดการข้อมูล</td>
        </tr>

        @foreach ($customerall as $ca)
        <tr align="center" style="{{ $ca->trashed() ? 'opacity: 0.6; background-color: #f9f9f9;' : '' }}">
            <td>{{ $loop->iteration }}</td>
            <td align="center" style="padding-left: 10px;">
                {{ $ca->prefix }}{{ $ca->first_name }} {{ $ca->last_name }}
            </td>

            <td>
                @if($ca->trashed())
                    <span>ถูกลบ</span>
                @else
                    <span>ปกติ</span>
                @endif
            </td>

            <td>
                <div style="display: flex; justify-content: center; gap: 5px;">
                    @if(!$ca->trashed())
                    <a href="{{ route('admin.projects.editcustomer', $ca->id) }}" class=" btn-icon btn-edit" title="แก้ไข">
                        <i class="fas fa-edit"></i>
                    </a>

                    <a href="{{ route('admin.projects.deletecustomer', $ca->id) }}"
                        class="btn-icon btn-delete"
                        style="background-color: #dc3545; color: white;"
                        onclick="return confirm('ยืนยันการลบข้อมูลลูกค้าท่านนี้?')" title="ลบ"> <i class="fas fa-trash"></i></a>
                    @else
                    <a href="{{ route('admin.projects.restorecustomer', $ca->id) }}"
                        class="btn-icon btn-secondary" title="กู้คืนข้อมูล">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </a>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </table>

    




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_id');
            const amphureSelect = document.getElementById('amphure_id');
            const tambonSelect = document.getElementById('tambon_id');
            const zipCodeInput = document.getElementById('zip_code');

            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;

                amphureSelect.innerHTML = '<option value=""> เลือกอำเภอ </option>';
                tambonSelect.innerHTML = '<option value="">เลือกตำบล</option>';
                zipCodeInput.value = '';

                amphureSelect.disabled = true;
                tambonSelect.disabled = true;

                if (provinceId) {
                    fetch(`/api/get-amphures/${provinceId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(amphure => {
                                amphureSelect.innerHTML +=
                                    `<option value="${amphure.id}">${amphure.name_th}</option>`;
                            });
                            amphureSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error fetching amphures:', error);
                        });
                }
            });

            amphureSelect.addEventListener('change', function() {
                const amphureId = this.value;

                tambonSelect.innerHTML = '<option value=""> เลือกตำบล </option>';
                zipCodeInput.value = '';
                tambonSelect.disabled = true;

                if (amphureId) {
                    fetch(`/api/get-tambons/${amphureId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(tambon => {
                                tambonSelect.innerHTML +=
                                    `<option value="${tambon.id}" data-zip="${tambon.zip_code}">${tambon.name_th}</option>`;
                            });
                            tambonSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error fetching tambons:', error);
                        });
                }
            });

            tambonSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];

                const zipCode = selectedOption.getAttribute('data-zip');

                if (zipCode) {
                    zipCodeInput.value = zipCode;
                } else {
                    zipCodeInput.value = '';
                }
            });
        });
    </script>






</div>




@endsection