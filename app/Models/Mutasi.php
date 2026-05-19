<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use HasFactory;

    protected $table = 'mutasi';
    protected $primaryKey = 'id_mutasi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_mutasi',
        'nop_asal',
        'nik_lama',
        'nik_baru',
        'jenis_mutasi',
        'tgl_mutasi',
        'no_arsip',
    ];

    protected $casts = [
        'tgl_mutasi' => 'date',
    ];

    public function objekPajak()
    {
        return $this->belongsTo(ObjekPajak::class, 'nop_asal', 'nop');
    }

    public function subjekLama()
    {
        return $this->belongsTo(SubjekPajak::class, 'nik_lama', 'nik');
    }

    public function subjekBaru()
    {
        return $this->belongsTo(SubjekPajak::class, 'nik_baru', 'nik');
    }
}
