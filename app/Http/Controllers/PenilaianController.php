<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    /**
     * Struktur elemen penilaian (hardcode dulu, biar konsisten dengan view lama).
     */
    public static function elemenWawancara(): array
    {
        return [
            ['Kemampuan Analisis', 'Kompetensi Inti', 'Pengetahuan tentang Bidang Pekerjaan'],
            ['', '', 'Kemampuan menulis dan publikasi'],
            ['Kemampuan Politis', 'Kompetensi Inti', 'Konteks Politik'],
            ['', '', 'Regulasi dan Legislasi'],
            ['', '', 'Komunikasi'],
            ['', '', 'Membangun jejaring'],
            ['', 'Kompetensi Spesialis', 'Presentasi'],
            ['', '', 'Konsultasi Publik'],
            ['', '', 'Partnership'],
            ['Kemampuan Analisis & Politis', 'Kompetensi Dasar', 'Manajemen Diri'],
            ['', '', 'Membangun Tim'],
        ];
    }

    public static function elemenTertulis(): array
    {
        return [
            ['Kemampuan Analisis', 'Kompetensi Inti', 'Pengetahuan tentang substansi Kebijakan Publik'],
            ['', '', 'Metode Riset'],
            ['', '', 'Teknik dan Analisis Kebijakan'],
            ['', 'Kompetensi Spesialis', 'Penyusunan Saran Kebijakan'],
            ['Kemampuan Politis', 'Kompetensi Inti', 'Regulasi dan Legislasi'],
        ];
    }

    // ============ DAFTAR PESERTA UNTUK PENGUJI ============
    public function index()
    {
        $userId   = session('user_id');
        $tipe     = session('tipe_penguji', 'wawancara');

        // Ambil peserta yang ditugaskan ke penguji ini (berdasarkan pivot)
        $pesertas = Peserta::whereHas('pengujis', function ($q) use ($userId) {
                        $q->where('penguji_id', $userId);
                    })
                    ->with(['penilaians' => function ($q) use ($userId, $tipe) {
                        $q->where('penguji_id', $userId)->where('tipe', $tipe);
                    }])
                    ->orderBy('nama')
                    ->get();

        return view('peserta-penilaian', compact('pesertas', 'tipe'));
    }

    // ============ FORM PENILAIAN PESERTA ============
    public function form($pesertaId)
    {
        $userId = session('user_id');
        $tipe   = session('tipe_penguji', 'wawancara');

        $peserta = Peserta::whereHas('pengujis', function ($q) use ($userId) {
                        $q->where('penguji_id', $userId);
                    })
                    ->with(['penilaians' => function ($q) use ($userId, $tipe) {
                        $q->where('penguji_id', $userId)->where('tipe', $tipe);
                    }])
                    ->findOrFail($pesertaId);

        // Cek sudah final?
        $sudahFinal = $peserta->penilaians->where('is_final', true)->count() > 0;

        $elemen = $tipe === 'wawancara' ? self::elemenWawancara() : self::elemenTertulis();

        // Map penilaian yang sudah ada berdasarkan elemen_index
        $nilaiTersimpan = $peserta->penilaians->keyBy('elemen_index');

        return view('form-penilaian', compact('peserta', 'elemen', 'tipe', 'sudahFinal', 'nilaiTersimpan'));
    }

    // ============ SIMPAN PENILAIAN ============
    public function simpan(Request $request, $pesertaId)
    {
        $userId = session('user_id');
        $tipe   = session('tipe_penguji', 'wawancara');

        $peserta = Peserta::whereHas('pengujis', function ($q) use ($userId) {
                        $q->where('penguji_id', $userId);
                    })->findOrFail($pesertaId);

        $elemen = $tipe === 'wawancara' ? self::elemenWawancara() : self::elemenTertulis();

        $values   = $request->input($tipe, []);
        $catatans = $request->input($tipe . '_catatan', []);

        DB::transaction(function () use ($peserta, $userId, $tipe, $elemen, $values, $catatans) {
            foreach ($elemen as $i => $e) {
                Penilaian::updateOrCreate(
                    [
                        'peserta_id'   => $peserta->id,
                        'penguji_id'   => $userId,
                        'tipe'         => $tipe,
                        'elemen_index' => $i,
                    ],
                    [
                        'judul_unit'        => $e[0] ?: null,
                        'jenis_kompetensi'  => $e[1] ?: null,
                        'elemen_kompetensi' => $e[2],
                        'nilai'             => isset($values[$i]) && $values[$i] !== '' ? $values[$i] : null,
                        'catatan'           => $catatans[$i] ?? null,
                        'is_final'          => true,
                        'submitted_at'      => now(),
                    ]
                );
            }
        });

        return redirect()->route('penilaian.berhasil');
    }
}