<?php

namespace App\Models;

use App\Models\Penilaian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'pesertas';

    protected $fillable = [
        'nama', 'instansi', 'jabatan', 'butuh_tertulis', 'is_active',
    ];

    protected $casts = [
        'butuh_tertulis' => 'boolean',
        'is_active'      => 'boolean',
    ];

    public function pengujis()
    {
        return $this->belongsToMany(LoginPenguji::class, 'peserta_penguji', 'peserta_id', 'penguji_id')
                    ->withPivot('peran')
                    ->withTimestamps();
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function pengujiWawancara()
    {
        return $this->pengujis()->wherePivotIn('peran', ['wawancara_1', 'wawancara_2']);
    }

    public function pengujiTertulis()
    {
        return $this->pengujis()->wherePivot('peran', 'tertulis');
    }

    public function rataWawancara(): ?float
    {
        $avg = $this->penilaians()->where('tipe', 'wawancara')->whereNotNull('nilai')->avg('nilai');
        return $avg !== null ? round((float) $avg, 2) : null;
    }

    public function rataTertulis(): ?float
    {
        $avg = $this->penilaians()->where('tipe', 'tertulis')->whereNotNull('nilai')->avg('nilai');
        return $avg !== null ? round((float) $avg, 2) : null;
    }

    public function nilaiAkhir(): ?float
    {
        $w = $this->rataWawancara();
        $t = $this->rataTertulis();

        if ($w === null && $t === null) return null;
        if ($t === null) return $w;
        if ($w === null) return $t;

        return round(($w * 0.6) + ($t * 0.4), 2);
    }

    public function statusKelulusan(): string
    {
        $n = $this->nilaiAkhir();
        if ($n === null) return 'belum';
        return $n >= 71 ? 'lulus' : 'tidak_lulus';
    }
}