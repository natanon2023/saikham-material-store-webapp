@extends('layouts.admin')

@section('content')
    <div class="main-content">

        <div class="boxmaterial">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3>รายละเอียดข้อมูลผู้ใช้งาน</h3>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary float-right">ย้อนกลับ</a>
            </div>
        </div>
        <div class="boxmaterial" style="margin-top: 15px; padding: 15px;">
            ข้อมูลทั่วไป
        </div>
        <div class="box">
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">ชื่อ - นามสกุล :</label>
                    <div class="form-input">{{ $users->name }} {{ $users->last_name }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">ชื่อเล่น :</label>
                    <div class="form-input">{{ $users->nickname }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">วันเกิด :</label>
                    <div class="form-input">{{ $users->profile->birth_date ?? 'วันเกิด' }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">อีเมล :</label>
                    <div class="form-input">{{ $users->email }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">เบอร์โทรศัพท์ :</label>
                    <div class="form-input">{{ $users->phone_number ?? 'ไม่มีข้อมูล' }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">สถานะผู้ใช้งาน :</label>
                    @if ($users->role == 'admin')
                        <div class="form-input">แอดมิน</div> 
                    @elseif ($users->role == 'technician')   
                        <div class="form-input">ช่าง</div>               
                    @endif
                </div>
            </div>
        </div>

        <div class="boxmaterial" style="margin-top: 15px; padding: 15px;">
            ข้อมูลที่อยู่
        </div>
        <div class="box">
            <div class="box-control">
                <div class="form-group">
                    <label class="form-label">บ้านเลขที่ :</label>
                    <div class="form-input">{{ $users->profile->house_number ?? 'ไม่มีข้อมูล' }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">หมู่ที่ :</label>
                    <div class="form-input">{{ $users->profile->moo ?? 'ไม่มีข้อมูล'}}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">ถนน :</label>
                    <div class="form-input">{{ $users->profile->road ?? 'ไม่มีข้อมูล'}}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">ซอย :</label>
                    <div class="form-input">{{ $users->profile->alley ?? 'ไม่มีข้อมูล' }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">หมู่บ้าน/ชุมชน :</label>
                    <div class="form-input">{{  $users->profile->village ?? 'ไม่มีข้อมูล'  }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">ตำบล/แขวง :</label>
                    <div class="form-input">{{ $users->profile->tambon->name_th ?? 'ไม่มีข้อมูล' }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">อำเภอ/เขต :</label>
                    <div class="form-input">{{ $users->profile->amphure->name_th ?? 'ไม่มีข้อมูล'}}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">จังหวัด :</label>
                    <div class="form-input">{{ $users->profile->province->name_th ?? 'ไม่มีข้อมูล'}}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">รหัสไปรษณีย์ :</label>
                    <div class="form-input">{{ $users->profile->tambon->zip_code ?? 'ไม่มีข้อมูล'}}</div>
                </div>

            </div>

        </div>


    </div>
@endsection
