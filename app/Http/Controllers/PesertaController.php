<?php

namespace App\Http\Controllers;

use App\Models\LoginPenguji;
use App\Models\Peserta;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesertaController extends Controller
{
    // ============ LIST PESERTA ============
    public function index()
    {
        $pesertas = Peserta::with('pengujis')->orderBy('nama')->get();
        return view('admin.kelola-peserta', compact('pesertas'));
    }

    // ============ FORM TAMBAH ============
    public function create()
    {
        $pengujiWawancara = LoginPenguji::where('role', 'penguji')
            ->where('tipe_penguji', 'wawancara')->where('is_active', true)
            ->orderBy('nama')->get();

        $pengujiTertulis = LoginPenguji::where('role', 'penguji')
            ->where('tipe_penguji', 'tertulis')->where('is_active', true)
            ->orderBy('nama')->get();

        return view('admin.peserta-form', compact('pengujiWawancara', 'pengujiTertulis'));
    }

    // ============ SIMPAN PESERTA BARU ============
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'            => 'required|string|max:255',
            'instansi'        => 'required|string|max:255',
            'jabatan'         => 'required|string|max:255',
            'butuh_tertulis'  => 'nullable|boolean',

            // Penguji wawancara: 1 atau 2 orang (tergantung butuh_tertulis)
            'penguji_wawancara'   => 'required|array|min:1|max:2',
            'penguji_wawancara.*' => 'exists:login_pengujis,id',

            // Penguji tertulis: wajib kalau butuh_tertulis
            'penguji_tertulis'    => 'nullable|exists:login_pengujis,id',
        ]);

        $butuhTertulis = $request->boolean('butuh_tertulis');

        // Validasi: butuh tertulis → wajib 1 penguji tertulis
        if ($butuhTertulis && empty($data['penguji_tertulis'])) {
            return back()->withErrors(['penguji_tertulis' => 'Peserta yang butuh tertulis harus punya 1 penguji tertulis.'])->withInput();
        }

        // Validasi: kalau tidak butuh tertulis → harus 2 penguji wawancara
        if (!$butuhTertulis && count($data['penguji_wawancara']) < 2) {
            return back()->withErrors(['penguji_wawancara' => 'Peserta wajib memiliki 2 penguji wawancara.'])->withInput();
        }

        DB::transaction(function () use ($data, $butuhTertulis) {
            $peserta = Peserta::create([
                'nama'           => $data['nama'],
                'instansi'       => $data['instansi'],
                'jabatan'        => $data['jabatan'],
                'butuh_tertulis' => $butuhTertulis,
                'is_active'      => true,
            ]);

            // Assign penguji wawancara
            $peran = ['wawancara_1', 'wawancara_2'];
            foreach ($data['penguji_wawancara'] as $i => $pengujiId) {
                $peserta->pengujis()->attach($pengujiId, ['peran' => $peran[$i]]);
            }

            // Assign penguji tertulis kalau ada
            if ($butuhTertulis && !empty($data['penguji_tertulis'])) {
                $peserta->pengujis()->attach($data['penguji_tertulis'], ['peran' => 'tertulis']);
            }
        });

        return redirect()->route('admin.kelola-peserta')
            ->with('success', 'Peserta berhasil ditambahkan beserta pengujinya.');
    }

    // ============ FORM EDIT ============
    public function edit($id)
    {
        $peserta = Peserta::with('pengujis')->findOrFail($id);

        $pengujiWawancara = LoginPenguji::where('role', 'penguji')
            ->where('tipe_penguji', 'wawancara')->where('is_active', true)
            ->orderBy('nama')->get();

        $pengujiTertulis = LoginPenguji::where('role', 'penguji')
            ->where('tipe_penguji', 'tertulis')->where('is_active', true)
            ->orderBy('nama')->get();

        // Ambil id penguji yang sudah di-assign
        $assignedWawancara = $peserta->pengujis
            ->whereIn('pivot.peran', ['wawancara_1', 'wawancara_2'])
            ->pluck('id')->toArray();

        $assignedTertulis = $peserta->pengujis
            ->where('pivot.peran', 'tertulis')
            ->pluck('id')->first();

        return view('admin.peserta-form', compact(
            'peserta', 'pengujiWawancara', 'pengujiTertulis',
            'assignedWawancara', 'assignedTertulis'
        ));
    }

    // ============ UPDATE PESERTA ============
    public function update(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        $data = $request->validate([
            'nama'                => 'required|string|max:255',
            'instansi'            => 'required|string|max:255',
            'jabatan'             => 'required|string|max:255',
            'butuh_tertulis'      => 'nullable|boolean',
            'penguji_wawancara'   => 'required|array|min:1|max:2',
            'penguji_wawancara.*' => 'exists:login_pengujis,id',
            'penguji_tertulis'    => 'nullable|exists:login_pengujis,id',
            'is_active'           => 'nullable|boolean',
        ]);

        $butuhTertulis = $request->boolean('butuh_tertulis');

        if ($butuhTertulis && empty($data['penguji_tertulis'])) {
            return back()->withErrors(['penguji_tertulis' => 'Peserta yang butuh tertulis harus punya 1 penguji tertulis.'])->withInput();
        }

        if (!$butuhTertulis && count($data['penguji_wawancara']) < 2) {
            return back()->withErrors(['penguji_wawancara' => 'Peserta wajib memiliki 2 penguji wawancara.'])->withInput();
        }

        DB::transaction(function () use ($peserta, $data, $butuhTertulis, $request) {
            $peserta->update([
                'nama'           => $data['nama'],
                'instansi'       => $data['instansi'],
                'jabatan'        => $data['jabatan'],
                'butuh_tertulis' => $butuhTertulis,
                'is_active'      => $request->boolean('is_active', true),
            ]);

            // Reset semua pivot penguji, lalu attach ulang
            $peserta->pengujis()->detach();

            $peran = ['wawancara_1', 'wawancara_2'];
            foreach ($data['penguji_wawancara'] as $i => $pengujiId) {
                $peserta->pengujis()->attach($pengujiId, ['peran' => $peran[$i]]);
            }

            if ($butuhTertulis && !empty($data['penguji_tertulis'])) {
                $peserta->pengujis()->attach($data['penguji_tertulis'], ['peran' => 'tertulis']);
            }

            // Kalau butuh_tertulis berubah jadi false, hapus penilaian tertulis
            if (!$butuhTertulis) {
                Penilaian::where('peserta_id', $peserta->id)->where('tipe', 'tertulis')->delete();
            }
        });

        return redirect()->route('admin.kelola-peserta')
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    // ============ HAPUS PESERTA ============
    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);
        $peserta->delete();

        return redirect()->route('admin.kelola-peserta')
            ->with('success', 'Peserta berhasil dihapus.');
    }
}