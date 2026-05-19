<?php

namespace App\Models;

use App\Models\ObjekPajak;
use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sppt extends Model
{
    use HasFactory;

    protected $table = 'sppt';
    protected $primaryKey = 'id_sppt';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_sppt',
        'nop',
        'tahun',
        'njop_bumi',
        'njop_bangunan',
        'pajak_terhutang',
        'status_bayar',
    ];

    public function objekPajak()
    {
        return $this->belongsTo(ObjekPajak::class, 'nop', 'nop');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_sppt', 'id_sppt');
    }
}
