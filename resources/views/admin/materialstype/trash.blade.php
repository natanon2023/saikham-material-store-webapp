@extends('layouts.admin')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>ถังขยะ</h3>
        <div class="box3">
            ข้อมูลที่ถูกลบทั้งหมด ({{ $allDeletedItems->count() }} รายการ)
        </div>
    </div>

    <div style="padding-top: 12px;">
        @if($allDeletedItems->count() > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8f9fa; text-align: center;">
                        <th style="padding: 12px; border: 1px solid #dee2e6; ">ประเภทวัสดุ</th>
                        <th style="padding: 12px; border: 1px solid #dee2e6; ">ชื่อ</th>
                        <th style="padding: 12px; border: 1px solid #dee2e6; ">วันที่ลบ</th>
                        <th style="padding: 12px; border: 1px solid #dee2e6; ">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allDeletedItems as $item)
                    <tr style="border-bottom: 1px solid #dee2e6; text-align: center;">
                        <td style="padding: 12px; border: 1px solid #dee2e6;">
                           {{ $item->material_type_name }}
                        </td>
                        <td style="padding: 12px; border: 1px solid #dee2e6;">{{ $item->name }}</td>
                        <td style="padding: 12px; border: 1px solid #dee2e6;">{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 12px; border: 1px solid #dee2e6; text-align: center;">
                            <form action="{{ route('materialstype.restore') }}" method="POST" style="display: inline-block; margin-right: 5px;">
                                @csrf
                                <input type="hidden" name="type" value="{{ $item->material_type }}">
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="submit" 
                                        class="btn btn-secondary"
                                        onclick="return confirm('ต้องการกู้คืน {{ $item->material_type_name }} &quot;{{ $item->name }}&quot; หรือไม่?')">
                                    กู้คืน
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 40px; color: #6c757d;">
                <p style="font-size: 18px; margin-bottom: 10px;">ไม่มีข้อมูลที่ถูกลบ</p>
            </div>
        @endif
    </div>
</div>
@endsection