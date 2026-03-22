<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รีเซ็ตรหัสผ่าน</title>
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
            <h2 style="margin-top: 20px; margin-bottom: 10px;">รีเซ็ตรหัสผ่าน</h2>
            <p class="subtitle">กรอกรหัสผ่านใหม่ของคุณ</p>
        </div>

        @if ($errors->any())
            <div class="validation-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label for="email">อีเมล</label>
                <div class="input-container">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="กรอกอีเมลของคุณ"
                    />
                </div>
            </div>

            <div class="form-group">
                <label for="password">รหัสผ่านใหม่</label>
                <div class="input-container">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="กรอกรหัสผ่านใหม่"
                    />
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">ยืนยันรหัสผ่านใหม่</label>
                <div class="input-container">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="กรอกรหัสผ่านอีกครั้ง"
                    />
                </div>
            </div>

            <div class="form-footer" style="flex-direction: column; gap: 12px;">
                <button type="submit" class="login-button">
                    รีเซ็ตรหัสผ่าน
                </button>
                <a href="{{ route('login') }}" style="text-align:center; font-size:0.85rem; color:#666; text-decoration:none;">
                    ← กลับไปหน้าเข้าสู่ระบบ
                </a>
            </div>
        </form>
    </div>
</body>
</html>