<?php

namespace Database\Seeders;

use App\Models\LoginPenguji;
use App\Models\Peserta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PesertaPengujiSeeder extends Seeder
{
    public function run(): void
    {
        // ============ ADMIN ============
        LoginPenguji::firstOrCreate(
            ['email' => 'admin@lanri.go.id'],
            [
                'nama'         => 'Administrator LAN',
                'username'     => 'admin',
                'password'     => Hash::make('admin123'),
                'role'         => 'admin',
                'tipe_penguji' => 'none',
                'is_active'    => true,
            ]
        );

        // ============ PENGUJI WAWANCARA ============
        $wawancara = [
            [
                'nama' => 'Dr. Muhammad Aswad, M.Si', 'email' => 'aswad@lanri.go.id',
                'username' => 'aswad', 'kelompok' => 'A', 'jabatan' => 'Penguji Ahli',
            ],
            [
                'nama' => 'Dr. Siti Aminah, M.Pd', 'email' => 'siti@lanri.go.id',
                'username' => 'siti', 'kelompok' => 'B', 'jabatan' => 'Penguji Ahli',
            ],
        ];

        $pengujiWawancara = [];
        foreach ($wawancara as $w) {
            $pengujiWawancara[] = LoginPenguji::firstOrCreate(
                ['email' => $w['email']],
                array_merge($w, [
                    'password'     => Hash::make('penguji123'),
                    'role'         => 'penguji',
                    'tipe_penguji' => 'wawancara',
                    'is_active'    => true,
                ])
            );
        }

        // ============ PENGUJI TERTULIS ============
        $tertulis = [
            [
                'nama' => 'Prof. Budi Santoso, Ph.D', 'email' => 'budi@lanri.go.id',
                'username' => 'budi', 'kelompok' => 'A', 'jabatan' => 'Penguji Ahli',
            ],
            [
                'nama' => 'Dr. Rina Marlina, M.Si', 'email' => 'rina@lanri.go.id',
                'username' => 'rina', 'kelompok' => 'B', 'jabatan' => 'Penguji Ahli',
            ],
        ];

        $pengujiTertulis = [];
        foreach ($tertulis as $t) {
            $pengujiTertulis[] = LoginPenguji::firstOrCreate(
                ['email' => $t['email']],
                array_merge($t, [
                    'password'     => Hash::make('penguji123'),
                    'role'         => 'penguji',
                    'tipe_penguji' => 'tertulis',
                    'is_active'    => true,
                ])
            );
        }

        // ============ PESERTA ============
        $pesertaData = [
            ['Andi Pratama', 'Kota Magelang', 'Analis Kepegawaian', false],
            ['Siti Nurhaliza', 'Kab. Semarang', 'Kepala Sub Bidang', true],
            ['Budi Santoso', 'Pemprov Jawa Tengah', 'Auditor Muda', false],
            ['Rina Oktaviani', 'Kab. Kebumen', 'Perencana Ahli Muda', true],
            ['Dedi Kurniawan', 'Kota Surakarta', 'Penyuluh Sosial', false],
            ['Putri Anggraini', 'Kab. Wonogiri', 'Analis SDM Aparatur', true],
            ['Fahri Ramadhan', 'Kab. Boyolali', 'Kepala Seksi', false],
            ['Nabila Safitri', 'Kab. Klaten', 'Pranata Komputer', true],
            ['Rizky Maulana', 'Kab. Sukoharjo', 'Analis Kebijakan', false],
            ['Lina Marlina', 'Kab. Karanganyar', 'Bendahara Pengeluaran', true],
        ];

        foreach ($pesertaData as $i => $p) {
            $peserta = Peserta::firstOrCreate(
                ['nama' => $p[0]],
                [
                    'instansi'       => $p[1],
                    'jabatan'        => $p[2],
                    'butuh_tertulis' => $p[3],
                    'is_active'      => true,
                ]
            );

            // Reset pivot
            $peserta->pengujis()->detach();

            // Rotasi penguji wawancara
            $p1 = $pengujiWawancara[$i % count($pengujiWawancara)];
            $p2 = $pengujiWawancara[($i + 1) % count($pengujiWawancara)];

            $peserta->pengujis()->attach($p1->id, ['peran' => 'wawancara_1']);
            $peserta->pengujis()->attach($p2->id, ['peran' => 'wawancara_2']);

            // Kalau butuh tertulis → tambah 1 penguji tertulis
            if ($p[3]) {
                $pt = $pengujiTertulis[$i % count($pengujiTertulis)];
                $peserta->pengujis()->attach($pt->id, ['peran' => 'tertulis']);
            }
        }
    }
}