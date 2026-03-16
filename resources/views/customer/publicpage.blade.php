@extends('layouts.customer')

@section('content')

<div class="blog-header text-center py-4 mb-0">
    <img src="/images/logo/favicon.ico" alt="Logo" width="56" height="56" class="rounded-circle logo-circle mb-2">
    <h1 class="blog-title">ทรายคำวัสดุ</h1>
    <p class="blog-subtitle">รับติดตั้งอลูมิเนียม กระจก ประตู หน้าต่าง</p>
</div>

<hr class="blog-divider mt-0">

<div class="row g-4 mt-2">
    <div class="col-lg-8">

        <div class="card-plain mb-4">
            <h5 class="section-heading">เกี่ยวกับร้าน</h5>
            <p>ทรายคำวัสดุ ให้บริการรับติดตั้งอลูมิเนียม กระจก ประตูและหน้าต่างทุกประเภท ด้วยประสบการณ์กว่า 20 ปี มุ่งเน้นงานคุณภาพและบริการที่ตรงต่อเวลา</p>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <div class="info-card text-center">
                    <div class="info-label">ประสบการณ์</div>
                    <div class="info-value">20 ปี</div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="info-card text-center">
                    <div class="info-label">การทำงาน</div>
                    <div class="info-value">ผ่านการทำงานมามากมาย</div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="info-card text-center">
                    <div class="info-label">บริการ</div>
                    <div class="info-value">ระดับ 5 ดาว</div>
                </div>
            </div>
        </div>

        <div class="card-plain">
            <h5 class="section-heading">บริการของเรา</h5>
            <ul class="service-list">
                <li>ติดตั้งหน้าต่างอลูมิเนียมทุกแบบ</li>
                <li>ติดตั้งประตูกระจก บานเลื่อน บานพับ</li>
                <li>ติดตั้งกระจกนิรภัย กระจกเทมเปอร์</li>
            </ul>
        </div>

    </div>

    <div class="col-lg-4">
        <div class="sidebar-widget mb-3">
            <div class="sidebar-title">เมนู</div>
            <ul class="sidebar-list">
                <li class="sidebar-list-item">
                    <a href="{{ route('home') }}" class="sidebar-link">หน้าหลัก</a>
                </li>
                <li class="sidebar-list-item">
                    <a href="" class="sidebar-link">ผลิตภัณฑ์ทั้งหมด</a>
                </li>
                <li class="sidebar-list-item">
                    <a href="{{ route('customer.cakestatuspage') }}" class="sidebar-link">เช็คสถานะการติดตั้ง</a>
                </li>
            </ul>
        </div>

        <div class="sidebar-widget">
            <div class="sidebar-title">ติดต่อเรา</div>
            <ul class="sidebar-contact">
                <li><i class="fas fa-phone me-2 icon-gold"></i> 089-528-4181</li>
            </ul>
        </div>
    </div>
</div>

@endsection