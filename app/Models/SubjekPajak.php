<?php

namespace App\Models;

use App\Models\ObjekPajak;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjekPajak extends Model
{
    use HasFactory;

    protected $table = 'subjek_pajak';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'rt',
        'rw',
        'no_hp',
    ];

    public function objekPajaks()
    {
        return $this->hasMany(ObjekPajak::class, 'nik_pemilik', 'nik');
    }
}
