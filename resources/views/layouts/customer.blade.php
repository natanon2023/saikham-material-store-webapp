<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทรายคำวัสดุ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:        #334E68;
            --navy-dark:   #243a50;
            --navy-light:  #4a6a8a;
            --gold:        #D4B483;
            --gold-dark:   #b8944f;
            --gold-light:  #eddfc2;
            --bg-page:     #f7f4ef;
            --bg-card:     #ffffff;
            --border:      #e5ddd0;
            --text-main:   #1e2d3d;
            --text-muted:  #7a8a99;
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--bg-page);
            font-family: 'Sarabun', 'Segoe UI', sans-serif;
            color: var(--text-main);
            font-size: 15px;
        }

        .navbar-custom {
            background-color: var(--navy);
            border-bottom: 3px solid var(--gold);
            padding: 0.55rem 0;
        }

        .navbar-custom .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-nav {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--gold);
            object-fit: cover;
        }

        .brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 0.3px;
        }

        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.82) !important;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.4rem 0.9rem !important;
            border-radius: 7px;
            transition: all 0.18s;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            background: rgba(212,180,131,0.18);
            color: var(--gold) !important;
        }

        .navbar-custom .navbar-toggler {
            border: none;
            padding: 4px 8px;
        }

        .navbar-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28212,180,131,1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .blog-header {
            padding: 2.5rem 0 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .logo-circle {
            border: 3px solid var(--gold);
            padding: 3px;
            background: white;
        }

        .blog-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--navy);
            letter-spacing: -0.5px;
            margin-bottom: 0.3rem;
        }

        .blog-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
            font-weight: 400;
        }

        .category-nav {
            border-bottom: 1px solid var(--border);
        }

        .cat-link {
            text-decoration: none;
            color: var(--navy);
            font-size: 0.88rem;
            font-weight: 600;
            padding: 0.35rem 0.9rem;
            border-radius: 20px;
            border: 1px solid transparent;
            transition: all 0.18s;
        }

        .cat-link:hover,
        .cat-link.active {
            background-color: var(--navy);
            color: var(--gold) !important;
            border-color: var(--navy);
        }

        .blog-divider {
            border-color: var(--border);
            opacity: 1;
        }

        .featured-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            background: var(--bg-card);
            transition: box-shadow 0.2s;
        }

        .featured-card:hover {
            box-shadow: 0 6px 24px rgba(51,78,104,0.13);
        }

        .featured-img {
            object-fit: cover;
            min-height: 280px;
            max-height: 340px;
        }

        .badge-category {
            display: inline-block;
            background: var(--gold-light);
            color: var(--navy-dark);
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }

        .product-card-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--navy);
            line-height: 1.35;
        }

        .spec-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .spec-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.855rem;
            padding: 5px 0;
            border-bottom: 1px dashed var(--border);
        }

        .spec-label {
            font-weight: 600;
            color: var(--navy);
            font-size: 0.82rem;
        }

        .spec-value {
            color: var(--text-muted);
            font-size: 0.82rem;
            text-align: right;
        }

        .product-detail {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 0;
        }

        .product-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            background: var(--bg-card);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(51,78,104,0.12);
        }

        .product-img-wrap {
            height: 190px;
            overflow: hidden;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        .product-card-title-sm {
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 0.5rem;
        }

        .card-divider {
            border-color: var(--border);
            opacity: 1;
            margin: 0.5rem 0;
        }

        .spec-list-sm {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .spec-sm {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-bottom: 2px;
            line-height: 1.5;
        }

        .spec-sm span {
            font-weight: 600;
            color: var(--navy-light);
        }

        .detail-text {
            border-top: 1px dashed var(--border);
            padding-top: 6px;
            margin-top: 4px;
            font-style: italic;
        }

        .btn-navy {
            background-color: var(--navy);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            padding: 0.45rem 1.1rem;
            transition: background 0.18s;
        }

        .btn-navy:hover {
            background-color: var(--navy-dark);
            color: white;
        }

        .btn-outline-navy {
            background: transparent;
            color: var(--navy);
            border: 1.5px solid var(--navy);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.4rem 1rem;
            transition: all 0.18s;
        }

        .btn-outline-navy:hover {
            background-color: var(--navy);
            color: white;
        }

        .btn-gold {
            background-color: var(--gold);
            color: var(--navy-dark);
            border: none;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            padding: 0.45rem 1.1rem;
            transition: background 0.18s;
        }

        .btn-gold:hover {
            background-color: var(--gold-dark);
            color: white;
        }

        .sidebar-widget {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 1.2rem 1.3rem;
        }

        .sidebar-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
            border-bottom: 2px solid var(--gold);
            padding-bottom: 0.5rem;
            margin-bottom: 0.9rem;
        }

        .sidebar-text {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        .sidebar-cta {
            background: var(--navy);
            border-color: var(--navy);
        }

        .sidebar-title-light {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gold);
            border-bottom: 2px solid rgba(212,180,131,0.4);
            padding-bottom: 0.5rem;
            margin-bottom: 0.9rem;
        }

        .sidebar-text-light {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.75);
            line-height: 1.6;
        }

        .sidebar-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-list-item {
            border-bottom: 1px dashed var(--border);
            padding: 7px 0;
        }

        .sidebar-list-item:last-child {
            border-bottom: none;
        }

        .sidebar-link {
            text-decoration: none;
            color: var(--navy);
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.15s;
        }

        .sidebar-link:hover {
            color: var(--gold-dark);
        }

        .sidebar-contact {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-contact li {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .icon-gold { color: var(--gold-dark); }

        .page-footer {
            margin-top: 3rem;
            padding: 1.5rem 0;
            text-align: center;
            font-size: 0.82rem;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
        }

        .page-footer a {
            color: var(--navy);
            text-decoration: none;
            font-weight: 600;
        }

        .page-title-bar {
            border-left: 4px solid var(--gold);
            padding-left: 0.75rem;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--navy);
            margin: 0;
        }

        .card-plain {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 1.25rem 1.4rem;
        }

        .section-heading {
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 0.75rem;
        }

        .info-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 1.1rem 0.75rem;
        }

        .info-icon {
            font-size: 1.4rem;
            color: var(--gold-dark);
            margin-bottom: 0.4rem;
        }

        .info-label {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .info-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
        }

        .service-list {
            padding-left: 1.1rem;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .service-list li {
            font-size: 0.9rem;
            color: var(--text-main);
            line-height: 1.5;
        }

        .project-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 1.1rem 1.25rem;
        }

        .project-id {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-bottom: 3px;
        }

        .project-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .status-badge {
            display: inline-block;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 2px 10px;
        }

        .status-green { background: #e6f4ea; color: #276749; }
        .status-red   { background: #fdecea; color: #a33030; }
        .status-blue  { background: #e8f0fc; color: #2a5ab5; }
        .status-gray  { background: #f0ede8; color: #5a6474; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="/images/logo/favicon.ico" alt="Logo" class="logo-nav">
                <span class="brand-text">ทรายคำวัสดุ</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navMain" aria-controls="navMain"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navMain">
                <ul class="navbar-nav gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            หน้าหลัก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            ผลิตภัณฑ์
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.cakestatuspage') ? 'active' : '' }}" href="{{ route('customer.cakestatuspage') }}">
                            เช็คสถานะ
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @yield('content')
    </main>

    <footer class="page-footer">
        &copy; {{ date('Y') }} <a href="#">ทรายคำวัสดุ</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
</body>
</html>