@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
    .main-content {
        padding: 1.5rem;
    }

    .dashboard-header {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .dashboard-header {
            flex-direction: row;
            align-items: center;
        }
    }

    .dashboard-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
    }

    .dashboard-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0;
    }

    .time-filter-wrap {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #fff;
        padding: 0.5rem 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #f3f4f6;
    }

    .time-filter-label {
        font-size: 0.875rem;
        color: #4b5563;
    }

    .time-filter-select {
        font-size: 0.875rem;
        border: none;
        background: transparent;
        font-weight: 600;
        color: #1f2937;
        cursor: pointer;
        outline: none;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (min-width: 768px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .kpi-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .kpi-card {
        background: #fff;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .kpi-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0 0 0.25rem 0;
    }

    .kpi-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .kpi-value.green {
        color: #16a34a;
    }

    .kpi-icon {
        padding: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .kpi-icon svg {
        width: 1.5rem;
        height: 1.5rem;
    }

    .kpi-icon.blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .kpi-icon.red {
        background: #fef2f2;
        color: #dc2626;
    }

    .kpi-icon.green {
        background: #f0fdf4;
        color: #16a34a;
    }

    .kpi-icon.purple {
        background: #faf5ff;
        color: #9333ea;
    }

    .chart-grid-main {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (min-width: 1024px) {
        .chart-grid-main {
            grid-template-columns: 2fr 1fr;
        }
    }

    .chart-grid-half {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (min-width: 1024px) {
        .chart-grid-half {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .chart-card {
        background: #fff;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #f3f4f6;
    }

    .chart-card-header {
        margin-bottom: 1rem;
    }

    .chart-card-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
    }

    .chart-card-desc {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0;
    }

    .chart-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .chart-area {
        width: 100%;
        height: 350px;
    }

    .chart-area-lg {
        width: 100%;
        height: 400px;
    }

    .chart-area-donut {
        width: 100%;
        height: 350px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .amphure-select {
        border: 1px solid #d1d5db;
        font-size: 0.875rem;
        padding: 0.375rem 0.625rem;
        outline: none;
    }
</style>

<div class="main-content">

    <div class="dashboard-header">
        <div>
            <h2 class="dashboard-title">รายงานการวิเคราะห์ข้อมูลร้านทรายคำวัสดุ</h2>
            <p class="dashboard-subtitle">ยอดขาย ต้นทุน และการปฏิบัติงาน</p>
        </div>
        <div class="time-filter-wrap">
            <span class="time-filter-label">ช่วงเวลา:</span>
            <select id="timeFilter" class="time-filter-select">
                <option value="month">รายเดือน (ปีนี้)</option>
                <option value="year">รายปี</option>
            </select>
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div>
                <p class="kpi-label">รายได้รวม</p>
                <h3 class="kpi-value" id="kpi-revenue">฿0</h3>
            </div>
        </div>
        <div class="kpi-card">
            <div>
                <p class="kpi-label">ต้นทุนรวม (วัสดุ+ค่าแรง)</p>
                <h3 class="kpi-value " style="color: #D4B483;" id="kpi-cost">฿0</h3>
            </div>
        </div>
        <div class="kpi-card">
            <div>
                <p class="kpi-label">กำไรขั้นต้น</p>
                <h3 class="kpi-value " style="color: #334E68;" id="kpi-profit">฿0</h3>
            </div>
        </div>
        <div class="kpi-card">
            <div>
                <p class="kpi-label">งานทั้งหมด</p>
                <h3 class="kpi-value" id="kpi-projects">0 งาน</h3>
            </div>
        </div>
    </div>

    <div class="chart-grid-main">
        <div class="chart-card">
            <div class="chart-card-header">
                <h4 class="chart-card-title">กระแสเงินสด (รายได้ VS ต้นทุน)</h4>
                <p class="chart-card-desc">เปรียบเทียบเพื่อให้เห็นสภาพคล่องของร้านในแต่ละเดือน</p>
            </div>
            <div id="cashflowChart" class="chart-area"></div>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <h4 class="chart-card-title">สัดส่วนปัญหาหลังการติดตั้ง</h4>
                <p class="chart-card-desc">วิเคราะห์จุดอ่อนเพื่อลดต้นทุนการเข้าแก้ไขงาน</p>
            </div>
            <div id="issueChart" class="chart-area-donut"></div>
        </div>
    </div>

    <div class="chart-grid-half">
        <div class="chart-card">
            <div class="chart-card-header">
                <h4 class="chart-card-title">ผลิตภัณฑ์ที่ได้รับความนิยมสูงสุด (Top 5)</h4>
                <p class="chart-card-desc">ช่วยประกอบการตัดสินใจในการสต็อกวัสดุ</p>
            </div>
            <div id="topProductChart" class="chart-area"></div>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <h4 class="chart-card-title">เปรียบเทียบจำนวนวัสดุ(ประเมิน VS สั่งซื้อจริง)</h4>
                <p class="chart-card-desc">เปรียบเทียบจำนวนวัสดุที่ประเมินไว้ในใบเสนอราคากับที่สั่งซื้อจริง เพื่อตรวจสอบความแม่นยำในการคำนวณและควบคุมต้นทุนไม่ให้รั่วไหล</p>
            </div>
            <div id="materialCompareChart" class="chart-area"></div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-top">
            <div>
                <h4 class="chart-card-title">ความหนาแน่นของลูกค้าแบ่งตามพื้นที่ จังหวัดอุบลราชธานี</h4>
                <p class="chart-card-desc">วิเคราะห์เพื่อวางแผนเส้นทางจัดส่งและการทำโปรโมชั่นในพื้นที่</p>
            </div>
            <select id="amphureFilter" class="amphure-select">
                <option value="">ทุกอำเภอ</option>
            </select>
        </div>
        <div id="areaChart" class="chart-area-lg"></div>
    </div>

</div>

<script>
    const thaiMonths = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
    let charts = {};

    document.addEventListener("DOMContentLoaded", async () => {
        await loadKPIs();
        renderCashflowChart();
        renderIssueChart();
        renderTopProductChart();
        renderMaterialCompareChart();
        await loadAmphures();
        renderAreaChart();

        document.getElementById('timeFilter').addEventListener('change', (e) => {
            renderCashflowChart(e.target.value);
        });

        document.getElementById('amphureFilter').addEventListener('change', function () {
            renderAreaChart(this.value);
        });
    });

    async function loadKPIs() {
        try {
            const res = await fetch('/chart/summary');
            const data = await res.json();
            const formatMoney = (amount) => new Intl.NumberFormat('th-TH').format(amount);
            const revenue = parseFloat(data.revenue) || 0;
            const profit = parseFloat(data.profit) || 0;
            const cost = revenue - profit;
            document.getElementById('kpi-revenue').innerText = '฿' + formatMoney(revenue);
            document.getElementById('kpi-cost').innerText = '฿' + formatMoney(cost);
            document.getElementById('kpi-profit').innerText = '฿' + formatMoney(profit);
            document.getElementById('kpi-projects').innerText = (data.projects || 0) + ' งาน';
        } catch (error) {
            console.error("Error loading KPIs:", error);
        }
    }

    async function renderCashflowChart(type = 'month') {
        try {
            const [revRes, costRes] = await Promise.all([
                fetch(`/admin/api/revenue?type=${type}`),
                fetch('/admin/api/cost')
            ]);
            const revData = await revRes.json();
            const costData = await costRes.json();

            let labels = [], revenues = [], costs = [];

            if (type === 'month') {
                for (let i = 1; i <= 12; i++) {
                    labels.push(thaiMonths[i]);
                    const r = revData.find(item => parseInt(item.label) === i);
                    const c = costData.find(item => parseInt(item.month) === i);
                    revenues.push(r ? parseFloat(r.total) : 0);
                    costs.push(c ? parseFloat(c.total) : 0);
                }
            } else {
                labels = revData.map(item => item.label);
                revenues = revData.map(item => parseFloat(item.total));
                costs = costData.map(item => parseFloat(item.total));
            }

            const options = {
                series: [
                    { name: 'รายได้', data: revenues },
                    { name: 'ต้นทุน', data: costs }
                ],
                chart: { type: 'area', height: 350, toolbar: { show: false }, fontFamily: 'inherit' },
                colors: ['#334E68', '#D4B483'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: { categories: labels },
                yaxis: { labels: { formatter: (val) => "฿" + val.toLocaleString() } },
                tooltip: { y: { formatter: (val) => "฿" + val.toLocaleString() } }
            };

            if (charts.cashflow) charts.cashflow.destroy();
            charts.cashflow = new ApexCharts(document.querySelector("#cashflowChart"), options);
            charts.cashflow.render();
        } catch (error) {
            console.error("Error loading cashflow chart:", error);
        }
    }

    async function renderIssueChart() {
        try {
            const res = await fetch('/admin/api/issies');
            const data = await res.json();

            const labels = data.map(item => {
                if (item.category === 'material_problems') return 'ปัญหาวัสดุ';
                if (item.category === 'general_problems') return 'ปัญหาทั่วไป';
                return item.category;
            });
            const totals = data.map(item => parseInt(item.total));

            const options = {
                series: totals.length ? totals : [1],
                labels: labels.length ? labels : ['ไม่มีข้อมูล'],
                chart: { type: 'donut', height: 350, fontFamily: 'inherit' },
                colors: ['#334E68', '#D4B483', '#EF4444', '#6B7280', '#14B8A6'],
                plotOptions: {
                    pie: { donut: { size: '70%', labels: { show: true, name: { show: true }, value: { show: true, formatter: (val) => val + " เคส" }, total: { show: true, label: 'ปัญหาทั้งหมด' } } } }
                },
                dataLabels: { enabled: false },
                legend: { position: 'bottom' }
            };

            if (charts.issue) charts.issue.destroy();
            charts.issue = new ApexCharts(document.querySelector("#issueChart"), options);
            charts.issue.render();
        } catch (error) {
            console.error("Error loading issue chart:", error);
        }
    }

    async function renderTopProductChart() {
        try {
            const res = await fetch('/admin/api/topproduct');
            const data = await res.json();

            const labels = data.map(item => item.name);
            const totals = data.map(item => parseInt(item.total));

            const options = {
                series: [{ name: 'จำนวนงาน (ชุด)', data: totals }],
                chart: { type: 'bar', height: 350, toolbar: { show: false }, fontFamily: 'inherit' },
                plotOptions: { bar: { borderRadius: 4, horizontal: true, distributed: true, barHeight: '60%' } },
                colors: ['#334E68', '#D4B483', '#0369A1', '#075985', '#082F49'],
                dataLabels: { enabled: true, textAnchor: 'start', style: { colors: ['#fff'] }, formatter: function (val, opt) { return opt.w.globals.labels[opt.dataPointIndex] + ": " + val }, offsetX: 0, dropShadow: { enable: true } },
                xaxis: { categories: labels },
                yaxis: { show: false },
                tooltip: { theme: 'light' },
                legend: { show: false }
            };

            if (charts.topProduct) charts.topProduct.destroy();
            charts.topProduct = new ApexCharts(document.querySelector("#topProductChart"), options);
            charts.topProduct.render();
        } catch (error) {
            console.error("Error loading top product chart:", error);
        }
    }

    async function renderMaterialCompareChart() {
        try {
            const res = await fetch('/admin/api/materialcompare');
            const data = await res.json();

            const options = {
                series: [
                    { name: 'จำนวนที่วางแผน (ชิ้น)', data: [data.planned || 0] },
                    { name: 'จำนวนที่ซื้อจริง (ชิ้น)', data: [data.actual || 0] }
                ],
                chart: { type: 'bar', height: 350, toolbar: { show: false }, fontFamily: 'inherit' },
                plotOptions: { bar: { horizontal: false, columnWidth: '50%', borderRadius: 4, dataLabels: { position: 'top' } } },
                colors: ['#D4B483', '#334E68'],
                dataLabels: { enabled: true, offsetY: -20, style: { fontSize: '12px', colors: ["#304758"] } },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                xaxis: { categories: ['วัสดุภาพรวม (ชิ้น)'] },
                yaxis: { title: { text: 'จำนวนหน่วย' } },
                fill: { opacity: 1 },
                tooltip: { y: { formatter: function (val) { return val + " หน่วย" } } }
            };

            if (charts.materialCompare) charts.materialCompare.destroy();
            charts.materialCompare = new ApexCharts(document.querySelector("#materialCompareChart"), options);
            charts.materialCompare.render();
        } catch (error) {
            console.error("Error loading material compare chart:", error);
        }
    }

    async function loadAmphures() {
        try {
            const res = await fetch('/chart/amphures');
            const data = await res.json();
            const select = document.getElementById('amphureFilter');
            select.innerHTML = '<option value=""> ทุกอำเภอ </option>';
            data.forEach(a => {
                const opt = document.createElement('option');
                opt.value = a.id;
                opt.textContent = a.name;
                select.appendChild(opt);
            });
        } catch (error) {
            console.error("Error loading amphures:", error);
        }
    }

    async function renderAreaChart(amphureId = '') {
        try {
            let url = '/chart/area';
            if (amphureId) url += '?amphure_id=' + amphureId;

            const res = await fetch(url);
            const data = await res.json();

            const labels = data.map(d => d.name);
            const totals = data.map(d => parseInt(d.total));

            const options = {
                series: [{ name: 'จำนวนงานติดตั้ง', data: totals }],
                chart: { type: 'bar', height: 400, toolbar: { show: false }, fontFamily: 'inherit' },
                colors: ['#D4B483'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: '40%', distributed: true } },
                dataLabels: { enabled: false },
                legend: { show: false },
                xaxis: { categories: labels, labels: { style: { fontSize: '12px' } } },
                yaxis: { title: { text: 'จำนวน (งาน)' } }
            };

            if (charts.area) charts.area.destroy();
            charts.area = new ApexCharts(document.querySelector("#areaChart"), options);
            charts.area.render();
        } catch (error) {
            console.error("Error loading area chart:", error);
        }
    }
</script>
@endsection