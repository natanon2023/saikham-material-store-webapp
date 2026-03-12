<nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-image: linear-gradient(to right ,#ffff);">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="/images/logo/favicon.ico" alt="Logo" width="50" height="auto" class="d-inline-block align-text-top">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link action" aria-current="page" href="{{ route('home') }}">หน้าหลัก</a>
        </li>
        <li class="nav-item">
          <a class="nav-link action" href="{{ route('customer.cakestatuspage') }}">เช็คสถานะการติดตั้ง</a>
        </li>
        
      </ul>
    </div>
  </div>
</nav>