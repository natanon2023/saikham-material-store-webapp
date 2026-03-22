<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="icon" type="image/x-icon" sizes="64x64" href="/favicon.ico">
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <div class="logo">
                <img src="/images/logo/favicon.ico" alt="ทรายคำวัสดุ Logo" width="100" height="100" />
            </div>
            <h1 class="title">ทรายคำวัสดุ</h1>
            <h2 style="margin-top: 20px; margin-bottom: 10px;">ลืมรหัสผ่าน</h2>
            <p class="subtitle">กรอกอีเมลเพื่อรับลิงก์รีเซ็ตรหัสผ่าน</p>
        </div>

        @if (session('status'))
            <div class="status-message" style="background:#d1fae5; color:#065f46; padding:12px 16px; border-radius:6px; margin-bottom:16px; font-size:0.9rem;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="validation-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">อีเมล</label>
                <div class="input-container">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="กรุณากรอกอีเมลของคุณ"
                    />
                </div>
            </div>

            <div class="form-footer" style="flex-direction: column; gap: 12px; justify-content: space-between;">
                <button type="submit" class="login-button">
                    ส่งลิงก์รีเซ็ตรหัสผ่าน
                </button>
                <a href="{{ route('login') }}" style="text-align:center; font-size:0.85rem; color:#666; text-decoration:none;">
                    กลับไปหน้าเข้าสู่ระบบ
                </a>
            </div>
        </form>
    </div>
</body>
</html>