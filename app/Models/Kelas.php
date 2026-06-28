<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama',
    ];

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'class_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'class_id');
    }
}
