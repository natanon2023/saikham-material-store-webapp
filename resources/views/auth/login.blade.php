<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="icon" type="image/x-icon"  sizes="64x64"  href="/favicon.ico">



</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <div class="logo">
                    <img src="/images/logo/favicon.ico" alt="ทรายคำวัสดุ Logo" width="100" height="100" />
            </div>
            <h1 class="title">ทรายคำวัสดุ</h1>
            <h2 style="margin-top: 20px; margin-bottom: 20px;">เข้าสู่ระบบ</h2>
            <p class="subtitle">ยินดีต้อนรับ กรุณาเข้าสู่ระบบ</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf


            @if ($errors->any())
                <div class="validation-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

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

            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <div class="input-container">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="กรุณากรอกรหัสผ่านของคุณ"
                    />
                </div>
            </div>

            <div class="checkbox-container">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <label for="remember" class="checkbox-label">
                    จดจำการเข้าสู่ระบบ
                </label>
            </div>

            <div class="form-footer">
                @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        ลืมรหัสผ่านหรือไม่?
                    </a>
                @endif

                <button type="submit" class="login-button" onclick="this.classList.add('loading')">
                    เข้าสู่ระบบ
                </button>
            </div>
        </form>
    </div>


</body>
</html>
