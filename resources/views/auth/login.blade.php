<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Sistem Informasi Pelanggaran Siswa - SMP Negeri 7 Jember</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom CSS -->
    @vite(['resources/css/login.css'])
</head>
<body>
    <!-- Splash Screen / Opening Animation Container -->
    <div class="splash-screen" id="splashScreen">
        <div class="splash-content">
            <div class="splash-logo-wrapper">
                <img src="{{ asset('images/logo-smp.png') }}" alt="Logo SMP Negeri 7 Jember" class="splash-logo" id="splashLogo">
            </div>
            <h2 class="splash-title">SMP Negeri 7 Jember</h2>
            <p class="splash-subtitle">Sistem Informasi Pelanggaran Siswa</p>
            <div class="splash-loader">
                <div class="loader-bar"></div>
            </div>
        </div>
    </div>

    <!-- Main Login Container (Awalnya tersembunyi) -->
    <div class="login-container" id="loginContainer">
        <!-- Left Side - School Image & Info (Akan bergerak dari tengah ke kiri) -->
        <div class="login-left" id="loginLeft">
            <div class="school-overlay"></div>
            <div class="school-content">
                <div class="school-icon">
                    <img src="{{ asset('images/logo-smp.png') }}" alt="Logo SMPN 7 Jember" class="school-logo-img">
                </div>
                <h2>SMP Negeri 7 Jember</h2>
                <p>Sistem Informasi Pelanggaran Siswa</p>
                <div class="school-motto">
                    <i class="fas fa-quote-left"></i>
                    <span>Disiplin adalah jembatan antara tujuan dan prestasi</span>
                </div>
                <div class="school-stats">
                    <div class="stat">
                        <span class="stat-number">892</span>
                        <span class="stat-label">Siswa</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">42</span>
                        <span class="stat-label">Guru</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">15</span>
                        <span class="stat-label">Kelas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form (Akan fade in) -->
        <div class="login-right" id="loginRight">
            <div class="login-card">
                <div class="login-header">
                    <h1>Selamat Datang</h1>
                    <p>Silakan login untuk mengakses sistem</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="session-status">
                        <i class="fas fa-check-circle"></i> {{ session('status') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Login Type Selector (Email / NISN / NUPTK) -->
                    <div class="input-group">
                        <div class="login-type-selector">
                            <button type="button" class="type-btn active" data-type="email">
                                <i class="fas fa-envelope"></i> Email
                            </button>
                            <button type="button" class="type-btn" data-type="nisn">
                                <i class="fas fa-id-card"></i> NISN
                            </button>
                            <button type="button" class="type-btn" data-type="nuptk">
                                <i class="fas fa-chalkboard-user"></i> NUPTK
                            </button>
                        </div>
                        
                        <div class="input-field-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="login_identifier" name="email" class="modern-input" 
                                   placeholder="Email / NISN / NUPTK" value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email')
                            <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="input-group">
                        <div class="input-field-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" class="modern-input" 
                                   placeholder="Password" required autocomplete="current-password">
                            <button type="button" class="toggle-password" id="togglePassword">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Options Row -->
                    <div class="options-row">
                        <label class="checkbox-modern">
                            <input type="checkbox" name="remember" id="remember_me">
                            <span>Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-btn">
                        <span>Masuk</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <div class="secure-note">
                        <i class="fas fa-shield-alt"></i> Sistem aman & terintegrasi dengan BK
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom JavaScript -->
    @vite(['resources/js/login.js'])
</body>
</html>