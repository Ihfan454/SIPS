<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nuptk')->unique();
            $table->string('nama_lengkap');
            $table->string('mata_pelajaran');
            $table->string('kelas_wali')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_kelas');
    }
};