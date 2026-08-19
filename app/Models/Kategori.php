<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Produk;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'nama',
    ];

    // Relasi: satu Kategori bisa punya banyak Produk
    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}