@extends('layouts.admin')

@section('content')
<div class="main-content" >
    @include('components.successanderror')

    <div class="boxmaterial"  style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #333;">เติมสต็อกวัสดุ</h3>
        <a href="{{ route('admin.projects.alldetail', $project->id) }}" class="btn btn-primary" >ย้อนกลับ</a>
    </div>

    @if($allComplete && count($purchaseItems) > 0)
        <div style="text-align: center; padding: 30px; background-color: #f6fdf6;  border: 1px solid #cce8d4; margin-bottom:20px;">
            <h4 style="margin-bottom: 20px;">วัสดุสั่งซื้อครบตามจำนวนแล้ว</h4>
            <form action="{{ route('admin.projects.updatestatusreadytowithdraw', $project->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary" style="padding: 12px 30px; font-weight: bold;">
                    ยืนยันวัสดุครบและเตรียมการเบิก
                </button>
            </form>
        </div>
    @endif

    <form action="{{ route('admin.projects.restockform', $project->id) }}" method="POST">
        @csrf

        <table width="100%" cellpadding="12" cellspacing="0" style="border-collapse: collapse; margin-bottom: 20px; border: 1px solid #eee;">
            <thead style="background:#f9f9f9; color:#333; border-bottom: 2px solid #ddd;">
                <tr align="left">
                    <th width="5%" style="text-align: center;">
                        <input type="checkbox" id="select-all" style="transform: scale(1.2); cursor: pointer;" title="เลือกทั้งหมด">
                    </th>
                    <th width="10%" style="text-align: center;">รูปภาพ</th>
                    <th width="40%">ประเภท / รายละเอียด</th>
                    <th width="15%" style="text-align: center;">จำนวนที่ต้องซื้อ</th>
                    <th width="15%" style="text-align: center;">สต็อกปัจจุบัน</th>
                    <th width="15%" style="text-align: center;">สถานะ</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($purchaseItems as $item)
                    @php $mat = $item->material; @endphp
                    <tr style="border-bottom: 1px solid #eee;">
                        <td align="center">
                            @if(!$item->is_complete)
                                <input type="checkbox" name="selected_materials[]" value="{{ $mat->id }}" class="item-checkbox" style="transform: scale(1.2); cursor: pointer;">
                            @else
                                <i class="fa-solid fa-circle-check" style="color: #1e8e3e; font-size: 1.2rem;"></i>
                            @endif
                        </td>
                        <td align="center">
                            @if ($mat->material_type == 'อลูมิเนียม' && !empty($mat->aluminiumItem->image_aluminium_item))
                                <img src="data:image/png;base64,{{ base64_encode($mat->aluminiumItem->image_aluminium_item) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            @elseif ($mat->material_type == 'กระจก' && !empty($mat->glassItem->image_glass_item))
                                <img src="data:image/png;base64,{{ base64_encode($mat->glassItem->image_glass_item) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            @elseif ($mat->material_type == 'อุปกรณ์เสริม' && !empty($mat->accessoryItem->image_accessory_item))
                                <img src="data:image/png;base64,{{ base64_encode($mat->accessoryItem->image_accessory_item) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            @elseif ($mat->material_type == 'วัสดุสิ้นเปลือง' && !empty($mat->consumableItem->image_consumable_item))
                                <img src="data:image/png;base64,{{ base64_encode($mat->consumableItem->image_consumable_item) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            @else
                                <div style="width: 50px; height: 50px; background-color: #f3f3f3; display: flex; justify-content: center; align-items: center; color: #bbb; font-size: 0.7em; border-radius: 4px;">ไม่มีรูป</div>
                            @endif
                        </td>
                        <td>
                            <b style="color: #333;">{{ $mat->material_type }}</b><br>
                            <small style="color: #666;">
                                {{ $item->description ?? 'ไม่มีรายละเอียด' }}
                            </small>
                        </td>

                        <td align="center"><b>{{ (float) $item->quantity }}</b></td>
                        <td align="center">{{ $item->current_stock }}</td>
                        <td align="center">
                            @if($item->is_complete)
                                <span style="color: #ffff; font-size: 0.9em; font-weight: bold;  background-color: #1e8e3e; padding:10px; border-radius:20px;">ครบแล้ว</span>
                            @else
                                <span style="color: #ffffff; font-size: 0.9em; font-weight: bold ; background-color: #d93025; padding:10px;  border-radius:20px;" >ยังไม่ครบ</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" align="center" style="padding: 20px; color: #888;">ไม่มีรายการสั่งซื้อวัสดุ</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(!$allComplete && count($purchaseItems) > 0)
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="submit" class="btn btn-secondary" style="padding: 10px 25px;">
                    เติมสต็อกวัสดุที่เลือก
                </button>
            </div>
        @endif
    </form>
</div>

<script>
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.querySelectorAll('.item-checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>
@endsection