<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default tanggal: awal bulan ini sampai hari ini
        $tanggal_mulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tanggal_selesai = $request->input('tanggal_selesai', Carbon::now()->toDateString());
        $kelas_selected = $request->input('kelas');
        $kategori_selected = $request->input('kategori');

        // Batasi Wali Kelas
        $isWali = auth()->user()->isWaliKelas();
        $classId = auth()->user()->class_id;
        $kelas_name = auth()->user()->kelas ? auth()->user()->kelas->nama : null;

        if ($isWali) {
            $kelas_selected = $kelas_name;
        }

        if ($isWali) {
            $list_kelas = collect($kelas_name ? [$kelas_name] : []);
        } else {
            $list_kelas = \App\Models\Kelas::orderBy('nama', 'asc')->pluck('nama');
        }

        // Query Pelanggaran
        $query = Pelanggaran::with('siswa');

        if ($isWali) {
            $query->whereHas('siswa', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        // Filter Rentang Tanggal
        if ($tanggal_mulai) {
            $query->whereDate('tanggal_pelanggaran', '>=', $tanggal_mulai);
        }
        if ($tanggal_selesai) {
            $query->whereDate('tanggal_pelanggaran', '<=', $tanggal_selesai);
        }

        // Filter Kelas (untuk non-wali kelas)
        if (!$isWali && $kelas_selected) {
            $query->whereHas('siswa', function ($q) use ($kelas_selected) {
                $q->where('kelas', $kelas_selected);
            });
        }

        // Filter Kategori
        if ($kategori_selected) {
            $query->where('kategori', $kategori_selected);
        }

        // Dapatkan data pelanggaran yang terfilter
        $data_pelanggaran = $query->latest('tanggal_pelanggaran')
            ->latest('created_at')
            ->get();

        // Hitung Statistik Berdasarkan Hasil Filter
        $stats = [
            'total' => $data_pelanggaran->count(),
            'ringan' => $data_pelanggaran->where('kategori', 'ringan')->count(),
            'sedang' => $data_pelanggaran->where('kategori', 'sedang')->count(),
            'berat' => $data_pelanggaran->where('kategori', 'berat')->count(),
            'total_poin' => $data_pelanggaran->sum('poin'),
        ];

        return view('laporan', compact(
            'data_pelanggaran',
            'list_kelas',
            'tanggal_mulai',
            'tanggal_selesai',
            'kelas_selected',
            'kategori_selected',
            'stats'
        ));
    }
}
