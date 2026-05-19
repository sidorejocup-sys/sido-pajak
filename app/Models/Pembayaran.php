<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_bayar';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_bayar',
        'id_sppt',
        'tgl_bayar',
        'jumlah_bayar',
        'id_petugas',
    ];

    protected $casts = [
        'tgl_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
    ];

    public function sppt()
    {
        return $this->belongsTo(Sppt::class, 'id_sppt', 'id_sppt');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas');
    }
}
