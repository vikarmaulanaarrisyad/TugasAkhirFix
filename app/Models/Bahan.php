<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bahan extends Model
{
    use HasFactory;
    protected $table = 'bahans';

    public function variants()
    {
        return $this->hasMany(BahanVariant::class);
    }
}
