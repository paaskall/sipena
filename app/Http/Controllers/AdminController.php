<?php

namespace App\Http\Controllers;

use App\Models\LoginPenguji;
use App\Models\Peserta;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // ============ DASHBOARD ============
    public function dashboard()
    {
        $totalPenguji   = LoginPenguji::where('role', 'penguji')->count();
        $totalPeserta   = Peserta::count();
        $totalPenilaian = Penilaian::whereNotNull('nilai')->count();

        return view('admin.dashboard', compact('totalPenguji', 'totalPeserta', 'totalPenilaian'));
    }

    // ============ KELOLA PENGUJI ============
    public function kelolaPenguji()
    {
        $pengujis = LoginPenguji::where('role', 'penguji')
            ->orderBy('tipe_penguji')
            ->orderBy('nama')
            ->get();

        return view('admin.kelola-penguji', compact('pengujis'));
    }

    public function storePenguji(Request $request)
    {
        $data = $request->validate([
            'nama'         => 'required|string|max:255',
            'email'        => 'required|email|unique:login_pengujis,email',
            'username'     => 'required|string|unique:login_pengujis,username',
            'password'     => 'required|string|min:6',
            'tipe_penguji' => 'required|in:wawancara,tertulis',
            'nip'          => 'nullable|string|max:50',
            'no_hp'        => 'nullable|string|max:20',
            'jabatan'      => 'nullable|string|max:255',
            'instansi'     => 'nullable|string|max:255',
            'kelompok'     => 'nullable|string|max:50',
        ]);

        $data['role']      = 'penguji';
        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = true;

        LoginPenguji::create($data);

        return redirect()->route('admin.kelola-penguji')
            ->with('success', 'Penguji berhasil ditambahkan.');
    }

    public function updatePenguji(Request $request, $id)
    {
        $penguji = LoginPenguji::where('role', 'penguji')->findOrFail($id);

        $data = $request->validate([
            'nama'         => 'required|string|max:255',
            'email'        => ['required', 'email', Rule::unique('login_pengujis', 'email')->ignore($penguji->id)],
            'username'     => ['required', 'string', Rule::unique('login_pengujis', 'username')->ignore($penguji->id)],
            'tipe_penguji' => 'required|in:wawancara,tertulis',
            'nip'          => 'nullable|string|max:50',
            'no_hp'        => 'nullable|string|max:20',
            'jabatan'      => 'nullable|string|max:255',
            'instansi'     => 'nullable|string|max:255',
            'kelompok'     => 'nullable|string|max:50',
            'is_active'    => 'nullable|boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $data['is_active'] = $request->boolean('is_active');

        $penguji->update($data);

        return redirect()->route('admin.kelola-penguji')
            ->with('success', 'Data penguji berhasil diperbarui.');
    }

    public function destroyPenguji($id)
    {
        $penguji = LoginPenguji::where('role', 'penguji')->findOrFail($id);
        $penguji->delete();

        return redirect()->route('admin.kelola-penguji')
            ->with('success', 'Penguji berhasil dihapus.');
    }

    // ============ KELOLA USER (semua role) ============
    public function kelolaUser()
    {
        $users = LoginPenguji::orderBy('role')->orderBy('nama')->get();
        return view('admin.kelola-user', compact('users'));
    }

    public function toggleUserActive($id)
    {
        $user = LoginPenguji::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', 'Status user berhasil diubah.');
    }

    public function resetPassword($id)
    {
        $user = LoginPenguji::findOrFail($id);
        $user->update(['password' => Hash::make('password123')]);

        return back()->with('success', 'Password direset menjadi: password123');
    }

    // ============ EDIT NILAI ============
    public function editNilai(Request $request)
    {
        $pesertaList = Peserta::orderBy('nama')->get();

        $pesertaId = $request->get('peserta_id', $pesertaList->first()->id ?? null);
        $peserta   = $pesertaId ? Peserta::with(['penilaians.penguji'])->find($pesertaId) : null;

        return view('admin.edit-nilai', compact('pesertaList', 'peserta'));
    }

    public function updateNilai(Request $request)
    {
        $request->validate([
            'penilaian_id' => 'required|exists:penilaians,id',
            'nilai'        => 'nullable|numeric|min:0|max:100',
            'catatan'      => 'nullable|string',
        ]);

        $penilaian = Penilaian::findOrFail($request->penilaian_id);
        $penilaian->update([
            'nilai'               => $request->nilai,
            'catatan'             => $request->catatan,
            'edited_by_admin_id'  => session('user_id'),
            'edited_by_admin_at'  => now(),
        ]);

        return back()->with('success', 'Nilai berhasil diperbarui oleh admin.');
    }

    // ============ REKAP NILAI ============
    public function rekapNilai()
    {
        $pesertas = Peserta::with(['pengujis', 'penilaians'])->orderBy('nama')->get();

        return view('admin.rekap-nilai', compact('pesertas'));
    }
}