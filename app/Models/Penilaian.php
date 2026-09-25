<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaians';

    protected $fillable = [
        'peserta_id', 'penguji_id', 'tipe',
        'elemen_index', 'judul_unit', 'jenis_kompetensi', 'elemen_kompetensi',
        'nilai', 'catatan',
        'is_final', 'submitted_at',
        'edited_by_admin_id', 'edited_by_admin_at',
    ];

    protected $casts = [
        'nilai'               => 'decimal:2',
        'is_final'            => 'boolean',
        'submitted_at'        => 'datetime',
        'edited_by_admin_at'  => 'datetime',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function penguji()
    {
        return $this->belongsTo(LoginPenguji::class, 'penguji_id');
    }

    public function editedByAdmin()
    {
        return $this->belongsTo(LoginPenguji::class, 'edited_by_admin_id');
    }
}