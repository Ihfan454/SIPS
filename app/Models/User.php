<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nuptk',
        'role',
        'jabatan',
        'no_hp',
        'alamat',
        'foto',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relasi ke pelanggaran
    public function pelanggarans()
    {
        return $this->hasMany(Pelanggaran::class, 'user_id');
    }

    // Helper (karena role hanya satu, bisa langsung return true)
    public function isAdminBK()
    {
        return $this->role === 'admin_bk';
    }

    // Scope untuk user aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}