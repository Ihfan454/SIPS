/**
 * Data Siswa JavaScript
 * Sistem Informasi Pelanggaran Siswa SMP Negeri 7 Jember
 */

import './bootstrap';
import { initNotifications } from './notifications';

let siswaData = [];
let currentPage = 1;
const itemsPerPage = 10;
let filteredData = [];

function renderTable() {
    const tbody = document.getElementById('tableBody');
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = filteredData.slice(start, end);

    if (pageData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="10" style="text-align: center; padding: 2rem; color: #94a3b8;">
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
            <td><strong>${siswa.nis}</strong></td>
            <td>${siswa.nisn}</td>
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
                ${window.userCanCRUD ? `
                <button class="btn-edit" onclick="editSiswa(${siswa.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-delete" onclick="deleteSiswa(${siswa.id})">
                    <i class="fas fa-trash"></i>
                </button>
                ` : '<span style="color:#94a3b8;font-size:0.75rem;">Read Only</span>'}
            </td>
        </tr>
    `).join('');

    updatePagination();
}

function updatePagination() {
    const totalItems = filteredData.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const start = totalItems > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
    const end = Math.min(start + itemsPerPage - 1, totalItems);

    document.getElementById('paginationInfo').textContent =
        `Menampilkan ${start}-${end} dari ${totalItems} data`;

    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages || totalPages === 0;

    let pageNumbers = '';
    for (let i = 1; i <= totalPages; i++) {
        pageNumbers += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }
    document.getElementById('pageNumbers').innerHTML = pageNumbers || '<span style="color:#94a3b8;font-size:0.75rem;">Tidak ada data</span>';
}

window.goToPage = function (page) {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderTable();
};

async function loadSiswa() {
    const kelas = document.getElementById('filterKelas').value;
    const status = document.getElementById('filterStatus').value;
    const search = document.getElementById('searchSiswa').value;

    try {
        const { data } = await window.axios.get('/api/siswa', {
            params: { kelas, status, search },
        });
        siswaData = data.data;
        filteredData = [...siswaData];
        currentPage = 1;
        renderTable();
    } catch (error) {
        console.error('Gagal memuat data siswa:', error);
        showToast('Gagal memuat data siswa', 'error');
    }
}

async function loadStats() {
    try {
        const { data } = await window.axios.get('/api/siswa/stats');
        animateNumber('totalSiswa', data.total);
        animateNumber('siswaAktif', data.aktif);
        animateNumber('siswaNonaktif', data.nonaktif);
        animateNumber('totalKelas', data.total_kelas);
    } catch (error) {
        console.error('Gagal memuat statistik:', error);
    }
}

function animateNumber(id, target) {
    const el = document.getElementById(id);
    if (!el) return;

    let current = 0;
    const increment = Math.max(1, Math.ceil(target / 30));
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

window.editSiswa = function (id) {
    const siswa = siswaData.find(s => s.id === id);
    if (!siswa) return;

    document.getElementById('modalTitle').textContent = 'Edit Siswa';
    document.getElementById('editId').value = id;
    document.getElementById('nis').value = siswa.nis;
    document.getElementById('nisn').value = siswa.nisn;
    document.getElementById('nama').value = siswa.nama;
    document.getElementById('kelas').value = siswa.kelas;
    document.getElementById('jenisKelamin').value = siswa.jk;
    document.getElementById('noHp').value = siswa.noHp || '';
    document.getElementById('alamat').value = siswa.alamat || '';
    document.getElementById('status').value = siswa.status;
    document.getElementById('modalSiswa').classList.add('show');
};

window.deleteSiswa = async function (id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) return;

    try {
        await window.axios.delete(`/api/siswa/${id}`);
        showToast('Data siswa berhasil dihapus', 'success');
        await loadSiswa();
        await loadStats();
    } catch (error) {
        showToast(error.response?.data?.message || 'Gagal menghapus data siswa', 'error');
    }
};

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed; bottom: 2rem; right: 2rem; padding: 0.8rem 1.5rem;
        background: ${type === 'success' ? '#10b981' : '#ef4444'}; color: white;
        border-radius: 12px; font-size: 0.85rem; font-weight: 500;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15); z-index: 999;
        font-family: 'Inter', sans-serif; max-width: 350px;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
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

document.getElementById('filterKelas').addEventListener('change', loadSiswa);
document.getElementById('filterStatus').addEventListener('change', loadSiswa);
document.getElementById('searchSiswa').addEventListener('input', debounce(loadSiswa, 300));

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

document.getElementById('modalSiswa').addEventListener('click', (e) => {
    if (e.target === e.currentTarget) {
        document.getElementById('modalSiswa').classList.remove('show');
    }
});

document.getElementById('modalSave').addEventListener('click', async () => {
    const id = document.getElementById('editId').value;
    const payload = {
        nis: document.getElementById('nis').value.trim(),
        nisn: document.getElementById('nisn').value.trim(),
        nama_lengkap: document.getElementById('nama').value.trim(),
        kelas: document.getElementById('kelas').value,
        jenis_kelamin: document.getElementById('jenisKelamin').value,
        no_hp: document.getElementById('noHp').value.trim(),
        alamat: document.getElementById('alamat').value.trim(),
        is_active: document.getElementById('status').value === 'aktif',
    };

    if (!payload.nis || !payload.nisn || !payload.nama_lengkap || !payload.kelas || !payload.jenis_kelamin) {
        showToast('Harap isi semua field yang wajib!', 'error');
        return;
    }

    try {
        if (id) {
            await window.axios.put(`/api/siswa/${id}`, payload);
            showToast('Data siswa berhasil diperbarui', 'success');
        } else {
            await window.axios.post('/api/siswa', payload);
            showToast('Data siswa berhasil ditambahkan', 'success');
        }

        document.getElementById('modalSiswa').classList.remove('show');
        await loadSiswa();
        await loadStats();
    } catch (error) {
        const errors = error.response?.data?.errors;
        const message = errors
            ? Object.values(errors).flat().join(', ')
            : (error.response?.data?.message || 'Gagal menyimpan data siswa');
        showToast(message, 'error');
    }
});

document.getElementById('menuToggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('active');
});

document.getElementById('sidebarOverlay').addEventListener('click', () => {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
});

function debounce(fn, delay) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

async function loadNotifications(container) {
    try {
        const { data } = await window.axios.get('/api/dashboard/recent');
        container.innerHTML = '';

        if (!data.data || data.data.length === 0) {
            container.innerHTML = '<div style="padding: 16px; text-align: center; color: #858796; font-size: 13px;">Tidak ada notifikasi baru</div>';
            return;
        }

        // Ambil 3 notifikasi teratas
        const recentNotifs = data.data.slice(0, 3);
        
        // Update badge count
        const badge = document.querySelector('.notification .badge');
        if (badge) {
            badge.textContent = recentNotifs.length;
        }
        const countLabel = document.getElementById('notif-count');
        if (countLabel) {
            countLabel.textContent = `${recentNotifs.length} Baru`;
        }

        const badgeColor = { Ringan: '#10b981', Sedang: '#f59e0b', Berat: '#ef4444' };

        recentNotifs.forEach(item => {
            const div = document.createElement('div');
            div.style.cssText = `
                padding: 12px 16px;
                border-bottom: 1px solid #eaecf4;
                transition: background 0.2s;
                font-size: 13px;
                color: #333;
                cursor: pointer;
            `;
            div.addEventListener('mouseenter', () => div.style.backgroundColor = '#f8f9fc');
            div.addEventListener('mouseleave', () => div.style.backgroundColor = 'transparent');
            
            const color = badgeColor[item.kategori] || '#10b981';

            div.innerHTML = `
                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: ${color}; margin-top: 4px; flex-shrink: 0;"></div>
                    <div style="flex: 1;">
                        <strong style="color: #4e73df;">${item.siswa} (${item.kelas})</strong>
                        <div style="color: #666; margin-top: 2px;">Melakukan pelanggaran: <em>${item.pelanggaran}</em></div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px; font-size: 11px; color: #999;">
                            <span>${item.tanggal}</span>
                            <span style="font-weight: 700; color: #ef4444;">+${item.poin} Poin</span>
                        </div>
                    </div>
                </div>
            `;
            div.addEventListener('click', () => {
                window.location.href = '/pelanggaran';
            });
            container.appendChild(div);
        });
    } catch (error) {
        console.error('Gagal memuat notifikasi:', error);
        container.innerHTML = '<div style="padding: 16px; text-align: center; color: #e74a3b; font-size: 13px;">Gagal memuat data</div>';
    }
}

async function initNotificationDropdown() {
    const notificationContainer = document.querySelector('.notification');
    if (!notificationContainer) return;

    notificationContainer.style.cursor = 'pointer';
    notificationContainer.style.position = 'relative';

    const dropdown = document.createElement('div');
    dropdown.className = 'notification-dropdown';
    dropdown.style.cssText = `
        position: absolute;
        top: 50px;
        right: 0;
        width: 320px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: 1px solid #eaecf4;
        display: none;
        z-index: 1000;
        cursor: default;
        text-align: left;
        overflow: hidden;
        font-family: 'Inter', sans-serif;
    `;

    const header = document.createElement('div');
    header.style.cssText = `
        padding: 12px 16px;
        background: #4e73df;
        color: white;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    `;
    header.innerHTML = `<span>Notifikasi Pelanggaran</span><small id="notif-count" style="background:rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; font-size:11px;">3 Baru</small>`;
    dropdown.appendChild(header);

    const list = document.createElement('div');
    list.id = 'notif-list';
    list.style.cssText = `
        max-height: 280px;
        overflow-y: auto;
    `;
    list.innerHTML = `<div style="padding: 16px; text-align: center; color: #858796; font-size: 13px;"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>`;
    dropdown.appendChild(list);

    const footer = document.createElement('div');
    footer.style.cssText = `
        padding: 10px;
        text-align: center;
        border-top: 1px solid #eaecf4;
        background: #f8f9fc;
    `;
    footer.innerHTML = `<a href="/pelanggaran" style="color: #4e73df; text-decoration: none; font-size: 12px; font-weight: 600;">Lihat Semua Pelanggaran</a>`;
    dropdown.appendChild(footer);

    notificationContainer.appendChild(dropdown);

    notificationContainer.addEventListener('click', async (e) => {
        if (e.target.closest('.notification-dropdown')) {
            return;
        }
        
        e.stopPropagation();
        const isOpen = dropdown.style.display === 'block';
        
        document.querySelectorAll('.notification-dropdown').forEach(d => d.style.display = 'none');
        
        if (!isOpen) {
            dropdown.style.display = 'block';
            await loadNotifications(list);
        } else {
            dropdown.style.display = 'none';
        }
    });

    document.addEventListener('click', () => {
        dropdown.style.display = 'none';
    });
}

async function loadKelasOptions() {
    try {
        const { data } = await window.axios.get('/api/siswa/kelas');
        const classes = data.data;

        const filterKelas = document.getElementById('filterKelas');
        const formKelas = document.getElementById('kelas');

        if (filterKelas) {
            filterKelas.innerHTML = '<option value="all">Semua Kelas</option>';
            classes.forEach(cls => {
                const opt = document.createElement('option');
                opt.value = cls;
                opt.textContent = cls;
                filterKelas.appendChild(opt);
            });
        }

        if (formKelas) {
            formKelas.innerHTML = '<option value="">Pilih Kelas</option>';
            classes.forEach(cls => {
                const opt = document.createElement('option');
                opt.value = cls;
                opt.textContent = cls;
                formKelas.appendChild(opt);
            });
        }
    } catch (error) {
        console.error('Gagal memuat data kelas:', error);
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    await loadKelasOptions();
    await loadStats();
    await loadSiswa();
    initNotifications(); // real-time notification (polling)
});
