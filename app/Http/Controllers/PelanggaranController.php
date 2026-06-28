<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    // Menampilkan daftar pelanggaran ke tabel
    public function index() 
    {
        $query = Pelanggaran::with('siswa');

        // Batasi Wali Kelas
        if (auth()->user()->isWaliKelas()) {
            if (auth()->user()->class_id) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('class_id', auth()->user()->class_id);
                });
            } else {
                $data_pelanggaran = collect();
                return view('pelanggaran', compact('data_pelanggaran'));
            }
        }

        $data_pelanggaran = $query->latest()->get();
        return view('pelanggaran', compact('data_pelanggaran'));
    }

    // Menampilkan form create
    public function create() 
    {
        if (!auth()->user()->isAdminBK() && !auth()->user()->isGuruBK()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mencatat pelanggaran.');
        }

        $data_siswa = Siswa::all();
        return view('pelanggaran-create', compact('data_siswa'));
    }

    // Menyimpan data ke database
    public function store(Request $request) 
    {
        if (!auth()->user()->isAdminBK() && !auth()->user()->isGuruBK()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mencatat pelanggaran.');
        }

        // Validasi dasar
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_pelanggaran' => 'required|string',
            'kategori' => 'required|in:ringan,sedang,berat',
            'poin' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        // Menggabungkan data dari form ke kolom database
        $data = [
            'siswa_id' => $request->siswa_id,
            'jenis_pelanggaran' => $request->jenis_pelanggaran,
            'kategori' => $request->kategori,
            'poin' => $request->poin,
            'tanggal_pelanggaran' => $request->tanggal,
            'deskripsi' => $request->catatan,
            'user_id' => auth()->id() ?? 1, // Menggunakan user yang sedang login, fallback ke 1 jika null
            'status' => 'proses', // lowercase sesuai enum database
        ];

        Pelanggaran::create($data);

        return redirect()->route('pelanggaran')->with('success', 'Data pelanggaran berhasil dicatat!');
    }
}