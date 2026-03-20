<div class="sidebar" id="sidebar">

    <a href="{{ route('admin.dashboard') }}" class="sidebar-header">
        <div class="logo">
            <img src="/images/logo/favicon.ico" alt="ทรายคำวัสดุ Logo" width="40" height="40" />
        </div>
        <span class="sidebar-title" style="margin-top: 5px;">ทรายคำวัสดุ  <p style="font-size: small; margin-top: 3px;">{{'ผู้ใช้งาน: '.Auth::user()->name.' '.Auth::user()->last_name ?? 'แอดมิน' }}</p></span>
    </a>
            

    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                <i class="fa fa-dashboard nav-icon"></i>
                <span class="nav-text">แดชบอร์ด</span>
                <div class="tooltip">แดชบอร์ด</div>
            </a>
        </li>
        
        <li class="nav-item has-submenu">
            <a href="javascript:void(0)" class="nav-link" onclick="toggleSubmenu(event)">
                <i class="fa-solid fa-list-check nav-icon" aria-hidden="true"></i>
                <span class="nav-text">งาน</span>
                <i class="fas fa-chevron-down submenu-toggle-icon"></i>
                <div class="tooltip">จัดการงาน</div>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.projects.adminfulleventcalendarpage') }}" class="nav-link">ปฎิทินงานทั้งหมด</a></li>
                <li><a href="{{ route('admin.projects.manageproblemsindex') }}" class="nav-link">จัดการปัญหา</a></li>
                <li><a href="{{ route('admin.projects.managewithdrawals') }}" class="nav-link">ประวัติการเบิกและคืนวัสดุ</a></li>
            </ul>
        </li>


        <li class="nav-item has-submenu">
            <a href="javascript:void(0)" class="nav-link" onclick="toggleSubmenu(event)">
                <i class="fa fa-cubes  nav-icon" aria-hidden="true"></i>
                <span class="nav-text">วัสดุและอุปกรณ์</span>
                <i class="fas fa-chevron-down submenu-toggle-icon"></i>
                <div class="tooltip">จัดการวัสดุ</div>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.materials.index') }}" class="nav-link">วัสดุและอุปกรณ์ทั้งหมด</a></li>
                <li><a href="{{ route('admin.materials.showselecttypematerials') }}" class="nav-link">เพิ่มวัสดุและอุปกรณ์ใหม่</a></li>
                <li><a href="{{ route('admin.projects.productsetdetail') }}" class="nav-link">ผลิตภัณฑ์</a></li>
                <li><a href="{{ route('admin.materials.trash') }}" class="nav-link">กู้คืนข้อมูล</a></li>
            </ul>
        </li>

        
        <li class="nav-item has-submenu">
            <a href="javascript:void(0)" class="nav-link" onclick="toggleSubmenu(event)">
                <i class="fa-solid fa-boxes-stacked nav-icon" aria-hidden="true"></i>
                <span class="nav-text">สต็อก</span>
                <i class="fas fa-chevron-down submenu-toggle-icon"></i>
                <div class="tooltip">ประวัติสต็อก</div>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.materials.addstockpage') }}" class="nav-link">เพิ่มสต็อก</a></li>
                <li><a href="{{ route('admin.materials.historystock') }}" class="nav-link">ประวัติสต็อก</a></li>
            </ul>
        </li>

        <li class="nav-item has-submenu">
            <a href="javascript:void(0)" class="nav-link" onclick="toggleSubmenu(event)">
                <i class="fa-regular fa-user nav-icon" aria-hidden="true"></i>
                <span class="nav-text">จัดการข้อมูลผู้ใช้งาน</span>
                <i class="fas fa-chevron-down submenu-toggle-icon"></i>
                <div class="tooltip"></div>
            </a>
            <ul class="submenu">
                <li><a href="{{Route('admin.users.index')  }}" class="nav-link">ข้อมูลผู้ใช้งานทั้งหมด</a></li>
                <li><a href="{{ Route('admin.users.create')  }}" class="nav-link">เพิ่มข้อมูลผู้ใช้งานใหม่</a></li>
            </ul>
        </li>




    </ul>

    <div class="user-section">
        <div class="user-dropdown">
            <a href="#" class="user-link" onclick="toggleDropdown(event)">
                <i class="fa-solid fa-right-from-bracket nav-icon"></i>
                <span class="user-name">ออกจากระบบ</span>
            </a>
            <div class="dropdown-menu" id="userDropdown">
                <a href="" class="dropdown-item2">โปรไฟล์</a>
                <hr class="dropdown-divider">
                <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="margin: 0;">
                    @csrf
                    <a href="javascript:void(0)" class="dropdown-item1 logout-item" onclick="handleLogout(event)">
                        <span>ออกจากระบบ</span>
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="logout-modal-overlay" id="logoutModal">
    <div class="logout-modal">
        <div class="logout-modal-header">
            <div class="logout-modal-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
            </div>
            <h3 class="logout-modal-title">ยืนยันการออกจากระบบ</h3>
        </div>
        <div class="logout-modal-body">
            <p class="logout-modal-message">คุณแน่ใจหรือไม่ที่จะออกจากระบบ?</p>
            <p class="logout-modal-submessage">การออกจากระบบจะทำให้คุณต้องเข้าสู่ระบบใหม่อีกครั้ง</p>
        </div>
        <div class="logout-modal-footer">
            <button type="button" class="logout-modal-cancel" onclick="cancelLogout()">ยกเลิก</button>
            <button type="button" class="logout-modal-confirm" onclick="confirmLogout()">
                <span class="confirm-text">ออกจากระบบ</span>
                <span class="loading-spinner" style="display: none;">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z">
                            <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12"
                                dur="1s" repeatCount="indefinite" />
                        </path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>

