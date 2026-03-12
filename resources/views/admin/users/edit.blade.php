@extends('layouts.admin')

@section('content')
    <div class="main-content">
        @include('components.successanderror')

        <div class="boxmaterial" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <h3>แก้ไขข้อมูลผู้ใช้: {{ $user->name }} {{ $user->last_name }}</h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                 ย้อนกลับ
            </a>
        </div>

        <div class="box2">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                
                <div style="margin-bottom: 15px; font-size: 16px; font-weight: bold;">
                    ข้อมูลทั่วไป
                </div>
                <hr>

                <div class="box-control">
                    <div class="form-group">
                        <label class="form-label">ชื่อ :</label>
                        <input class="form-input" type="text" name="name"
                               value="{{ old('name', $user->name) }}" placeholder="พิมพ์ชื่อ">
                        @error('name')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">นามสกุล :</label>
                        <input class="form-input" type="text" name="last_name"
                               value="{{ old('last_name', $user->last_name) }}" placeholder="พิมพ์นามสกุล">
                        @error('last_name')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">ชื่อเล่น :</label>
                        <input class="form-input" type="text" name="nickname"
                               value="{{ old('nickname', $user->nickname) }}" placeholder="พิมพ์ชื่อเล่น">
                        @error('nickname')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">วันเกิด :</label>
                        <input class="form-input" type="date" name="birth_date"
                               value="{{ old('birth_date', optional($user->profile)->birth_date) }}">
                        @error('birth_date')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">อีเมล :</label>
                        <input class="form-input" type="email" name="email"
                               value="{{ old('email', $user->email) }}" placeholder="พิมพ์อีเมล">
                        @error('email')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">เบอร์โทรศัพท์ :</label>
                        <input class="form-input" type="text" name="phone_number"
                               value="{{ old('phone_number', $user->phone_number) }}" placeholder="พิมพ์เบอร์โทรศัพท์">
                        @error('phone_number')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">รหัสผ่านใหม่ :</label>
                        <input class="form-input" type="password" name="password"
                               placeholder="ไม่ต้องกรอกหากไม่ต้องการเปลี่ยน">
                        @error('password')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                        <small style="color: #666; margin-top: 5px; display: block;">
                            หากต้องการเปลี่ยนรหัสผ่าน กรุณากรอกรหัสผ่านใหม่
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">ยืนยันรหัสผ่านใหม่ :</label>
                        <input class="form-input" type="password" name="password_confirmation"
                               placeholder="ยืนยันรหัสผ่านใหม่">
                        @error('password_confirmation')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">สถานะผู้ใช้งาน :</label>
                        <select class="form-select" name="role">
                            <option value="">เลือกสถานะผู้ใช้งาน</option>
                            <option value="technician" {{ old('role', $user->role) == 'technician' ? 'selected' : '' }}>ช่าง</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>แอดมิน</option>
                        </select>
                        @error('role')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="margin-top: 30px; margin-bottom: 15px; font-size: 16px; font-weight: bold;">
                    ข้อมูลที่อยู่
                </div>
                <hr>

                <div class="box-control">
                    <div class="form-group">
                        <label class="form-label">บ้านเลขที่ :</label>
                        <input class="form-input" type="text" name="house_number"
                               value="{{ old('house_number', optional($user->profile)->house_number) }}"
                               placeholder="พิมพ์บ้านเลขที่ (ไม่จำเป็น)">
                        @error('house_number')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">หมู่ที่ :</label>
                        <input class="form-input" type="text" name="moo"
                               value="{{ old('moo', optional($user->profile)->moo) }}"
                               placeholder="พิมพ์หมู่ที่ (ไม่จำเป็น)">
                        @error('moo')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">ถนน :</label>
                        <input class="form-input" type="text" name="road"
                               value="{{ old('road', optional($user->profile)->road) }}"
                               placeholder="ถ้าไม่มี พิมพ์ -">
                        @error('road')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">ซอย :</label>
                        <input class="form-input" type="text" name="alley"
                               value="{{ old('alley', optional($user->profile)->alley) }}"
                               placeholder="ถ้าไม่มี พิมพ์ -">
                        @error('alley')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">หมู่บ้าน/ชุมชน :</label>
                        <input class="form-input" type="text" name="village"
                               value="{{ old('village', optional($user->profile)->village) }}"
                               placeholder="พิมพ์ชื่อหมู่บ้านหรือชุมชน (ไม่จำเป็น)">
                        @error('village')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">จังหวัด :</label>
                        <select class="form-select" name="province_id" id="province_select" onchange="loadAmphures(this.value)">
                            <option value="">เลือกจังหวัด</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}"
                                    {{ old('province_id', optional($user->profile)->province_id) == $province->id ? 'selected' : '' }}>
                                    {{ $province->name_th }}
                                </option>
                            @endforeach
                        </select>
                        @error('province_id')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">อำเภอ/เขต :</label>
                        <select class="form-select" name="amphure_id" id="amphure_select" onchange="loadTambons(this.value)">
                            <option value="">เลือกอำเภอ/เขต</option>
                        </select>
                        @error('amphure_id')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">ตำบล/แขวง :</label>
                        <select class="form-select" name="tambon_id" id="tambon_select">
                            <option value="">เลือกตำบล/แขวง</option>
                        </select>
                        @error('tambon_id')
                            <div style="color:red; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa fa-save" style="margin-right: 5px;"></i>
                            บันทึกการแก้ไข
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
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

        function loadAmphures(provinceId) {
            const amphureSelect = document.getElementById('amphure_select');
            const tambonSelect = document.getElementById('tambon_select');

            amphureSelect.innerHTML = '<option value="">เลือกอำเภอ/เขต</option>';
            tambonSelect.innerHTML = '<option value="">เลือกตำบล/แขวง</option>';

            if (provinceId && provinceAmphureMap[provinceId]) {
                const amphures = provinceAmphureMap[provinceId];
                amphures.forEach(amphure => {
                    const option = document.createElement('option');
                    option.value = amphure.id;
                    option.textContent = amphure.name;

                    if (amphure.id == '{{ old("amphure_id", optional($user->profile)->amphure_id) }}') {
                        option.selected = true;
                    }

                    amphureSelect.appendChild(option);
                });

                const selectedAmphureId = '{{ old("amphure_id", optional($user->profile)->amphure_id) }}';
                if (selectedAmphureId) {
                    loadTambons(selectedAmphureId);
                }
            }
        }

        function loadTambons(amphureId) {
            const tambonSelect = document.getElementById('tambon_select');
            tambonSelect.innerHTML = '<option value="">เลือกตำบล/แขวง</option>';

            if (amphureId && amphureTambonMap[amphureId]) {
                const tambons = amphureTambonMap[amphureId];
                tambons.forEach(tambon => {
                    const option = document.createElement('option');
                    option.value = tambon.id;
                    option.textContent = tambon.name;


                    if (tambon.id == '{{ old("tambon_id", optional($user->profile)->tambon_id) }}') {
                        option.selected = true;
                    }

                    tambonSelect.appendChild(option);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const selectedProvinceId = '{{ old("province_id", optional($user->profile)->province_id) }}';
            if (selectedProvinceId) {
                loadAmphures(selectedProvinceId);
            }
        });
    </script>
@endsection
