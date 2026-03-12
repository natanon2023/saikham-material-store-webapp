@extends('layouts.customer')
@section('content')
<div class="main-content">
    <div class="boxmaterial" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>รายละเอียดโครงการ: {{ $project->projectname->name }}</h3>
    </div>
    <div style="background: white; margin-bottom: 20px; padding: 30px; background-color: white;">
        <div  style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div style="text-align: left;">
                <strong>ชื่อลูกค้า:</strong> {{ $project->customer->first_name }} {{ $project->customer->last_name }}<br>
                <strong>ที่อยู่:</strong> {{ $project->customer->house_number }} ต.{{ $project->customer->tambon->name_th ?? '-' }}
                อ.{{ $project->customer->amphure->name_th ?? '-' }}  จ.{{ $project->customer->province->name_th }}<br>
                <strong>เบอร์โทรศัพท์:</strong> {{ $project->customer->phone }}
            </div>
            <div style="text-align: right;">
                <strong>วันที่เริ่มโครงการ:</strong> {{ date('d/m/Y', strtotime($project->created_at)) }}<br>
                <strong>สถานะการดำเนินงานปัจจุบัน:</strong>{{ $statusesthiname }}
            </div>
        </div>
    </div>

    <div class="boxmaterial" style="margin-top: 20px;">รายการติดตั้ง</div>
    <div>
        <table>
            <tr style="background: #f8f9fa;">
                <th style="padding: 10px;">รายการ</th>
                <th>ขนาด</th>
                <th>จำนวน</th>
                <th>สถานที่ติดตั้ง</th>
            </tr>
            @foreach($project->customerneed as $need)
            <tr>
                <td style="padding: 10px;">{{ $need->productset->productSetName->name }}</td>
                <td>{{ $need->width }} x {{ $need->high }} ซม.</td>
                <td>{{ $need->quantity }} ชุด</td>
                <td>{{ $need->location }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="boxmaterial" style="margin-top: 20px;">สรุปรายการค่าใช้จ่าย</div>
    <div>
        <table>
            <thead>
                <tr style="border-bottom: 2px solid #eee;">
                    <th align="left" style="padding: 10px;">ประเภทค่าใช้จ่าย</th>
                    <th align="left">รายละเอียด</th>
                    <th align="right">จำนวนเงิน</th>
                </tr>
            </thead>
            <tbody>
                @php $totalExpense = 0; @endphp
                @foreach($project->projectexpenses as $expense)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">{{ $expense->type->name }}</td>
                    <td>{{ $expense->description }}</td>
                    <td align="right">{{ number_format($expense->amount, 2) }} บาท</td>
                </tr>
                @php $totalExpense += $expense->amount; @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f1f9ff;">
                    <td colspan="2" align="left" style="padding: 10px;"><strong>รวมค่าใช้จ่ายทั้งสิ้น:</strong></td>
                    <td align="right"><strong>{{ number_format($totalExpense, 2) }} บาท</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection