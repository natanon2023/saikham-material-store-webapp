@extends('layouts.admin')

@section('content')
<div class="main-content">

    @include('components.successanderror')

    @include('components.progress-steps2')

    <div class="boxmaterial" style="margin-top: 20px; margin-bottom: 20px; display:flex ; justify-content: space-between;">
        <h5>บันทึกข้อมูลค่าใช้จ่ายเพิ่มเติม</h5>
        <a onclick="history.back()" class="btn btn-primary">ย้อนกลับ</a>
    </div>
    <div class="boxmaterial" style="display: flex; justify-content: space-between; ">
        ค่าใช้จ่ายเพิ่มเติม
        <a href="{{ route('admin.projects.formprojectexpense',$project->id) }}" class="btn btn-secondary">เพิ่มข้อมูลค่าใช้จ่าย</a>
    </div>
    @if ($project->projectexpenses->count() > 0)

    <div style="margin-bottom: 20px; margin-top: 10px;">

        <table style="text-align: center;">
            <tr>
                <th>รายการที่</th>
                <th>รายการค่าใช้จ่าย</th>
                <th>ค่าใช้จ่าย</th>
                <th>วันที่ใช้จ่าย</th>
                <th>รายละเอียด</th>
                <th>ผู้เพิ่ม</th>
                <th>จัดการ</th>
            </tr>
            @foreach ($project->projectexpenses as $expense)
            <tr>

                <td>{{ $loop->iteration }}</td>
                <td>{{ $expense->type->name }}</td>
                <td>{{number_format($expense->amount,2).' บาท'  }}</td>
                <td>
                    {{ $expense->expense_date
                        ? \Carbon\Carbon::parse($expense->expense_date)
                        ->locale('th') 
                        ->addYears(543) 
                        ->isoFormat('D MMMM YYYY') 
                        : 'ยังไม่ได้กำหนดวันทำงาน' 
                    }}
                </td>
                <td>{{ $expense->description ?? '-' }}</td>
                <td>{{ $expense->creator->name }}</td>

                <td>
                    <a href="{{ route('admin.projects.formeditProjectexpense',$expense->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                        <i class="fas fa-edit "></i>
                    </a>

                    <form action="{{ route('admin.projects.deleteProjectExpense', $expense->id)  }}" method="POST"
                        style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" title="ลบ">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
        <div>

        </div>
        <div class="sum-expense" >
            <p>รวมค่าใช้จ่ายปัจจุบันทั้งสิ้น </p>
            <p>{{ number_format($project->projectexpenses->sum('amount'),2 ).' บาท ' }}</p>
        </div>
        <div style="margin-top : 20px;">
            <form action="{{ route('admin.projects.updatestatuswaiting_survey',$project->id )}}" method="post">
                @csrf
                    <input type="hidden" name="id" value="{{ $project->id }}">
                    <div style="display: flex; justify-content: end;">
                      <button type="submit" class="btn btn-secondary">บันทึกข้อมูล</button>  
                    </div>
            </form>
        </div>
    </div>

    @else ()
    <div class="box">
        <div style="padding: 20px;">
            <center>ยังไม่ได้เพิ่มข้อมูลค่าใช้จ่าย</center>
        </div>
    </div>
    @endif




</div>

@endsection