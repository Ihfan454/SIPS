/**
 * Dashboard JavaScript
 * Sistem Informasi Pelanggaran Siswa SMP Negeri 7 Jember
 */

// ========================================
// DATA SIMULASI
// ========================================
const violationData = [
    { nama: "Terlambat masuk sekolah", kategori: "Ringan", poin: 5, frekuensi: 32 },
    { nama: "Seragam tidak rapi", kategori: "Ringan", poin: 5, frekuensi: 28 },
    { nama: "Membuang sampah sembarangan", kategori: "Ringan", poin: 5, frekuensi: 18 },
    { nama: "Keluar kelas tanpa izin", kategori: "Sedang", poin: 10, frekuensi: 14 },
    { nama: "Berbicara tidak sopan", kategori: "Sedang", poin: 15, frekuensi: 9 },
    { nama: "Menggunakan HP tanpa izin", kategori: "Sedang", poin: 10, frekuensi: 22 },
    { nama: "Bolos tanpa keterangan", kategori: "Berat", poin: 25, frekuensi: 7 },
    { nama: "Berkelahi", kategori: "Berat", poin: 30, frekuensi: 4 },
    { nama: "Vandalisme", kategori: "Berat", poin: 35, frekuensi: 3 },
    { nama: "Bullying", kategori: "Berat", poin: 40, frekuensi: 2 }
];

const siswaData = [
    { nama: "Ahmad Rizki", kelas: "7A" },
    { nama: "Bunga Citra", kelas: "7A" },
    { nama: "Citra Dewi", kelas: "7B" },
    { nama: "Danu Pratama", kelas: "7B" },
    { nama: "Eka Fitriani", kelas: "7C" },
    { nama: "Fajar Nugroho", kelas: "8A" },
    { nama: "Gita Puspita", kelas: "8A" },
    { nama: "Hendra Wijaya", kelas: "8B" },
    { nama: "Indah Lestari", kelas: "8B" },
    { nama: "Joko Susilo", kelas: "9A" },
    { nama: "Kartika Sari", kelas: "9A" },
    { nama: "Lukman Hakim", kelas: "9B" }
];

const statusList = ['selesai', 'proses', 'menunggu'];
const statusLabel = { selesai: 'Selesai', proses: 'Proses BK', menunggu: 'Menunggu' };

// ========================================
// HELPER FUNCTIONS
// ========================================
function getRandomStatus() {
    return statusList[Math.floor(Math.random() * statusList.length)];
}

function generateRecentData() {
    const recent = [];
    const jenisPelanggaran = violationData.map(v => v.nama);
    
    for (let i = 0; i < 10; i++) {
        const randomSiswa = siswaData[Math.floor(Math.random() * siswaData.length)];
        const randomViolasi = jenisPelanggaran[Math.floor(Math.random() * jenisPelanggaran.length)];
        const vData = violationData.find(v => v.nama === randomViolasi);
        const status = getRandomStatus();
        const tanggal = new Date();
        tanggal.setDate(tanggal.getDate() - Math.floor(Math.random() * 14));
        const formattedDate = `${tanggal.getDate()}/${tanggal.getMonth()+1}/${tanggal.getFullYear()}`;
        
        recent.push({
            siswa: randomSiswa.nama,
            kelas: randomSiswa.kelas,
            pelanggaran: randomViolasi,
            kategori: vData.kategori,
            poin: vData.poin,
            tanggal: formattedDate,
            status: status
        });
    }
    
    recent.sort((a, b) => {
        const dateA = a.tanggal.split('/').reverse().join('-');
        const dateB = b.tanggal.split('/').reverse().join('-');
        return dateB.localeCompare(dateA);
    });
    
    return recent;
}

// ========================================
// ANIMATE NUMBER COUNTER
// ========================================
function animateNumber(id, target) {
    const el = document.getElementById(id);
    if (!el) return;
    
    let current = 0;
    const increment = Math.ceil(target / 30);
    const stepTime = 800 / 30;
    
    const update = () => {
        current += increment;
        if (current < target) {
            el.textContent = current;
            setTimeout(update, stepTime);
        } else {
            el.textContent = target;
        }
    };
    
    update();
}

