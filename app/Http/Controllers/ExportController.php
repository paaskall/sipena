<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    // ============ EXPORT CSV ============
    public function csv()
    {
        $pesertas = Peserta::with(['pengujis', 'penilaians'])->orderBy('nama')->get();

        $filename = 'rekap-nilai-ukom-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($pesertas) {
            $file = fopen('php://output', 'w');

            // BOM biar Excel baca UTF-8 dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'No', 'Nama Peserta', 'Instansi', 'Jabatan',
                'Penguji Wawancara 1', 'Nilai', 
                'Penguji Wawancara 2', 'Nilai',
                'Penguji Tertulis', 'Nilai',
                'Rata Wawancara', 'Rata Tertulis', 'Nilai Akhir', 'Status',
            ]);

            foreach ($pesertas as $i => $p) {
                $pengujiWawancara = $p->pengujis->whereIn('pivot.peran', ['wawancara_1', 'wawancara_2'])->values();
                $pengujiTertulis  = $p->pengujis->where('pivot.peran', 'tertulis')->first();

                $nilaiWawancara = function ($pengujiId) use ($p) {
                    return $p->penilaians->where('penguji_id', $pengujiId)->where('tipe', 'wawancara')->whereNotNull('nilai')->avg('nilai');
                };

                $nilaiTertulis = $pengujiTertulis
                    ? $p->penilaians->where('penguji_id', $pengujiTertulis->id)->where('tipe', 'tertulis')->whereNotNull('nilai')->avg('nilai')
                    : null;

                $p1 = $pengujiWawancara[0] ?? null;
                $p2 = $pengujiWawancara[1] ?? null;

                fputcsv($file, [
                    $i + 1,
                    $p->nama,
                    $p->instansi,
                    $p->jabatan,
                    $p1->nama ?? '-',
                    $p1 ? ($nilaiWawancara($p1->id) !== null ? round($nilaiWawancara($p1->id), 2) : '-') : '-',
                    $p2->nama ?? '-',
                    $p2 ? ($nilaiWawancara($p2->id) !== null ? round($nilaiWawancara($p2->id), 2) : '-') : '-',
                    $pengujiTertulis->nama ?? '-',
                    $nilaiTertulis !== null ? round($nilaiTertulis, 2) : '-',
                    $p->rataWawancara() ?? '-',
                    $p->rataTertulis() ?? '-',
                    $p->nilaiAkhir() ?? '-',
                    match ($p->statusKelulusan()) {
                        'lulus'        => 'LULUS',
                        'tidak_lulus'  => 'TIDAK LULUS',
                        default        => 'BELUM DINILAI',
                    },
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ============ EXPORT PDF ============
    public function pdf()
    {
        $pesertas = Peserta::with(['pengujis', 'penilaians'])->orderBy('nama')->get();

        $pdf = Pdf::loadView('exports.rekap-pdf', compact('pesertas'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('rekap-nilai-ukom-' . now()->format('Ymd-His') . '.pdf');
    }
}