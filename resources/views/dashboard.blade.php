<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Sistem Informasi Pelanggaran Siswa | SMP Negeri 7 Jember</title>
    <!-- Google Fonts: Poppins untuk tampilan modern dan bersih -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 (ikon lengkap) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Chart.js untuk grafik statistik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f7ff;
            color: #1e293b;
            line-height: 1.5;
        }

        /* navbar sekolah */
        .navbar {
            background: linear-gradient(135deg, #0f2b3d 0%, #1b4a6e 100%);
            padding: 1rem 2rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
        }

        .logo-area i {
            font-size: 2rem;
            filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.2));
        }

        .logo-area h1 {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .logo-area span {
            font-size: 0.85rem;
            font-weight: 400;
            opacity: 0.9;
            display: block;
        }

        .nav-stats {
            display: flex;
            gap: 1.2rem;
            background: rgba(255,255,255,0.12);
            padding: 0.5rem 1.2rem;
            border-radius: 60px;
            backdrop-filter: blur(4px);
        }

        .nav-stats .stat-badge {
            color: white;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* main container */
        .dashboard-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* kartu statistik */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.2rem 1.5rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.03), 0 4px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -12px rgba(0,0,0,0.1);
        }

        .stat-info h3 {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5b6e8c;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            margin-top: 8px;
            color: #0f2b3d;
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.7;
            color: #2c6e9e;
        }

        /* tabel dan grafik */
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 1.8rem;
            margin-bottom: 2rem;
        }

        .card-panel {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .panel-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 5px solid #1b4a6e;
            padding-left: 1rem;
        }

        .pelanggaran-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pelanggaran-table th {
            text-align: left;
            padding: 0.9rem 0.5rem 0.7rem 0;
            font-weight: 600;
            color: #334155;
            border-bottom: 2px solid #e2e8f0;
        }

        .pelanggaran-table td {
            padding: 0.75rem 0.5rem 0.75rem 0;
            border-bottom: 1px solid #f0f2f5;
            font-weight: 500;
        }

        .badge-ringan {
            background: #d9f99d;
            color: #3a6210;
            padding: 0.2rem 0.8rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-block;
        }
        .badge-sedang {
            background: #fed7aa;
            color: #9b4a0b;
        }
        .badge-berat {
            background: #fecaca;
            color: #b91c1c;
        }
        .badge {
            border-radius: 30px;
            padding: 0.2rem 0.8rem;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-block;
        }

        .chart-container {
            margin-top: 10px;
            height: 240px;
        }

        /* daftar pelanggaran terbaru */
        .recent-list {
            margin-top: 0.5rem;
        }

        .recent-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid #edf2f7;
        }

        .recent-info strong {
            display: block;
            font-size: 0.9rem;
        }

        .recent-info small {
            color: #5b6e8c;
            font-size: 0.7rem;
        }

        .recent-point {
            font-weight: 800;
            background: #f1f5f9;
            padding: 0.2rem 0.8rem;
            border-radius: 40px;
        }

        .footer {
            text-align: center;
            padding: 2rem;
            color: #5b6e8c;
            font-size: 0.8rem;
            border-top: 1px solid #e2e8f0;
            margin-top: 2rem;
        }

        @media (max-width: 900px) {
            .two-columns {
                grid-template-columns: 1fr;
            }
            .nav-container {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        button, .btn-filter {
            background: #eef2ff;
            border: none;
            padding: 0.3rem 1rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.1s;
        }
        .filter-bar {
            margin-bottom: 1rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="nav-container">
        <div class="logo-area">
            <i class="fas fa-school"></i>
            <div>
                <h1>SMP Negeri 7 Jember</h1>
                <span>Sistem Informasi Pelanggaran Siswa</span>
            </div>
        </div>
        <div class="nav-stats">
            <div class="stat-badge"><i class="fas fa-calendar-alt"></i> Tahun Ajaran 2024/2025</div>
            <div class="stat-badge"><i class="fas fa-chalkboard-user"></i> Tertib & Berkarakter</div>
        </div>
    </div>
</div>

<div class="dashboard-container">
    <!-- Dynamic stats via JS -->
    <div class="stats-grid" id="statsGrid"></div>

    <div class="two-columns">
        <!-- Tabel jenis pelanggaran & kategori -->
        <div class="card-panel">
            <div class="panel-title">
                <i class="fas fa-clipboard-list"></i> Rekapitulasi Pelanggaran
            </div>
            <div class="filter-bar">
                <button class="btn-filter active-filter" data-filter="all">Semua</button>
                <button class="btn-filter" data-filter="Ringan">Ringan</button>
                <button class="btn-filter" data-filter="Sedang">Sedang</button>
                <button class="btn-filter" data-filter="Berat">Berat</button>
            </div>
            <div style="overflow-x: auto;">
                <table class="pelanggaran-table" id="pelanggaranTable">
                    <thead>
                        <tr><th>Jenis Pelanggaran</th><th>Kategori</th><th>Poin</th><th>Frekuensi</th></tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
            <div class="mt-2" style="margin-top: 0.8rem; font-size:0.7rem; color:#5b6e8c;">
                <i class="fas fa-chart-line"></i> Data berdasarkan BK semester ganjil
            </div>
        </div>

        <!-- Grafik statistik + ringkasan -->
        <div class="card-panel">
            <div class="panel-title">
                <i class="fas fa-chart-pie"></i> Statistik Pelanggaran
            </div>
            <div class="chart-container">
                <canvas id="violationChart" style="max-height: 220px; width:100%"></canvas>
            </div>
            <div style="margin-top: 1rem; background:#f8fafc; border-radius:1rem; padding:0.8rem;">
                <div style="display:flex; justify-content:space-between;">
                    <span><i class="fas fa-user-graduate"></i> Total tercatat:</span>
                    <strong id="totalViolationCount">0</strong>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:6px;">
                    <span><i class="fas fa-exclamation-triangle"></i> Poin akumulasi:</span>
                    <strong id="totalPointsSum">0</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar pelanggaran siswa terkini (simulasi data nama siswa) -->
    <div class="card-panel" style="margin-top: 0.5rem;">
        <div class="panel-title">
            <i class="fas fa-users"></i> Pelanggaran Siswa Terbaru
        </div>
        <div id="recentViolationsList" class="recent-list"></div>
    </div>
</div>

<div class="footer">
    <i class="fas fa-database"></i> Sistem Informasi Pelanggaran Siswa | SMP Negeri 7 Jember | Bimbingan Konseling Terintegrasi
</div>

<script>
    // ------ DATA PELANGGARAN (Sesuai tema SMP Negeri 7 Jember) ------
    const violationData = [
        { nama: "Terlambat masuk sekolah", kategori: "Ringan", poin: 5, frekuensi: 32 },
        { nama: "Seragam tidak rapi / tidak sesuai", kategori: "Ringan", poin: 5, frekuensi: 28 },
        { nama: "Membuang sampah sembarangan", kategori: "Ringan", poin: 5, frekuensi: 18 },
        { nama: "Keluar kelas saat jam pelajaran", kategori: "Sedang", poin: 10, frekuensi: 14 },
        { nama: "Berbicara tidak sopan kepada guru", kategori: "Sedang", poin: 15, frekuensi: 9 },
        { nama: "Menggunakan HP tanpa izin", kategori: "Sedang", poin: 10, frekuensi: 22 },
        { nama: "Bolos / tidak masuk tanpa keterangan", kategori: "Berat", poin: 25, frekuensi: 7 },
        { nama: "Berkelahi dengan teman", kategori: "Berat", poin: 30, frekuensi: 4 },
        { nama: "Vandalisme / merusak fasilitas", kategori: "Berat", poin: 35, frekuensi: 3 },
        { nama: "Bullying / perundungan", kategori: "Berat", poin: 40, frekuensi: 2 }
    ];

    // Data nama siswa (simulasi untuk riwayat terbaru)
    const siswaList = [
        "Ahmad Raffi", "Bunga Citra", "Cahya Dewi", "Dimas Prasetyo", "Elok Faiqoh",
        "Farhan Kurniawan", "Gita Puspita", "Hafidz Ramadhan", "Indah Nurhaliza", "Joko Santoso"
    ];

    // generate riwayat acak (20 data terbaru)
    function generateRecentViolations() {
        let recent = [];
        const jenisViolasi = violationData.map(v => v.nama);
        for (let i = 1; i <= 18; i++) {
            const randomViolation = jenisViolasi[Math.floor(Math.random() * jenisViolasi.length)];
            const vData = violationData.find(v => v.nama === randomViolation);
            const randomSiswa = siswaList[Math.floor(Math.random() * siswaList.length)];
            const tanggal = new Date();
            tanggal.setDate(tanggal.getDate() - Math.floor(Math.random() * 30));
            const formattedDate = `${tanggal.getDate()}/${tanggal.getMonth()+1}/${tanggal.getFullYear()}`;
            recent.push({
                siswa: randomSiswa,
                pelanggaran: randomViolation,
                kategori: vData.kategori,
                poin: vData.poin,
                tanggal: formattedDate
            });
        }
        // sort by tanggal desc (newest first)
        recent.sort((a,b) => new Date(b.tanggal.split('/').reverse().join('-')) - new Date(a.tanggal.split('/').reverse().join('-')));
        return recent;
    }

    let currentFilter = "all";
    let chartInstance = null;

    // Render tabel pelanggaran berdasarkan filter
    function renderTable() {
        const filtered = currentFilter === "all" ? violationData : violationData.filter(v => v.kategori === currentFilter);
        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";
        filtered.forEach(item => {
            const row = tbody.insertRow();
            row.insertCell(0).innerHTML = `<strong>${item.nama}</strong>`;
            let badgeClass = "";
            if(item.kategori === "Ringan") badgeClass = "badge-ringan";
            else if(item.kategori === "Sedang") badgeClass = "badge-sedang";
            else badgeClass = "badge-berat";
            row.insertCell(1).innerHTML = `<span class="badge ${badgeClass}">${item.kategori}</span>`;
            row.insertCell(2).innerHTML = `${item.poin} poin`;
            row.insertCell(3).innerHTML = `${item.frekuensi} x`;
        });
        // update total keseluruhan frekuensi & poin
        const totalFreq = violationData.reduce((sum, v) => sum + v.frekuensi, 0);
        const totalPoints = violationData.reduce((sum, v) => sum + (v.poin * v.frekuensi), 0);
        document.getElementById("totalViolationCount").innerText = totalFreq + " kejadian";
        document.getElementById("totalPointsSum").innerText = totalPoints + " poin";
        
        // update card stats (ringkasan);
        updateStatsCards();
    }

    function updateStatsCards() {
        const totalJenisPelanggaran = violationData.length;
        const totalRingan = violationData.filter(v => v.kategori === "Ringan").reduce((s,v) => s + v.frekuensi,0);
        const totalSedang = violationData.filter(v => v.kategori === "Sedang").reduce((s,v) => s + v.frekuensi,0);
        const totalBerat = violationData.filter(v => v.kategori === "Berat").reduce((s,v) => s + v.frekuensi,0);
        const totalSemua = totalRingan + totalSedang + totalBerat;
        
        const statsGrid = document.getElementById("statsGrid");
        statsGrid.innerHTML = `
            <div class="stat-card"><div class="stat-info"><h3>Total Pelanggaran</h3><div class="stat-number">${totalSemua}</div></div><div class="stat-icon"><i class="fas fa-chart-simple"></i></div></div>
            <div class="stat-card"><div class="stat-info"><h3>Kategori Ringan</h3><div class="stat-number">${totalRingan}</div></div><div class="stat-icon"><i class="fas fa-leaf"></i></div></div>
            <div class="stat-card"><div class="stat-info"><h3>Kategori Sedang</h3><div class="stat-number">${totalSedang}</div></div><div class="stat-icon"><i class="fas fa-chart-line"></i></div></div>
            <div class="stat-card"><div class="stat-info"><h3>Kategori Berat</h3><div class="stat-number">${totalBerat}</div></div><div class="stat-icon"><i class="fas fa-fire"></i></div></div>
        `;
    }

    // Render chart (grafik per jenis pelanggaran top 6)
    function renderChart() {
        const ctx = document.getElementById('violationChart').getContext('2d');
        // ambil 6 pelanggaran terbanyak berdasarkan frekuensi
        const sorted = [...violationData].sort((a,b) => b.frekuensi - a.frekuensi).slice(0,6);
        const labels = sorted.map(v => v.nama.length > 18 ? v.nama.slice(0,16)+'..' : v.nama);
        const data = sorted.map(v => v.frekuensi);
        
        if (chartInstance) chartInstance.destroy();
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Kejadian (Frekuensi)',
                    data: data,
                    backgroundColor: 'rgba(27, 74, 110, 0.7)',
                    borderRadius: 10,
                    barPercentage: 0.65
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 11 } } },
                    tooltip: { backgroundColor: '#0f2b3d' }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e9ecef' }, title: { display: true, text: 'Frekuensi' } },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
    }

    // Render daftar pelanggaran terbaru ( siswa )
    function renderRecent(recentData) {
        const container = document.getElementById("recentViolationsList");
        container.innerHTML = "";
        recentData.slice(0, 10).forEach(item => {
            const badgeCat = item.kategori === "Ringan" ? "badge-ringan" : (item.kategori === "Sedang" ? "badge-sedang" : "badge-berat");
            const div = document.createElement("div");
            div.className = "recent-item";
            div.innerHTML = `
                <div class="recent-info">
                    <strong><i class="fas fa-user"></i> ${item.siswa}</strong>
                    <small>${item.pelanggaran} • ${item.tanggal}</small>
                </div>
                <div>
                    <span class="badge ${badgeCat}" style="margin-right:8px;">${item.kategori}</span>
                    <span class="recent-point">${item.poin} poin</span>
                </div>
            `;
            container.appendChild(div);
        });
        if(recentData.length === 0) container.innerHTML = "<div style='padding:1rem; text-align:center'>Belum ada data pelanggaran</div>";
    }

    // handle filter buttons
    function initFilters() {
        const btns = document.querySelectorAll(".btn-filter");
        btns.forEach(btn => {
            btn.addEventListener("click", function() {
                btns.forEach(b => b.classList.remove("active-filter"));
                this.classList.add("active-filter");
                currentFilter = this.getAttribute("data-filter");
                renderTable();
                // chart tetap untuk keseluruhan, tidak perlu filter ulang pada chart (biar representatif)
            });
        });
    }

    // style active filter
    const style = document.createElement('style');
    style.textContent = `.active-filter { background: #1b4a6e; color: white; box-shadow: 0 2px 6px rgba(0,0,0,0.1); } .btn-filter { transition: all 0.1s; }`;
    document.head.appendChild(style);

    // inisialisasi semua
    const recentViolations = generateRecentViolations();
    renderRecent(recentViolations);
    renderTable();
    renderChart();
    initFilters();

    // tambahan efek untuk interaksi tombol dan menjaga grafik responsif
    window.addEventListener('resize', () => {
        if(chartInstance) chartInstance.resize();
    });
    
    // update stat tambahan: total point dll sudah di renderTable
    // juga menampilkan total pelanggaran di navbar statis (opsional)
    const totalFreq = violationData.reduce((sum, v) => sum + v.frekuensi, 0);
    const totalPointsAll = violationData.reduce((sum, v) => sum + (v.poin * v.frekuensi), 0);
    // bisa tampilkan tooltip tambahan
    console.log(`SMP Negeri 7 Jember - Total Kejadian: ${totalFreq}, Total Poin: ${totalPointsAll}`);
</script>
</body>
</html>