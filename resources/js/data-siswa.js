/**
 * Data Siswa JavaScript
 * Sistem Informasi Pelanggaran Siswa SMP Negeri 7 Jember
 */

// ========================================
// DATA SISWA (SIMULASI)
// ========================================
let siswaData = [
    { id: 1, nisn: '2024001', nama: 'Ahmad Rizki', kelas: '7A', jk: 'L', noHp: '081234567890', alamat: 'Jl. Merdeka No.1', status: 'aktif', poin: 5 },
    { id: 2, nisn: '2024002', nama: 'Bunga Citra', kelas: '7A', jk: 'P', noHp: '081234567891', alamat: 'Jl. Diponegoro No.2', status: 'aktif', poin: 0 },
    { id: 3, nisn: '2024003', nama: 'Citra Dewi', kelas: '7B', jk: 'P', noHp: '081234567892', alamat: 'Jl. Sudirman No.3', status: 'aktif', poin: 10 },
    { id: 4, nisn: '2024004', nama: 'Danu Pratama', kelas: '7B', jk: 'L', noHp: '081234567893', alamat: 'Jl. Ahmad Yani No.4', status: 'nonaktif', poin: 0 },
    { id: 5, nisn: '2024005', nama: 'Eka Fitriani', kelas: '7C', jk: 'P', noHp: '081234567894', alamat: 'Jl. Kartini No.5', status: 'aktif', poin: 15 },
    { id: 6, nisn: '2023001', nama: 'Fajar Nugroho', kelas: '8A', jk: 'L', noHp: '081234567895', alamat: 'Jl. Pahlawan No.6', status: 'aktif', poin: 25 },
    { id: 7, nisn: '2023002', nama: 'Gita Puspita', kelas: '8A', jk: 'P', noHp: '081234567896', alamat: 'Jl. Veteran No.7', status: 'aktif', poin: 0 },
    { id: 8, nisn: '2023003', nama: 'Hendra Wijaya', kelas: '8B', jk: 'L', noHp: '081234567897', alamat: 'Jl. Siliwangi No.8', status: 'aktif', poin: 30 },
    { id: 9, nisn: '2023004', nama: 'Indah Lestari', kelas: '8B', jk: 'P', noHp: '081234567898', alamat: 'Jl. Gatot Subroto No.9', status: 'nonaktif', poin: 0 },
    { id: 10, nisn: '2022001', nama: 'Joko Susilo', kelas: '9A', jk: 'L', noHp: '081234567899', alamat: 'Jl. Raya No.10', status: 'aktif', poin: 40 },
    { id: 11, nisn: '2022002', nama: 'Kartika Sari', kelas: '9A', jk: 'P', noHp: '081234567800', alamat: 'Jl. Mawar No.11', status: 'aktif', poin: 0 },
    { id: 12, nisn: '2022003', nama: 'Lukman Hakim', kelas: '9B', jk: 'L', noHp: '081234567801', alamat: 'Jl. Melati No.12', status: 'aktif', poin: 20 },
];

let currentPage = 1;
const itemsPerPage = 10;
let filteredData = [...siswaData];

// ========================================
// RENDER TABLE
// ========================================
function renderTable() {
    const tbody = document.getElementById('tableBody');
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = filteredData.slice(start, end);

    if (pageData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" style="text-align: center; padding: 2rem; color: #94a3b8;">
                    <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                    Tidak ada data siswa
                </td>
            </tr>
        `;
        updatePagination();
        return;
    }

    tbody.innerHTML = pageData.map((siswa, index) => `
        <tr>
            <td>${start + index + 1}</td>
            <td><strong>${siswa.nisn}</strong></td>
            <td>${siswa.nama}</td>
            <td>${siswa.kelas}</td>
            <td>${siswa.jk === 'L' ? 'Laki-laki' : 'Perempuan'}</td>
            <td>${siswa.noHp || '-'}</td>
            <td>
                <span class="badge-status ${siswa.status}">
                    ${siswa.status === 'aktif' ? 'Aktif' : 'Tidak Aktif'}
                </span>
            </td>
            <td>
                ${siswa.poin > 0 ? `<span style="color: ${siswa.poin > 20 ? '#ef4444' : '#f59e0b'}; font-weight: 700;">${siswa.poin}</span>` : '0'}
            </td>
            <td>
                <button class="btn-edit" onclick="editSiswa(${siswa.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-delete" onclick="deleteSiswa(${siswa.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');

    updatePagination();
}

// ========================================
// UPDATE PAGINATION
// ========================================
function updatePagination() {
    const totalItems = filteredData.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const start = (currentPage - 1) * itemsPerPage + 1;
    const end = Math.min(start + itemsPerPage - 1, totalItems);

    document.getElementById('paginationInfo').textContent = 
        `Menampilkan ${totalItems > 0 ? start : 0}-${end} dari ${totalItems} data`;

    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages || totalPages === 0;

    let pageNumbers = '';
    for (let i = 1; i <= totalPages; i++) {
        pageNumbers += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }
    document.getElementById('pageNumbers').innerHTML = pageNumbers || '<span style="color:#94a3b8;font-size:0.75rem;">Tidak ada data</span>';
}

// ========================================
// PAGE FUNCTIONS
// ========================================
function goToPage(page) {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderTable();
}

document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
});

document.getElementById('nextPage').addEventListener('click', () => {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderTable();
    }
});