<script>
    function toggleDropdown(event) {
        event.preventDefault();
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('show');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        const userLink = document.querySelector('.user-link');
        if (!userLink.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });

    function toggleSubmenu(event) {
        event.preventDefault();
        const parent = event.currentTarget.closest('.has-submenu');
        parent.classList.toggle('open');
    }

    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (!href || href.trim() === '#' || href.trim() === '') {
                e.preventDefault();
            }
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    function handleResize() {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('collapsed');
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize();

    function handleLogout(event) {
        event.preventDefault();
        event.stopPropagation();
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) dropdown.classList.remove('show');
        setTimeout(() => showLogoutModal(), 200);
    }

    function showLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function cancelLogout() {
        const modal = document.getElementById('logoutModal');
        if (modal) {
            modal.classList.add('hiding');
            setTimeout(() => {
                modal.classList.remove('show', 'hiding');
                document.body.style.overflow = '';
            }, 300);
        }
    }

    function confirmLogout() {
        const confirmBtn = document.querySelector('.logout-modal-confirm');
        const confirmText = confirmBtn.querySelector('.confirm-text');
        const loadingSpinner = confirmBtn.querySelector('.loading-spinner');

        confirmText.style.display = 'none';
        loadingSpinner.style.display = 'flex';
        confirmBtn.disabled = true;
        confirmBtn.style.cursor = 'not-allowed';

        setTimeout(() => {
            const form = document.getElementById('logoutForm');
            if (form) {
                form.submit();
            } else {
                alert('เกิดข้อผิดพลาดในการออกจากระบบ กรุณาลองใหม่อีกครั้ง');
                confirmText.style.display = 'inline';
                loadingSpinner.style.display = 'none';
                confirmBtn.disabled = false;
                confirmBtn.style.cursor = 'pointer';
            }
        }, 500);
    }

    document.addEventListener('click', function(event) {
        const modal = document.getElementById('logoutModal');
        if (event.target === modal) cancelLogout();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('logoutModal');
            if (modal && modal.classList.contains('show')) {
                cancelLogout();
            }
        }
    });

    function setActiveNavLink() {
        const currentUrl = window.location.href;
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (currentUrl.startsWith(link.href)) {
                link.classList.add('active');


                const submenu = link.closest('.submenu');
                if (submenu) {
                    submenu.closest('.has-submenu').classList.add('open');
                }
            }
        });
    }

    window.addEventListener('DOMContentLoaded', setActiveNavLink);


    function setActiveNavLink() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.classList.remove('active');
            const linkPath = new URL(link.href, window.location.origin).pathname;
            if (currentPath === linkPath) {
                link.classList.add('active');
                const submenu = link.closest('.submenu');
                if (submenu) {
                    submenu.closest('.has-submenu').classList.add('open');
                }
            }
        });
    }
</script>