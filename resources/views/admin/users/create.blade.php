@extends('layouts.admin')

@section('content')
    <div class="main-content">
        @include('components.successanderror')

        <div class="boxmaterial">
            <h3>เพิ่มผู้ใช้ใหม่</h3>
        </div>

        <div class="box2">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div style="margin-top: 30px; margin-bottom: 15px; font-size: 16px; font-weight: bold">
                    ข้อมูลทั่วไป
                </div>

                <hr>

                <div class="box-control">
                    <div class="form-group">
                        <label class="form-label">ชื่อ :</label>
                        <input class="form-input" type="text" name="name" value="{{ old('name') }}" placeholder="พิมพ์ชื่อ" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">นามสกุล :</label>
                        <input class="form-input" type="text" name="last_name" value="{{ old('last_name') }}"
                            placeholder="พิมพ์นามสกุล" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">ชื่อเล่น :</label>
                        <input class="form-input" type="text" name="nickname" value="{{ old('nickname') }}"
                            placeholder="พิมพ์ชื่อเล่น" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">วันเกิด :</label>
                        <input class="form-input" type="date" name="birth_date" value="{{ old('birth_date') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">อีเมล :</label>
                        <input class="form-input" type="email" name="email" value="{{ old('email') }}"
                            placeholder="พิมพ์อีเมล" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">เบอร์โทรศัพท์ :</label>
                        <input class="form-input" type="text" name="phone_number" value="{{ old('phone_number') }}"
                            placeholder="พิมพ์เบอร์โทรศัพท์" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">รหัสผ่าน :</label>
                        <input class="form-input" type="password" name="password" placeholder="ตั้งค่ารหัสผ่าน" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">ยืนยันรหัสผ่าน :</label>
                        <input class="form-input" type="password" name="password_confirmation"
                            placeholder="กรอกรหัสผ่านที่ตั้งค่าอีกครั้งเพื่อยืนยัน" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">สถานะผู้ใช้งาน :</label>
                        <select class="form-select" name="role" required>
                            <option value="">เลือกสถานะผู้ใช้งาน</option>
                            <option value="technician" {{ old('role') == 'technician' ? 'selected' : '' }}>ช่าง</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>แอดมิน</option>
                        </select>
                    </div>

                </div>

                <div style="margin-top: 30px; margin-bottom: 15px; font-size: 16px; font-weight: bold">
                    ข้อมูลที่อยู่
                </div>

                <hr>

                <div class="box-control">
                    <div class="form-group">
                        <label class="form-label">บ้านเลขที่ :</label>
                        <input class="form-input" type="text" name="house_number" value="{{ old('house_number') }}"
                            placeholder="พิมพ์บ้านเลขที่" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">หมู่ที่ :</label>
                        <input class="form-input" type="text" name="moo" value="{{ old('moo') }}"
                            placeholder="พิมพ์หมู่ที่" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">ถนน :</label>
                        <input class="form-input" type="text" name="road" value="{{ old('road') }}"
                            placeholder="ถ้ามี">
                    </div>

                    <div class="form-group">
                        <label class="form-label">ซอย :</label>
                        <input class="form-input" type="text" name="alley" value="{{ old('alley') }}"
                            placeholder="ถ้ามี">
                    </div>

                    <div class="form-group">
                        <label class="form-label">หมู่บ้าน/ชุมชน :</label>
                        <input class="form-input" type="text" name="village" value="{{ old('village') }}"
                            placeholder="พิมพ์ชื่อหมู่บ้านหรือชุมชน" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">จังหวัด :</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if ($provinces->count() > 5)
                                <div style="position: relative; flex: 1;">
                                    <input class="form-input" type="text" id="province_search_input"
                                        placeholder="พิมพ์เพื่อค้นหาจังหวัด..."
                                        value="{{ $provinces->firstWhere('id', old('province_id'))->name_th ?? '' }}"
                                        data-selected-id="{{ old('province_id') }}"
                                        data-selected-name="{{ $provinces->firstWhere('id', old('province_id'))->name_th ?? '' }}">
                                    <input type="hidden" name="province_id" id="selected_province_id"
                                        value="{{ old('province_id') }}" required>
                                    <div class="dropdown-list" id="province_search_dropdown">
                                        @foreach ($provinces as $province)
                                            <div class="dropdown-item" data-province-id="{{ $province->id }}"
                                                data-province-name="{{ $province->name_th }}">
                                                {{ $province->name_th }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <select class="form-select" name="province_id" id="province_select"
                                    onchange="loadAmphures(this.value)" style="flex: 1;" required>
                                    <option value="">เลือกจังหวัด</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                            {{ $province->name_th }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label class="form-label">อำเภอ/เขต :</label>
                        <select class="form-select" name="amphure_id" id="amphure_select"
                            onchange="loadTambons(this.value)" disabled required>
                            <option value="">เลือกอำเภอ/เขต</option>
                        </select>
                    </div>

                
                    <div class="form-group">
                        <label class="form-label">ตำบล/แขวง :</label>
                        <select class="form-select" name="tambon_id" id="tambon_select" disabled required>
                            <option value="">เลือกตำบล/แขวง</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save" style="margin-right: 5px;"></i>
                            บันทึกข้อมูล
                        </button>
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

            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                amphureSelect.innerHTML = '<option value="">เลือกอำเภอ</option>';
                tambonSelect.innerHTML = '<option value="">เลือกตำบล</option>';

                if (provinceId) {
                    fetch(`/admin/get-amphures/${provinceId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(amphure => {
                                amphureSelect.innerHTML +=
                                    `<option value="${amphure.id}">${amphure.name_th}</option>`;
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching amphures:', error);
                        });
                }
            });


            amphureSelect.addEventListener('change', function() {
                const amphureId = this.value;
                tambonSelect.innerHTML = '<option value="">เลือกตำบล</option>';

                if (amphureId) {
                    fetch(`/admin/get-tambons/${amphureId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(tambon => {
                                tambonSelect.innerHTML +=
                                    `<option value="${tambon.id}">${tambon.name_th}</option>`;
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching tambons:', error);
                        });
                }
            });
        });
    </script>
    <script>
        const provinceMap = {};
        @foreach ($provinces as $province)
            provinceMap['{{ $province->id }}'] = {
                id: '{{ $province->id }}',
                name: '{{ $province->name_th }}'
            };
        @endforeach

        const provinceAmphureMap = {};
        @foreach ($provinces as $province)
            if (!provinceAmphureMap['{{ $province->id }}']) {
                provinceAmphureMap['{{ $province->id }}'] = [];
            }
            @foreach ($province->amphures as $amphure)
                provinceAmphureMap['{{ $province->id }}'].push({
                    id: '{{ $amphure->id }}',
                    name: '{{ $amphure->name_th }}'
                });
            @endforeach
        @endforeach

        const amphureTambonMap = {};
        @foreach ($amphures as $amphure)
            if (!amphureTambonMap['{{ $amphure->id }}']) {
                amphureTambonMap['{{ $amphure->id }}'] = [];
            }
            @foreach ($amphure->tambons as $tambon)
                amphureTambonMap['{{ $amphure->id }}'].push({
                    id: '{{ $tambon->id }}',
                    name: '{{ $tambon->name_th }}'
                });
            @endforeach
        @endforeach

        console.log('Province Map:', provinceMap);
        console.log('Province-Amphure Map:', provinceAmphureMap);
        console.log('Amphure-Tambon Map:', amphureTambonMap);
    </script>
    <script>
        function loadAmphures(provinceId) {
            const amphureSelect = document.getElementById('amphure_select');
            const tambonSelect = document.getElementById('tambon_select');

            amphureSelect.innerHTML = '<option value="">เลือกอำเภอ/เขต</option>';
            tambonSelect.innerHTML = '<option value="">เลือกตำบล/แขวง</option>';
            amphureSelect.disabled = true;
            tambonSelect.disabled = true;

            if (provinceId && provinceAmphureMap[provinceId]) {
                const amphures = provinceAmphureMap[provinceId];

                amphures.forEach(amphure => {
                    const option = document.createElement('option');
                    option.value = amphure.id;
                    option.textContent = amphure.name;
                    amphureSelect.appendChild(option);
                });

                amphureSelect.disabled = false;
            }
        }

        function loadTambons(amphureId) {
            const tambonSelect = document.getElementById('tambon_select');

            tambonSelect.innerHTML = '<option value="">เลือกตำบล/แขวง</option>';
            tambonSelect.disabled = true;

            if (amphureId && amphureTambonMap[amphureId]) {
                const tambons = amphureTambonMap[amphureId];

                tambons.forEach(tambon => {
                    const option = document.createElement('option');
                    option.value = tambon.id;
                    option.textContent = tambon.name;
                    tambonSelect.appendChild(option);
                });

                tambonSelect.disabled = false;
            }
        }

        @if ($provinces->count() > 5)
            function setupProvinceSearch() {
                const searchInput = document.getElementById('province_search_input');
                const dropdown = document.getElementById('province_search_dropdown');
                const hiddenInput = document.getElementById('selected_province_id');

                if (!searchInput) return;

                searchInput.addEventListener('focus', function() {
                    dropdown.style.display = 'block';
                });

                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.style.display = 'none';
                    }
                });

                searchInput.addEventListener('input', function() {
                    const filter = this.value.toLowerCase();
                    const items = dropdown.querySelectorAll('.dropdown-item');

                    items.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        if (text.includes(filter)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });

                dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const provinceId = this.getAttribute('data-province-id');
                        const provinceName = this.getAttribute('data-province-name');

                        searchInput.value = provinceName;
                        hiddenInput.value = provinceId;
                        dropdown.style.display = 'none';

                        loadAmphures(provinceId);
                    });
                });
            }

            setupProvinceSearch();
        @endif

        function getSelectedProvinceId() {
            @if ($provinces->count() > 5)
                return document.getElementById('selected_province_id').value;
            @else
                return document.getElementById('province_select').value;
            @endif
        }
    </script>
@endsection
