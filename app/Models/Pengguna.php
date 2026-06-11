<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengguna extends Model
{
    protected $table = 'pengguna';

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'peran'
    ];

    protected $hidden = [
        'kata_sandi'
    ];

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'pengguna_id');
    }
}
