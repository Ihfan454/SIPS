<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // NUPTK untuk guru BK / Admin
            $table->string('nuptk')->nullable()->unique()->after('email');
            
            // HANYA 1 ROLE: admin_bk (Admin dan Guru BK disatukan)
            $table->enum('role', ['admin_bk'])->default('admin_bk')->after('password');
            
            $table->string('jabatan')->nullable()->after('role'); // Kepala Sekolah, Waka Kesiswaan, Guru BK
            $table->string('no_hp')->nullable()->after('jabatan');
            $table->text('alamat')->nullable()->after('no_hp');
            $table->string('foto')->nullable()->after('alamat');
            $table->boolean('is_active')->default(true)->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nuptk',
                'role',
                'jabatan',
                'no_hp',
                'alamat',
                'foto',
                'is_active'
            ]);
        });
    }
};