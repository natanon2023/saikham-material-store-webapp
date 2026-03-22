@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')
    @if ($errors->any())
    <div style="padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px 0;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('error'))
    <div style="padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
    </div>
    @endif

    <div class="boxmaterial">
        <h3>เพิ่มผู้ใช้ใหม่</h3>
    </div>

    <div class="box2" style="margin-top: 20px;">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div style="margin-top: 30px; margin-bottom: 15px; font-size: 16px; font-weight: bold">
                ข้อมูลทั่วไป
            </div>
            <hr>

            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ชื่อ :</label>
                    <input class="form-input" type="text" name="name" value="{{ old('name') }}" placeholder="พิมพ์ชื่อ" pattern="^[ก-๙a-zA-Z\s]+$" title="กรอกตัวอักษรเท่านั้น" required>
                    @error('name')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">นามสกุล :</label>
                    <input class="form-input" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="พิมพ์นามสกุล" pattern="^[ก-๙a-zA-Z\s]+$" title="กรอกตัวอักษรเท่านั้น" required>
                    @error('last_name')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">ชื่อเล่น :</label>
                    <input class="form-input" type="text" name="nickname" value="{{ old('nickname') }}" placeholder="พิมพ์ชื่อเล่น" pattern="^[ก-๙a-zA-Z\s]+$" title="กรอกตัวอักษรเท่านั้น" required>
                    @error('nickname')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">วันเกิด :</label>
                    <input class="form-input" type="date" name="birth_date" value="{{ old('birth_date') }}" max="{{ date('Y-m-d') }}" required>
                    @error('birth_date')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">อีเมล :</label>
                    <input class="form-input" type="email" name="email" value="{{ old('email') }}" placeholder="พิมพ์อีเมล" required>
                    @error('email')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">เบอร์โทรศัพท์ :</label>
                    <input class="form-input" type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="พิมพ์เบอร์โทรศัพท์ 10 หลัก" pattern="^0[0-9]{9}$" maxlength="10" title="เบอร์โทรต้องมี 10 หลัก และขึ้นต้นด้วย 0" required>
                    @error('phone_number')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">รหัสผ่าน :</label>
                    <input class="form-input" type="password" name="password" placeholder="ตั้งค่ารหัสผ่าน (อย่างน้อย 8 ตัวอักษร)" minlength="8" required>
                    @error('password')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">ยืนยันรหัสผ่าน :</label>
                    <input class="form-input" type="password" name="password_confirmation" placeholder="กรอกรหัสผ่านที่ตั้งค่าอีกครั้งเพื่อยืนยัน" minlength="8" required>
                    @error('password_confirmation')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">สถานะผู้ใช้งาน :</label>
                    <select class="form-select" name="role" required>
                        <option value="">เลือกสถานะผู้ใช้งาน</option>
                        <option value="technician" {{ old('role') == 'technician' ? 'selected' : '' }}>ช่าง</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>แอดมิน</option>
                    </select>
                    @error('role')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="margin-top: 30px; margin-bottom: 15px; font-size: 16px; font-weight: bold">
                ข้อมูลที่อยู่
            </div>
            <hr>

            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">บ้านเลขที่ :</label>
                    <input class="form-input" type="text" name="house_number" value="{{ old('house_number') }}" placeholder="เช่น 123 หรือ 123/4" pattern="^[0-9/]+$" title="กรอกเฉพาะตัวเลขและ /" required>
                    @error('house_number')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">หมู่ที่ :</label>
                    <input class="form-input" type="number" name="moo" value="{{ old('moo') }}" placeholder="พิมพ์หมู่ที่" min="1" max="999" required>
                    @error('moo')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">ถนน :</label>
                    <input class="form-input" type="text" name="road" value="{{ old('road') }}" placeholder="ถ้ามี" maxlength="100">
                    @error('road')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">ซอย :</label>
                    <input class="form-input" type="text" name="alley" value="{{ old('alley') }}" placeholder="ถ้ามี" maxlength="100">
                    @error('alley')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">หมู่บ้าน/ชุมชน :</label>
                    <input class="form-input" type="text" name="village" value="{{ old('village') }}" placeholder="พิมพ์ชื่อหมู่บ้านหรือชุมชน" maxlength="255" required>
                    @error('village')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
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
                            <input type="hidden" name="province_id" id="selected_province_id" value="{{ old('province_id') }}" required>
                            <div class="dropdown-list" id="province_search_dropdown">
                                @foreach ($provinces as $province)
                                <div class="dropdown-item" data-province-id="{{ $province->id }}" data-province-name="{{ $province->name_th }}">
                                    {{ $province->name_th }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <select class="form-select" name="province_id" id="province_select" onchange="loadAmphures(this.value)" style="flex: 1;" required>
                            <option value="">เลือกจังหวัด</option>
                            @foreach ($provinces as $province)
                            <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                {{ $province->name_th }}
                            </option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                    @error('province_id')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">อำเภอ/เขต :</label>
                    <select class="form-select" name="amphure_id" id="amphure_select" onchange="loadTambons(this.value)" disabled required>
                        <option value="">เลือกอำเภอ/เขต</option>
                    </select>
                    @error('amphure_id')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">ตำบล/แขวง :</label>
                    <select class="form-select" name="tambon_id" id="tambon_select" disabled required>
                        <option value="">เลือกตำบล/แขวง</option>
                    </select>
                    @error('tambon_id')
                        <div style="color:red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">
                        บันทึกข้อมูล
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const provinceMap = {};
    @foreach($provinces as $province)
    provinceMap['{{ $province->id }}'] = {
        id: '{{ $province->id }}',
        name: '{{ $province->name_th }}'
    };
    @endforeach

    const provinceAmphureMap = {};
    @foreach($provinces as $province)
    if (!provinceAmphureMap['{{ $province->id }}']) {
        provinceAmphureMap['{{ $province->id }}'] = [];
    }
    @foreach($province->amphures as $amphure)
    provinceAmphureMap['{{ $province->id }}'].push({
        id: '{{ $amphure->id }}',
        name: '{{ $amphure->name_th }}'
    });
    @endforeach
    @endforeach

    const amphureTambonMap = {};
    @if(isset($amphures))
    @foreach($amphures as $amphure)
    if (!amphureTambonMap['{{ $amphure->id }}']) {
        amphureTambonMap['{{ $amphure->id }}'] = [];
    }
    @foreach($amphure->tambons as $tambon)
    amphureTambonMap['{{ $amphure->id }}'].push({
        id: '{{ $tambon->id }}',
        name: '{{ $tambon->name_th }}'
    });
    @endforeach
    @endforeach
    @endif

    function loadAmphures(provinceId) {
        const amphureSelect = document.getElementById('amphure_select');
        const tambonSelect = document.getElementById('tambon_select');

        amphureSelect.innerHTML = '<option value="">เลือกอำเภอ/เขต</option>';
        tambonSelect.innerHTML = '<option value="">เลือกตำบล/แขวง</option>';
        amphureSelect.disabled = true;
        tambonSelect.disabled = true;

        if (provinceId && provinceAmphureMap[provinceId]) {
            provinceAmphureMap[provinceId].forEach(amphure => {
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
            amphureTambonMap[amphureId].forEach(tambon => {
                const option = document.createElement('option');
                option.value = tambon.id;
                option.textContent = tambon.name;
                tambonSelect.appendChild(option);
            });
            tambonSelect.disabled = false;
        }
    }

    @if($provinces->count() > 5)
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
            dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                item.style.display = item.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });

        dropdown.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                searchInput.value = this.getAttribute('data-province-name');
                hiddenInput.value = this.getAttribute('data-province-id');
                dropdown.style.display = 'none';
                loadAmphures(hiddenInput.value);
            });
        });
    }
    setupProvinceSearch();
    @endif
</script>
<script>
    const password = document.querySelector('input[name="password"]');
    const passwordConfirm = document.querySelector('input[name="password_confirmation"]');

    passwordConfirm.addEventListener('input', function() {
        if (this.value !== password.value) {
            this.setCustomValidity('รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน');
        } else {
            this.setCustomValidity('');
        }
    });

    password.addEventListener('input', function() {
        if (passwordConfirm.value && passwordConfirm.value !== this.value) {
            passwordConfirm.setCustomValidity('รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน');
        } else {
            passwordConfirm.setCustomValidity('');
        }
    });
</script>
@endsection