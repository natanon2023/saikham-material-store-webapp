@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0;">อัปโหลดภาพหลังติดตั้งและยืนยันงานเสร็จสิ้น</h3>
        <a href="{{ route('admin.projects.alldetail', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>
    @if($project->customerneed->isNotEmpty() && $project->customerneed->every(fn($need) => !empty($need->imageafter)))
        <div style="margin-bottom: 20px; display: flex; justify-content: end; align-items: center;">
            <form action="{{ route('admin.projects.updatestatuscompleted', $project->id) }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-secondary">ยืนยันงานเสร็จสมบูรณ์</button>
            </form>
        </div>
    @endif
    

        <table border="1" cellpadding="12" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; background: #fff;" >
            <thead style="background-color: #f8f9fa;">
                <tr align = "center">
                    <th style="width: 5%;">ลำดับ</th>
                    <th style="width: 20%;">ผลิตภัณฑ์</th>
                    <th style="width: 15%;">ตำแหน่งติดตั้ง</th>
                    <th style="width: 15%;">ภาพก่อนติดตั้ง</th>
                    <th style="width: 25%;">ภาพหลังติดตั้ง</th>
                    <th style="width: 20%;">จัดการภาพหลังติดตั้ง</th>
                </tr>
            </thead>
            <tbody>
                @forelse($project->customerneed as $index => $need)
                <tr align = "center">
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $need->productset->productSetName->name ?? 'ไม่ระบุ' }}</strong><br>
                        <small style="color: #666;">ขนาด: {{ $need->width }} x {{ $need->height }} ซม.</small>
                    </td>

                    <td>{{ $need->projectImage->imagetype->name ?? 'ไม่ระบุตำแหน่ง' }}</td>

                    <td>
                        @if($need->installation_image)
                            <img src="data:image/jpeg;base64,{{ base64_encode($need->installation_image) }}" style="width: 100px; height: 100px; object-fit: cover;  border: 1px solid #ddd;">
                        @else
                            <span style="color: #999;">ไม่มีภาพ</span>
                        @endif
                    </td>

                    <td>
                        @if($need->imageafter)
                            <img src="data:image/jpeg;base64,{{ base64_encode($need->imageafter) }}" style="width: 150px; height: 100px; object-fit: cover;  border: 2px solid #ddd;">
                        @else
                            <div style=" background-color:#dc3545; color: #fff ; font-size: 0.9em; font-weight: bold; border-radius:20px; width:fit-content; padding:5px;">
                                ยังไม่ได้อัปโหลดภาพ
                            </div>
                        @endif
                    </td>

                    <td>
                        @if(!$need->imageafter)
                            <form action="{{ route('admin.projects.uploadafterimage', $need->id) }}" method="POST" enctype="multipart/form-data" style="margin: 0;">
                                @csrf
                                <input type="file" name="imageafter" accept="image/*" required style="margin-bottom: 8px; font-size: 0.8em; max-width: 180px;">
                                <button type="submit" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.85em;">อัปโหลด</button>
                            </form>
                        @else
                            <form action="{{ route('admin.projects.deleteafterimage', $need->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('ยืนยันการลบภาพหลังติดตั้งนี้?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.85em;">ลบเพื่ออัปโหลดใหม่</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #888;">ไม่พบรายการความต้องการของลูกค้า</td>
                </tr>
                @endforelse
            </tbody>
        </table>

</div>
@endsection