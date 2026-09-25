<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LoginPengujiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\ExportController;
use App\Models\LoginPenguji;

if (!function_exists('getRekanPenguji')) {
    function getRekanPenguji($tipePenguji, $currentUserId) {
        return LoginPenguji::where('role', 'penguji')
            ->where('tipe_penguji', $tipePenguji)
            ->where('id', '!=', $currentUserId)
            ->first();
    }
}

Route::get('/login-penguji', [LoginPengujiController::class, 'showLoginForm'])->name('login.penguji');
Route::post('/login-penguji', [LoginPengujiController::class, 'login'])->name('login.penguji.submit');
Route::post('/logout-penguji', [LoginPengujiController::class, 'logout'])->name('logout.penguji');

Route::middleware('penguji')->group(function () {

    Route::get('/dashboard-penguji', function () {
        $tipePenguji = Session::get('tipe_penguji', 'wawancara');
        $namaPenguji = Session::get('nama_penguji', 'Penguji');
        $userId      = Session::get('user_id');

        $rekanPenguji = getRekanPenguji($tipePenguji, $userId);

        $pesertaTerbaru = \App\Models\Peserta::whereHas('pengujis', function ($q) use ($userId) {
                                $q->where('penguji_id', $userId);
                            })
                            ->orderBy('nama')
                            ->take(5)
                            ->get();

        $totalPeserta = \App\Models\Peserta::whereHas('pengujis', function ($q) use ($userId) {
                                $q->where('penguji_id', $userId);
                            })->count();

        $sudahDinilai = \App\Models\Penilaian::where('penguji_id', $userId)
                            ->where('tipe', $tipePenguji)
                            ->where('is_final', true)
                            ->distinct('peserta_id')
                            ->pluck('peserta_id')->toArray();

        $semuaPeserta = \App\Models\Peserta::whereHas('pengujis', function ($q) use ($userId) {
                                $q->where('penguji_id', $userId);
                            })->get();

        return view('dashboard-penguji', compact(
            'pesertaTerbaru', 'sudahDinilai', 'tipePenguji',
            'namaPenguji', 'semuaPeserta', 'rekanPenguji', 'totalPeserta'
        ));
    })->name('dashboard.penguji');

    Route::get('/peserta-penilaian', [PenilaianController::class, 'index'])->name('peserta.penilaian');
    Route::get('/peserta-penilaian/{pesertaId}/form', [PenilaianController::class, 'form'])->name('penilaian.form');
    Route::post('/peserta-penilaian/{pesertaId}/simpan', [PenilaianController::class, 'simpan'])->name('penilaian.simpan');

    Route::get('/penilaian-berhasil', function () {
        return view('penilaian-berhasil');
    })->name('penilaian.berhasil');

    Route::get('/profil-penguji', function () {
        $tipePenguji  = Session::get('tipe_penguji', 'wawancara');
        $namaPenguji  = Session::get('nama_penguji', 'Penguji');
        $userId       = Session::get('user_id');
        $rekanPenguji = getRekanPenguji($tipePenguji, $userId);

        return view('profil-penguji', compact('tipePenguji', 'namaPenguji', 'rekanPenguji'));
    })->name('profil.penguji');
});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola Penguji
    Route::get('/kelola-penguji', [AdminController::class, 'kelolaPenguji'])->name('kelola-penguji');
    Route::post('/kelola-penguji', [AdminController::class, 'storePenguji'])->name('kelola-penguji.store');
    Route::put('/kelola-penguji/{id}', [AdminController::class, 'updatePenguji'])->name('kelola-penguji.update');
    Route::delete('/kelola-penguji/{id}', [AdminController::class, 'destroyPenguji'])->name('kelola-penguji.destroy');

    // Kelola Peserta
    Route::get('/kelola-peserta', [PesertaController::class, 'index'])->name('kelola-peserta');
    Route::get('/kelola-peserta/create', [PesertaController::class, 'create'])->name('kelola-peserta.create');
    Route::post('/kelola-peserta', [PesertaController::class, 'store'])->name('kelola-peserta.store');
    Route::get('/kelola-peserta/{id}/edit', [PesertaController::class, 'edit'])->name('kelola-peserta.edit');
    Route::put('/kelola-peserta/{id}', [PesertaController::class, 'update'])->name('kelola-peserta.update');
    Route::delete('/kelola-peserta/{id}', [PesertaController::class, 'destroy'])->name('kelola-peserta.destroy');

    // Edit Nilai
    Route::get('/edit-nilai', [AdminController::class, 'editNilai'])->name('edit-nilai');
    Route::post('/edit-nilai', [AdminController::class, 'updateNilai'])->name('edit-nilai.update');

    // Rekap Nilai
    Route::get('/rekap-nilai', [AdminController::class, 'rekapNilai'])->name('rekap-nilai');

    // Kelola User
    Route::get('/kelola-user', [AdminController::class, 'kelolaUser'])->name('kelola-user');
    Route::post('/kelola-user/{id}/toggle', [AdminController::class, 'toggleUserActive'])->name('kelola-user.toggle');
    Route::post('/kelola-user/{id}/reset', [AdminController::class, 'resetPassword'])->name('kelola-user.reset');

    // Export
    Route::get('/export/csv', [ExportController::class, 'csv'])->name('export.csv');
    Route::get('/export/pdf', [ExportController::class, 'pdf'])->name('export.pdf');
});

Route::get('/', function () {
    return redirect()->route('login.penguji');
});