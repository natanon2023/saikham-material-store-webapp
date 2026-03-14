@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .card-gradient-1 { background:  #f8f8fa ; }
    .card-gradient-2 { background:  #f8f8fa ; }
    .card-gradient-3 { background:  #f8f8fa ; }
    
    .dash-card {
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.2s;
    }
    .dash-icon { font-size: 2.5rem; opacity: 0.8; }
    
    .progress-bg { background: #e9ecef; border-radius: 10px; height: 10px; width: 100%; overflow: hidden; }
    .progress-bar { background: #dc3545; height: 100%; }
</style>

<div class="main-content boxmaterial" >
    <div style="margin-bottom: 25px;">
        <h2 style="color: #333; font-weight: bold;">Dashboard</h2>
        <p style="color: #666;">ภาพรวมผลประกอบการและการปฏิบัติงาน</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="dash-card card-gradient-1">
            <div>
                <p style="margin: 0; font-size: 0.9em; opacity: 0.9;">กำไรสุทธิ (เดือนนี้)</p>
                <h2 style="margin: 5px 0 0; font-weight: bold;">฿ {{ number_format($monthlyProfit, 2) }}</h2>
            </div>
        </div>

        <div class="dash-card card-gradient-2">
            <div>
                <p style="margin: 0; font-size: 0.9em; opacity: 0.9;">โปรเจกต์ที่กำลังดำเนินการ</p>
                <h2 style="margin: 5px 0 0; font-weight: bold;">{{ $activeProjects }} งาน</h2>
            </div>
        </div>

        <div class="dash-card card-gradient-3">
            <div>
                <p style="margin: 0; font-size: 0.9em; opacity: 0.9;">วัสดุต้องสั่งซื้อด่วน</p>
                <h2 style="margin: 5px 0 0; font-weight: bold;">{{ $lowStockCount }} รายการ</h2>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 30px;">
        
        <div class="boxmaterial" style="background: white;  padding: 20px;">
            <h4 style="text-align: center; margin-bottom: 20px;">สัดส่วนสถานะโครงการ</h4>
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="boxmaterial" style="background: white;  padding: 20px;">
            <h4 style="margin-bottom: 20px;">วิเคราะห์ต้นทุนและกำไร (5 โครงการล่าสุด)</h4>
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="profitChart"></canvas>
            </div>
        </div>

    </div>

    <div class="boxmaterial" style="background: white;  padding: 20px;">
        <h4 style="color: #dc3545; margin-bottom: 15px;">สต็อกวัสดุเหลือน้อย (ต่ำกว่า 10 ชิ้น)</h4>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
            @forelse($lowStockItems as $item)
            <div style="background: #f8f9fa; padding: 15px;  border: 1px solid #eee;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <strong>{{ $item->material->material_type ?? 'วัสดุ' }} ({{ $item->lot }})</strong>
                    <span style="color: #dc3545; font-weight: bold;">{{ $item->quantity }} ชิ้น</span>
                </div>
                <div class="progress-bg">
                    <div class="progress-bar" style="width: {{ ($item->quantity / 10) * 100 }}%;"></div>
                </div>
            </div>
            @empty
            <div style="padding: 20px; color: #28a745; text-align: center; width: 100%;">
                สต็อกวัสดุเพียงพอ
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['รอดำเนินการ', 'กำลังติดตั้ง', 'เสร็จสิ้น'],
            datasets: [{
                data: [
                    {{ $statusData['รอดำเนินการ'] }}, 
                    {{ $statusData['กำลังติดตั้ง'] }}, 
                    {{ $statusData['เสร็จสิ้น'] }}
                ],
                backgroundColor: ['#fdf472', '#17a2b8', '#28a745'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const ctxProfit = document.getElementById('profitChart').getContext('2d');
    
    const projectLabels = {!! json_encode($recentFinancialProjects->pluck('project_code')) !!};
    const matCosts = {!! json_encode($recentFinancialProjects->pluck('actual_material_cost')) !!};
    const laborCosts = {!! json_encode($recentFinancialProjects->pluck('labor_cost')) !!};
    const profits = {!! json_encode($recentFinancialProjects->pluck('total_profit')) !!};

    new Chart(ctxProfit, {
        type: 'bar',
        data: {
            labels: projectLabels,
            datasets: [
                { label: 'ค่าวัสดุ', data: matCosts, backgroundColor: '#ff4343' },
                { label: 'ค่าแรง', data: laborCosts, backgroundColor: '#00acbf' },
                { label: 'กำไรสุทธิ', data: profits, backgroundColor: '#28c76f' }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                x: { stacked: true },
                y: { stacked: true } 
            }
        }
    });
</script>
@endsection