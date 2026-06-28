<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create Kelas Table
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        // Seed default classes
        $now = now();
        DB::table('kelas')->insert([
            ['nama' => '7A', 'created_at' => $now, 'updated_at' => $now],
            ['nama' => '7B', 'created_at' => $now, 'updated_at' => $now],
            ['nama' => '8A', 'created_at' => $now, 'updated_at' => $now],
            ['nama' => '9A', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 2. Add class_id to Siswa Table
        Schema::table('siswa', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('kelas')->constrained('kelas')->nullOnDelete();
        });

        // Migrate existing siswa class to class_id
        $classes = DB::table('kelas')->get();
        foreach ($classes as $class) {
            DB::table('siswa')
                ->where('kelas', $class->nama)
                ->update(['class_id' => $class->id]);
        }

        // 3. Add class_id and modify role in Users Table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('role')->constrained('kelas')->nullOnDelete();
        });

        // Modify users role enum (change to string or modify enum using DB statement for MySQL)
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin_bk', 'guru_bk', 'wali_kelas', 'kepala_sekolah') DEFAULT 'admin_bk'");
        } else {
            // Fallback for sqlite/other databases
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('admin_bk')->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });

        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin_bk') DEFAULT 'admin_bk'");
        }

        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });

        Schema::dropIfExists('kelas');
    }
};
