<?php

namespace App\Models;

use App\Models\Mutasi;
use App\Models\Sppt;
use App\Models\SubjekPajak;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjekPajak extends Model
{
    use HasFactory;

    protected $table = 'objek_pajak';

    protected $fillable = [
        'nop',
        'nik_pemilik',
        'letak_objek',
        'luas_bumi',
        'luas_bangunan',
        'status_aktif',
    ];

    public function subjekPajak()
    {
        return $this->belongsTo(SubjekPajak::class, 'nik_pemilik', 'nik');
    }

    public function sppts()
    {
        return $this->hasMany(Sppt::class, 'nop', 'nop');
    }

    public function mutasis()
    {
        return $this->hasMany(Mutasi::class, 'nop_asal', 'nop');
    }
}