// ========================================
// FILTER & SEARCH
// ========================================
function applyFilters() {
    const kelas = document.getElementById('filterKelas').value;
    const status = document.getElementById('filterStatus').value;
    const search = document.getElementById('searchSiswa').value.toLowerCase();

    filteredData = siswaData.filter(siswa => {
        const matchKelas = kelas === 'all' || siswa.kelas === kelas;
        const matchStatus = status === 'all' || siswa.status === status;
        const matchSearch = siswa.nama.toLowerCase().includes(search) || 
                           siswa.nisn.includes(search);
        return matchKelas && matchStatus && matchSearch;
    });

    currentPage = 1;
    renderTable();
    updateStats();
}

document.getElementById('filterKelas').addEventListener('change', applyFilters);
document.getElementById('filterStatus').addEventListener('change', applyFilters);
document.getElementById('searchSiswa').addEventListener('input', applyFilters);

// ========================================
// UPDATE STATS
// ========================================
function updateStats() {
    const total = siswaData.length;
    const aktif = siswaData.filter(s => s.status === 'aktif').length;
    const nonaktif = siswaData.filter(s => s.status === 'nonaktif').length;
    const kelas = [...new Set(siswaData.map(s => s.kelas))].length;

    animateNumber('totalSiswa', total);
    animateNumber('siswaAktif', aktif);
    animateNumber('siswaNonaktif', nonaktif);
    animateNumber('totalKelas', kelas);
}

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
// CRUD OPERATIONS
// ========================================
function editSiswa(id) {
    const siswa = siswaData.find(s => s.id === id);
    if (!siswa) return;

    document.getElementById('modalTitle').textContent = 'Edit Siswa';
    document.getElementById('editId').value = id;
    document.getElementById('nisn').value = siswa.nisn;
    document.getElementById('nama').value = siswa.nama;
    document.getElementById('kelas').value = siswa.kelas;
    document.getElementById('jenisKelamin').value = siswa.jk;
    document.getElementById('noHp').value = siswa.noHp || '';
    document.getElementById('alamat').value = siswa.alamat || '';
    document.getElementById('status').value = siswa.status;

    document.getElementById('modalSiswa').classList.add('show');
}

function deleteSiswa(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) {
        siswaData = siswaData.filter(s => s.id !== id);
        applyFilters();
        updateStats();
        showToast('Data siswa berhasil dihapus', 'success');
    }
}

// ========================================
// MODAL CONTROLS
// ========================================
document.getElementById('btnTambahSiswa').addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Tambah Siswa';
    document.getElementById('editId').value = '';
    document.getElementById('formSiswa').reset();
    document.getElementById('status').value = 'aktif';
    document.getElementById('modalSiswa').classList.add('show');
});

document.getElementById('modalClose').addEventListener('click', () => {
    document.getElementById('modalSiswa').classList.remove('show');
});

document.getElementById('modalCancel').addEventListener('click', () => {
    document.getElementById('modalSiswa').classList.remove('show');
});

// Close modal on overlay click
document.getElementById('modalSiswa').addEventListener('click', (e) => {
    if (e.target === e.currentTarget) {
        document.getElementById('modalSiswa').classList.remove('show');
    }
});

// ========================================
// SAVE STUDENT
// ========================================
document.getElementById('modalSave').addEventListener('click', () => {
    const id = document.getElementById('editId').value;
    const nisn = document.getElementById('nisn').value.trim();
    const nama = document.getElementById('nama').value.trim();
    const kelas = document.getElementById('kelas').value;
    const jk = document.getElementById('jenisKelamin').value;
    const noHp = document.getElementById('noHp').value.trim();
    const alamat = document.getElementById('alamat').value.trim();
    const status = document.getElementById('status').value;

    // Validasi
    if (!nisn || !nama || !kelas || !jk) {
        showToast('Harap isi semua field yang wajib!', 'error');
        return;
    }

    if (id) {
        // Edit
        const index = siswaData.findIndex(s => s.id === parseInt(id));
        if (index !== -1) {
            siswaData[index] = {
                ...siswaData[index],
                nisn,
                nama,
                kelas,
                jk,
                noHp,
                alamat,
                status
            };
            showToast('Data siswa berhasil diperbarui', 'success');
        }
    } else {
        // Tambah
        const newId = Math.max(...siswaData.map(s => s.id), 0) + 1;
        siswaData.push({
            id: newId,
            nisn,
            nama,
            kelas,
            jk,
            noHp,
            alamat,
            status,
            poin: 0
        });
        showToast('Data siswa berhasil ditambahkan', 'success');
    }

    document.getElementById('modalSiswa').classList.remove('show');
    applyFilters();
    updateStats();
});

// ========================================
// TOAST NOTIFICATION
// ========================================
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        padding: 0.8rem 1.5rem;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        z-index: 999;
        animation: slideIn 0.3s ease;
        font-family: 'Inter', sans-serif;
        max-width: 350px;
    `;
    toast.textContent = message;

    // Style untuk animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => {
            toast.remove();
            style.remove();
        }, 300);
    }, 3000);
}

// ========================================
// SIDEBAR TOGGLE (MOBILE)
// ========================================
document.getElementById('menuToggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('active');
});

document.getElementById('sidebarOverlay').addEventListener('click', () => {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
});

// ========================================
// INITIALIZE
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    updateStats();
    renderTable();
    console.log('✨ Data Siswa page ready - SMP Negeri 7 Jember');
});