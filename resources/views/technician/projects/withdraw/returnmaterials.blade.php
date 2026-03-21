@extends('layouts.technician')

@section('content')
<div class="main-content">
    @include('components.successanderror')

    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">คืนวัสดุที่เหลือเข้าสต็อก</h3>
        <a href="{{ route('technician.projects.withdrawdetails', $project->id) }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    <div class="boxmaterial" style="margin-bottom: 20px;  border-left: 4px solid #f0a500;">
        <h4 style="margin: 0 0 8px 0;">กฎการคืนวัสดุ</h4>
        <div style="padding: 20px;">
            <li><b>กระจก</b> : ใช้งานแล้วให้กรอก 0 | ไม่ใช้งานให้กรอกตามจำนวน</li>
            <li><b>อลูมิเนียม</b> : ใช้งานแล้วให้กรอก 0 | ไม่ใช้งานให้กรอกตามจำนวน</li>
            <li><b>อุปกรณ์เสริมและวัสดุสิ้นเปลือง</b> : ใช้งานแล้วให้กรอก 0 | ไม่ใช้งานให้กรอกตามจำนวน</li>
        </div>
    </div>

    <form action="{{ route('technician.projects.store_return_materials', $project->id) }}" method="POST">
        @csrf

        @if($aluminiumItems->isNotEmpty())
        <div class="boxmaterial" style="margin-bottom: 20px;">
            <h4>อลูมิเนียม</h4>
            <p style="color: #888; font-size: 0.9em;">กรอกจำนวนเส้นที่จะคืน ถ้าไม่คืนให้ใส่ 0</p>
            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
                <thead style="background: #333; color: #fff;">
                    <tr>
                        <th width="45%">รายละเอียด</th>
                        <th width="20%" style="text-align: center;">ล็อต</th>
                        <th width="15%" style="text-align: center;">จำนวนที่เบิก</th>
                        <th width="20%" style="text-align: center;">จำนวนที่จะคืน (เส้น)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aluminiumItems as $item)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td>
                            {{ $item->material->aluminiumItem->aluminiumType->name ?? '-' }}
                            สี {{ $item->material->aluminiumItem->aluminumSurfaceFinish->name ?? '-' }}
                        </td>
                        <td align="center">{{ $item->lot }}</td>
                        <td align="center">{{ $item->quantity }} เส้น</td>
                        <td align="center">
                            <input type="number" name="return_qty[aluminium][{{ $item->id }}]" value="0" min="0" max="{{ $item->quantity }}" style="width: 70px; padding: 4px; text-align: center; border: 1px solid #ccc; ">
                            <input type="hidden" name="return_material_id[aluminium][{{ $item->id }}]" value="{{ $item->material_id }}">
                            <input type="hidden" name="return_lot[aluminium][{{ $item->id }}]" value="{{ $item->lot }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($glassItems->isNotEmpty())
        <div class="boxmaterial" style="margin-bottom: 20px;">
            <h4>กระจก</h4>
            <p style="color: #888; font-size: 0.9em;">ถ้าไม่ได้ใช้ให้กรอกจำนวนแผ่นที่จะคืน ถ้าตัดแล้วเป็นเศษใส่ 0</p>
            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
                <thead style="background: #333; color: #fff;">
                    <tr>
                        <th width="45%">รายละเอียด</th>
                        <th width="20%" style="text-align: center;">ล็อต</th>
                        <th width="15%" style="text-align: center;">จำนวนที่เบิก</th>
                        <th width="20%" style="text-align: center;">จำนวนที่จะคืน (แผ่น)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($glassItems as $item)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td>
                            {{ $item->material->glassItem->glassType->name ?? '-' }}
                            สี {{ $item->material->glassItem->colourItem->name ?? '-' }}
                        </td>
                        <td align="center">{{ $item->lot }}</td>
                        <td align="center">{{ $item->quantity }} แผ่น</td>
                        <td align="center">
                            <input type="number"
                                   name="return_qty[glass][{{ $item->id }}]"
                                   value="0" min="0" max="{{ $item->quantity }}"
                                   style="width: 70px; padding: 4px; text-align: center; border: 1px solid #ccc; ">
                            <input type="hidden" name="return_material_id[glass][{{ $item->id }}]" value="{{ $item->material_id }}">
                            <input type="hidden" name="return_lot[glass][{{ $item->id }}]" value="{{ $item->lot }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($accessoryItems->isNotEmpty())
        <div class="boxmaterial" style="margin-bottom: 20px;">
            <h4>อุปกรณ์เสริม</h4>
            <p style="color: #888; font-size: 0.9em;">กรอกจำนวนชิ้นที่จะคืน ถ้าไม่คืนให้ใส่ 0</p>
            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
                <thead style="background: #333; color: #fff;">
                    <tr>
                        <th width="45%">รายละเอียด</th>
                        <th width="20%" style="text-align: center;">ล็อต</th>
                        <th width="15%" style="text-align: center;">จำนวนที่เบิก</th>
                        <th width="20%" style="text-align: center;">จำนวนที่จะคืน (ชิ้น)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accessoryItems as $item)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td>{{ $item->material->accessoryItem->accessoryType->name ?? '-' }}</td>
                        <td align="center">{{ $item->lot }}</td>
                        <td align="center">{{ $item->quantity }} ชิ้น</td>
                        <td align="center">
                            <input type="number"
                                   name="return_qty[accessory][{{ $item->id }}]"
                                   value="0" min="0" max="{{ $item->quantity }}"
                                   style="width: 70px; padding: 4px; text-align: center; border: 1px solid #ccc; ">
                            <input type="hidden" name="return_material_id[accessory][{{ $item->id }}]" value="{{ $item->material_id }}">
                            <input type="hidden" name="return_lot[accessory][{{ $item->id }}]" value="{{ $item->lot }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($consumableItems->isNotEmpty())
        <div class="boxmaterial" style="margin-bottom: 20px;">
            <h4>วัสดุสิ้นเปลือง</h4>
            <p style="color: #888; font-size: 0.9em;">กรอกจำนวนที่จะคืน ถ้าไม่คืนให้ใส่ 0</p>
            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
                <thead style="background: #333; color: #fff;">
                    <tr>
                        <th width="45%">รายละเอียด</th>
                        <th width="20%" style="text-align: center;">ล็อต</th>
                        <th width="15%" style="text-align: center;">จำนวนที่เบิก</th>
                        <th width="20%" style="text-align: center;">จำนวนที่จะคืน</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consumableItems as $item)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td>{{ $item->material->consumableItem->consumabletype->name ?? '-' }}</td>
                        <td align="center">{{ $item->lot }}</td>
                        <td align="center">{{ $item->quantity }}</td>
                        <td align="center">
                            <input type="number"
                                   name="return_qty[consumable][{{ $item->id }}]"
                                   value="0" min="0" max="{{ $item->quantity }}"
                                   style="width: 70px; padding: 4px; text-align: center; border: 1px solid #ccc; ">
                            <input type="hidden" name="return_material_id[consumable][{{ $item->id }}]" value="{{ $item->material_id }}">
                            <input type="hidden" name="return_lot[consumable][{{ $item->id }}]" value="{{ $item->lot }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <button type="submit" class="btn btn-secondary"
                    onclick="return confirm('ยืนยันการคืนวัสดุเข้าสต็อก?');"
                    style="padding: 10px 30px; font-size: 1em;">
                ยืนยันคืนวัสดุ
            </button>
        </div>
    </form>
</div>
@endsection