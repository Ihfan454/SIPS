<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Siswa | Sistem Informasi Pelanggaran Siswa - SMP Negeri 7 Jember</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/data-siswa.css', 'resources/js/data-siswa.js'])
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
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('data-siswa') }}" class="nav-item active">
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
                    <h2>Data Siswa</h2>
                </div>
                <div class="topbar-right">
                    <div class="notification">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari siswa..." id="searchSiswa">
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
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Siswa Aktif</h3>
                            <p class="stat-number" id="siswaAktif">0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Siswa Nonaktif</h3>
                            <p class="stat-number" id="siswaNonaktif">0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-chalkboard"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Kelas</h3>
                            <p class="stat-number" id="totalKelas">0</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Filter & Actions -->
            <section class="filter-section">
                <div class="filter-left">
                    <div class="filter-group">
                        <label>Filter Kelas:</label>
                        <select id="filterKelas" class="filter-select">
                            <option value="all">Semua Kelas</option>
                            <option value="7A">7A</option>
                            <option value="7B">7B</option>
                            <option value="7C">7C</option>
                            <option value="8A">8A</option>
                            <option value="8B">8B</option>
                            <option value="8C">8C</option>
                            <option value="9A">9A</option>
                            <option value="9B">9B</option>
                            <option value="9C">9C</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Status:</label>
                        <select id="filterStatus" class="filter-select">
                            <option value="all">Semua</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="filter-right">
                    <button class="btn-add" id="btnTambahSiswa">
                        <i class="fas fa-plus"></i> Tambah Siswa
                    </button>
                    <button class="btn-export">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                </div>
            </section>

            <!-- Table -->
            <section class="table-section">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>No. HP</th>
                                <th>Status</th>
                                <th>Total Poin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="pagination">
                    <div class="pagination-info">
                        <span id="paginationInfo">Menampilkan 1-10 dari 0 data</span>
                    </div>
                    <div class="pagination-buttons">
                        <button class="page-btn" id="prevPage" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="pageNumbers"></span>
                        <button class="page-btn" id="nextPage">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal Tambah/Edit Siswa -->
    <div class="modal" id="modalSiswa">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Siswa</h3>
                <button class="modal-close" id="modalClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSiswa">
                    <input type="hidden" id="editId" value="">
                    <div class="form-group">
                        <label>NISN</label>
                        <input type="text" id="nisn" class="form-input" placeholder="Masukkan NISN" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" id="nama" class="form-input" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Kelas</label>
                            <select id="kelas" class="form-input" required>
                                <option value="">Pilih Kelas</option>
                                <option value="7A">7A</option>
                                <option value="7B">7B</option>
                                <option value="7C">7C</option>
                                <option value="8A">8A</option>
                                <option value="8B">8B</option>
                                <option value="8C">8C</option>
                                <option value="9A">9A</option>
                                <option value="9B">9B</option>
                                <option value="9C">9C</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select id="jenisKelamin" class="form-input" required>
                                <option value="">Pilih</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" id="noHp" class="form-input" placeholder="Masukkan nomor HP">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea id="alamat" class="form-input" rows="2" placeholder="Masukkan alamat"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="status" class="form-input">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Tidak Aktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" id="modalCancel">Batal</button>
                <button class="btn-save" id="modalSave">Simpan</button>
            </div>
        </div>
    </div>
</body>
</html>