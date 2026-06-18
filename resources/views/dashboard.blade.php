<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | Sistem Informasi Pelanggaran Siswa - SMP Negeri 7 Jember</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>
<body>
    <!-- Overlay untuk mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div class="brand-text">
                    <span>SMPN 7 Jember</span>
                    <small>Sistem Pelanggaran</small>
                </div>
            </div>
            
            <nav class="sidebar-nav">
    <a href="{{ route('dashboard') }}" class="nav-item active">
        <i class="fas fa-chart-pie"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('data-siswa') }}" class="nav-item">
        <i class="fas fa-user-graduate"></i>
        <span>Data Siswa</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Pelanggaran</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fas fa-chart-line"></i>
        <span>Laporan</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fas fa-users"></i>
        <span>Guru BK</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
</nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <small>{{ auth()->user()->jabatan ?? 'Guru BK' }}</small>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2>Dashboard</h2>
                </div>
                <div class="topbar-right">
                    <div class="notification">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari...">
                    </div>
                </div>
            </header>

            <!-- Stats Cards -->
            <section class="stats-section">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Siswa</h3>
                            <p class="stat-number" id="totalSiswa">0</p>
                            <span class="stat-change positive">
                                <i class="fas fa-arrow-up"></i> 12% dari bulan lalu
                            </span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Pelanggaran</h3>
                            <p class="stat-number" id="totalPelanggaran">0</p>
                            <span class="stat-change negative">
                                <i class="fas fa-arrow-down"></i> 8% dari bulan lalu
                            </span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Selesai Ditangani</h3>
                            <p class="stat-number" id="totalSelesai">0</p>
                            <span class="stat-change positive">
                                <i class="fas fa-arrow-up"></i> 15% dari bulan lalu
                            </span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Proses BK</h3>
                            <p class="stat-number" id="totalProses">0</p>
                            <span class="stat-change neutral">
                                <i class="fas fa-minus"></i> Stabil
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Charts Section -->
            <section class="charts-section">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3><i class="fas fa-chart-bar"></i> Statistik Pelanggaran per Kategori</h3>
                        <div class="chart-actions">
                            <button class="chart-btn active" data-period="week">Minggu</button>
                            <button class="chart-btn" data-period="month">Bulan</button>
                            <button class="chart-btn" data-period="year">Tahun</button>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <h3><i class="fas fa-chart-pie"></i> Distribusi Pelanggaran</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </section>

            <!-- Recent Violations -->
            <section class="recent-section">
                <div class="recent-header">
                    <h3><i class="fas fa-clock"></i> Pelanggaran Terbaru</h3>
                    <a href="#" class="view-all">Lihat Semua →</a>
                </div>
                <div class="recent-table-wrapper">
                    <table class="recent-table">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Pelanggaran</th>
                                <th>Kategori</th>
                                <th>Poin</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="recentTableBody">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>