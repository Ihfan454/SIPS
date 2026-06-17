<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Admin/Guru BK yang mencatat
            $table->foreignId('guru_kelas_id')->nullable()->constrained('guru_kelas')->nullOnDelete(); // Pelapor (guru kelas)
            $table->string('jenis_pelanggaran');
            $table->enum('kategori', ['ringan', 'sedang', 'berat']);
            $table->integer('poin');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_pelanggaran');
            $table->time('waktu_pelanggaran')->nullable();
            $table->string('tempat_pelanggaran')->nullable();
            $table->text('tindakan')->nullable();
            $table->enum('status', ['proses', 'selesai'])->default('proses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran');
    }
};