// ========================================
// UPDATE STATS
// ========================================
function updateStats() {
    const totalSiswa = siswaData.length;
    const totalPelanggaran = violationData.reduce((sum, v) => sum + v.frekuensi, 0);
    const totalSelesai = Math.floor(totalPelanggaran * 0.65);
    const totalProses = totalPelanggaran - totalSelesai;
    
    animateNumber('totalSiswa', totalSiswa);
    animateNumber('totalPelanggaran', totalPelanggaran);
    animateNumber('totalSelesai', totalSelesai);
    animateNumber('totalProses', totalProses);
}

// ========================================
// RENDER RECENT TABLE
// ========================================
function renderRecentTable() {
    const data = generateRecentData();
    const tbody = document.getElementById('recentTableBody');
    tbody.innerHTML = '';
    
    data.forEach(item => {
        const tr = document.createElement('tr');
        
        const badgeColor = {
            'Ringan': 'badge-ringan',
            'Sedang': 'badge-sedang',
            'Berat': 'badge-berat'
        };
        
        const statusClass = {
            'selesai': 'selesai',
            'proses': 'proses',
            'menunggu': 'menunggu'
        };
        
        tr.innerHTML = `
            <td><strong>${item.siswa}</strong></td>
            <td>${item.kelas}</td>
            <td>${item.pelanggaran}</td>
            <td><span class="badge-kategori ${badgeColor[item.kategori]}">${item.kategori}</span></td>
            <td>${item.poin}</td>
            <td>${item.tanggal}</td>
            <td><span class="badge-status ${statusClass[item.status]}">${statusLabel[item.status]}</span></td>
        `;
        
        tbody.appendChild(tr);
    });
}

// ========================================
// RENDER CHARTS
// ========================================
let mainChart = null;
let pieChart = null;

function renderMainChart() {
    const ctx = document.getElementById('mainChart').getContext('2d');
    const sorted = [...violationData].sort((a, b) => b.frekuensi - a.frekuensi).slice(0, 6);
    const labels = sorted.map(v => v.nama.length > 15 ? v.nama.slice(0, 14) + '...' : v.nama);
    const data = sorted.map(v => v.frekuensi);
    
    if (mainChart) mainChart.destroy();
    
    mainChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Frekuensi Pelanggaran',
                data: data,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(99, 102, 241, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(16, 185, 129, 0.8)'
                ],
                borderRadius: 8,
                barPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 11 },
                    bodyFont: { size: 10 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { font: { size: 10 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 9 } }
                }
            }
        }
    });
}

function renderPieChart() {
    const ctx = document.getElementById('pieChart').getContext('2d');
    const ringan = violationData.filter(v => v.kategori === 'Ringan').reduce((s, v) => s + v.frekuensi, 0);
    const sedang = violationData.filter(v => v.kategori === 'Sedang').reduce((s, v) => s + v.frekuensi, 0);
    const berat = violationData.filter(v => v.kategori === 'Berat').reduce((s, v) => s + v.frekuensi, 0);
    
    if (pieChart) pieChart.destroy();
    
    pieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Ringan', 'Sedang', 'Berat'],
            datasets: [{
                data: [ringan, sedang, berat],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 3,
                borderColor: 'white'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 11 },
                        padding: 12
                    }
                }
            },
            cutout: '65%'
        }
    });
}

function renderCharts() {
    renderMainChart();
    renderPieChart();
}

// ========================================
// SIDEBAR TOGGLE (MOBILE)
// ========================================
function initSidebarToggle() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (!menuToggle || !sidebar || !overlay) return;
    
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
    });
    
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
    });
}

// ========================================
// CHART PERIOD BUTTONS
// ========================================
function initChartButtons() {
    const buttons = document.querySelectorAll('.chart-btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            renderCharts();
        });
    });
}

// ========================================
// ANIMASI MASUK
// ========================================
function animateEntrance() {
    const cards = document.querySelectorAll('.stat-card, .chart-card, .recent-section');
    cards.forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.5s ease ${0.1 * i}s`;
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
    });
}

// ========================================
// INITIALIZE
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    updateStats();
    renderRecentTable();
    renderCharts();
    initSidebarToggle();
    initChartButtons();
    animateEntrance();
    
    console.log('✨ Dashboard ready - SMP Negeri 7 Jember');
});