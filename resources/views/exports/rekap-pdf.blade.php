<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Nilai UKom</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { text-align: center; font-size: 14px; margin-bottom: 4px; }
        p.sub { text-align: center; font-size: 10px; color: #555; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        th { background-color: #e5e7eb; font-weight: bold; font-size: 10px; }
        td.center { text-align: center; }
        .lulus { color: #15803d; font-weight: bold; }
        .tidak { color: #b91c1c; font-weight: bold; }
    </style>
</head>
<body>
    <h1>REKAPITULASI NILAI UJIAN KOMPETENSI</h1>
    <p class="sub">Lembaga Administrasi Negara RI — Dicetak: {{ now()->format('d F Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Nama Peserta</th>
                <th>Instansi</th>
                <th style="width:60px" class="center">Rata Wawancara</th>
                <th style="width:60px" class="center">Rata Tertulis</th>
                <th style="width:60px" class="center">Nilai Akhir</th>
                <th style="width:80px" class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pesertas as $i => $p)
                @php
                    $w = $p->rataWawancara();
                    $t = $p->rataTertulis();
                    $akhir = $p->nilaiAkhir();
                    $status = $p->statusKelulusan();
                @endphp
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->instansi }}</td>
                    <td class="center">{{ $w !== null ? number_format($w, 2) : '—' }}</td>
                    <td class="center">{{ $t !== null ? number_format($t, 2) : '—' }}</td>
                    <td class="center">{{ $akhir !== null ? number_format($akhir, 2) : '—' }}</td>
                    <td class="center">
                        @if ($status === 'lulus')
                            <span class="lulus">LULUS</span>
                        @elseif ($status === 'tidak_lulus')
                            <span class="tidak">TIDAK LULUS</span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>