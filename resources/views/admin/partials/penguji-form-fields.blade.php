<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="form-label">Nama Lengkap <span class="req">*</span></label>
        <input type="text" name="nama" required class="form-input" placeholder="cth: Dr. Budi Santoso, M.Si">
    </div>
    <div>
        <label class="form-label">Email <span class="req">*</span></label>
        <input type="email" name="email" required class="form-input" placeholder="email@lanri.go.id">
    </div>
    <div>
        <label class="form-label">Username <span class="req">*</span></label>
        <input type="text" name="username" required class="form-input" placeholder="username">
    </div>
    <div>
        <label class="form-label">Password <span class="req">*</span></label>
        <input type="password" name="password" required class="form-input" placeholder="Minimal 6 karakter">
    </div>
    <div>
        <label class="form-label">Tipe Penguji <span class="req">*</span></label>
        <select name="tipe_penguji" required class="form-select">
            <option value="">-- Pilih Tipe --</option>
            <option value="wawancara">Wawancara</option>
            <option value="tertulis">Tertulis</option>
        </select>
    </div>
    <div>
        <label class="form-label">NIP</label>
        <input type="text" name="nip" class="form-input" placeholder="Nomor Induk Pegawai">
    </div>
    <div>
        <label class="form-label">No. HP</label>
        <input type="text" name="no_hp" class="form-input" placeholder="08xxxxxxxxxx">
    </div>
    <div>
        <label class="form-label">Jabatan</label>
        <input type="text" name="jabatan" class="form-input" placeholder="cth: Penguji Ahli">
    </div>
    <div>
        <label class="form-label">Instansi</label>
        <input type="text" name="instansi" class="form-input" placeholder="cth: LAN RI">
    </div>
    <div>
        <label class="form-label">Kelompok</label>
        <input type="text" name="kelompok" class="form-input" placeholder="cth: A">
    </div>
</div>