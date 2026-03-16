<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="#">
      <img src="/images/logo/favicon.ico" alt="Logo" width="44" height="44" class="rounded-circle border border-gold">
      <span class="brand-name">ทรายคำวัสดุ</span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon-custom">
        <span></span><span></span><span></span>
      </span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-lg-1">
        <li class="nav-item">
          <a class="nav-link nav-link-custom" aria-current="page" href="{{ route('home') }}">
            <i class="fas fa-home me-1"></i> หน้าหลัก
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="{{ route('customer.cakestatuspage') }}">
            <i class="fas fa-clipboard-check me-1"></i> เช็คสถานะการติดตั้ง
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>