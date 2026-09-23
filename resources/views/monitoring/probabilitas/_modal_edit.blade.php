<dialog id="modal-edit" class="modal modal-lg">
    <form id="form-edit" method="POST">
        @csrf
        @method('PUT')

        {{-- Lokasi & koordinat tetap hidden (jarang diubah di modal ini) --}}
        <input type="hidden" name="lokasi" id="f-lokasi">
        <input type="hidden" name="tikor_lat" id="f-lat">
        <input type="hidden" name="tikor_lng" id="f-lng">

        <div class="modal-header-blue">
            <div>
                <h2 id="edit-judul">SPKLU</h2>
                <p id="edit-subjudul">TIKOR : — . ULP —</p>
            </div>
            <button type="button" class="modal-close" onclick="document.getElementById('modal-edit').close()">X</button>
        </div>

        <div class="modal-body">

            {{-- ============ BANNER NOTIF ============ --}}
            <div id="edit-notif" hidden style="
                margin-bottom: 16px;
                padding: 10px 14px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 500;
                align-items: center;
                gap: 8px;
            ">
                <span id="edit-notif-icon"></span>
                <span id="edit-notif-text"></span>
            </div>

            <div class="edit-card">
                <h3>Identitas Lokasi</h3>
                <div class="form-row">
                    <div>
                        <label>ULP</label>
                        <select name="ulp" id="f-ulp" required>
                            <option value="">— Pilih ULP —</option>
                            @foreach ($daftarUlp as $ulp)
                                <option value="{{ $ulp->nama_penuh }}">{{ $ulp->nama_penuh }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Skema</label>
                        <select name="skema" id="f-skema">
                            <option value="">— Pilih Skema —</option>
                            <option value="Skema 1">Skema 1</option>
                            <option value="Skema 2">Skema 2</option>
                            <option value="Skema 3">Skema 3</option>
                            <option value="Skema 4">Skema 4</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="edit-card">
                <h3>Kebutuhan Mesin (unit)</h3>
                <div class="form-row form-row-6">
                    <div><label>22kW</label><input type="number" min="0" placeholder="0" name="kebutuhan_22kw" id="f-22kw" onfocus="this.select()"></div>
                    <div><label>30kW</label><input type="number" min="0" placeholder="0" name="kebutuhan_30kw" id="f-30kw" onfocus="this.select()"></div>
                    <div><label>50kW</label><input type="number" min="0" placeholder="0" name="kebutuhan_50kw" id="f-50kw" onfocus="this.select()"></div>
                    <div><label>60kW</label><input type="number" min="0" placeholder="0" name="kebutuhan_60kw" id="f-60kw" onfocus="this.select()"></div>
                    <div><label>120kW</label><input type="number" min="0" placeholder="0" name="kebutuhan_120kw" id="f-120kw" onfocus="this.select()"></div>
                    <div><label>180kW</label><input type="number" min="0" placeholder="0" name="kebutuhan_180kw" id="f-180kw" onfocus="this.select()"></div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Mitra Mesin</label>
                        <select name="mitra_mesin" id="f-mitra-mesin">
                            <option value="">— Pilih Mitra Mesin —</option>
                            <option value="UCI Beny">UCI Beny</option>
                            <option value="Voltron">Voltron</option>
                            <option value="EAD">EAD</option>
                            <option value="LAD">LAD</option>
                            <option value="Niscala">Niscala</option>
                            <option value="Prastiwahyu">Prastiwahyu</option>
                            <option value="TEB">TEB</option>
                            <option value="Arista">Arista</option>
                            <option value="PLN ES">PLN ES</option>
                        </select>
                    </div>
                    <div>
                        <label>Poin Perluasan Jaringan</label>
                        <select name="poin_perluasan_jaringan" id="f-poin-jaringan">
                            <option value="0">0</option>
                            <option value="0.5">0.5</option>
                            <option value="1">1</option>
                            <option value="1.5">1.5</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="edit-card">
                <h3>Poin Fasilitas <span class="hint">(0 = tidak ada, 1 = ada)</span></h3>
                <div class="form-row form-row-4">
                    <div>
                        <label>Ruang Tunggu</label>
                        <select name="fasilitas_ruang_tunggu" id="f-fas-ruang-tunggu">
                            <option value="1">Ada</option>
                            <option value="0">Tidak Ada</option>
                        </select>
                    </div>
                    <div>
                        <label>Parkir</label>
                        <select name="fasilitas_parkir" id="f-fas-parkir">
                            <option value="1">Ada</option>
                            <option value="0">Tidak Ada</option>
                        </select>
                    </div>
                    <div>
                        <label>Toilet</label>
                        <select name="fasilitas_toilet" id="f-fas-toilet">
                            <option value="1">Ada</option>
                            <option value="0">Tidak Ada</option>
                        </select>
                    </div>
                    <div>
                        <label>Kafe</label>
                        <select name="fasilitas_kafe" id="f-fas-kafe">
                            <option value="1">Ada</option>
                            <option value="0">Tidak Ada</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="edit-card">
                <h3>Poin Okupansi <span class="hint">(0 = tidak ada, 1 = ada)</span></h3>
                <div class="form-row form-row-4">
                    <div>
                        <label>Perumahan</label>
                        <select name="okupansi_perumahan" id="f-oku-perumahan">
                            <option value="1">Ada</option>
                            <option value="0">Tidak Ada</option>
                        </select>
                    </div>
                    <div>
                        <label>Pintu Tol</label>
                        <select name="okupansi_pintu_tol" id="f-oku-pintu-tol">
                            <option value="1">Ada</option>
                            <option value="0">Tidak Ada</option>
                        </select>
                    </div>
                    <div>
                        <label>Pusat Keramaian</label>
                        <select name="okupansi_pusat_keramaian" id="f-oku-pusat-keramaian">
                            <option value="1">Ada</option>
                            <option value="0">Tidak Ada</option>
                        </select>
                    </div>
                    <div>
                        <label>Ruas Jalan</label>
                        <select name="okupansi_ruas_jalan" id="f-oku-ruas-jalan">
                            <option value="1">Ada</option>
                            <option value="0">Tidak Ada</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="edit-card">
                <h3>Status Tahapan</h3>
                <p class="hint">Klik salah satu tahap untuk lihat riwayat &amp; tambah kunjungan baru.</p>
                <div id="edit-status-tahapan" class="tahap-quick-list">
                    {{-- diisi lewat JS --}}
                </div>
            </div>

            <div class="edit-card">
                <h3>Keterangan</h3>
                <textarea name="keterangan" id="f-keterangan" rows="2"></textarea>
            </div>

            <div class="progres-preview">
                <span>Progres saat ini: <strong id="edit-persentase">0%</strong></span>
                <span class="badge-kategori" id="edit-kategori-badge">Belum Dihitung</span>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-edit').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</dialog>

<script>
const TAHAPAN_LABELS = @json(\App\Models\Probabilitas::TAHAPAN);
window.CURRENT_PROBABILITAS_ID = null;

// dipanggil dari _modal_riwayat.blade.php setelah tambah kunjungan sukses
window.tampilkanNotifEdit = function (pesan, tipe = 'sukses') {
    const notif = document.getElementById('edit-notif');
    const icon  = document.getElementById('edit-notif-icon');
    const teks  = document.getElementById('edit-notif-text');

    teks.textContent = pesan;
    icon.textContent = tipe === 'sukses' ? '✓' : '✕';
    notif.style.background = tipe === 'sukses' ? '#dcfce7' : '#fef2f2';
    notif.style.color = tipe === 'sukses' ? '#166534' : '#b91c1c';
    notif.style.border = '1px solid ' + (tipe === 'sukses' ? '#86efac' : '#fecaca');
    notif.style.display = 'flex';
    notif.hidden = false;

    notif.scrollIntoView({ behavior: 'smooth', block: 'start' });

    clearTimeout(window._editNotifTimeout);
    window._editNotifTimeout = setTimeout(() => {
        notif.hidden = true;
        notif.style.display = '';
    }, 3000);
};

window.isiModalEdit = function (data) {
    const p = data.probabilitas;
    const badges = data.badges;
    window.CURRENT_PROBABILITAS_ID = p.id;

    const notifEl = document.getElementById('edit-notif');
    notifEl.hidden = true;
    notifEl.style.display = '';

    document.getElementById('form-edit').action = `/monitoring/probabilitas/${p.id}`;
    document.getElementById('edit-judul').textContent = p.lokasi;
    document.getElementById('edit-subjudul').textContent = `TIKOR : ${p.tikor_lat}, ${p.tikor_lng} . ULP ${p.ulp}`;

    document.getElementById('f-lokasi').value = p.lokasi;
    document.getElementById('f-lat').value = p.tikor_lat;
    document.getElementById('f-lng').value = p.tikor_lng;

    document.getElementById('f-ulp').value = p.ulp ?? '';
    document.getElementById('f-skema').value = p.skema ?? '';

    // Field angka: 0/kosong ditampilkan kosong (placeholder "0") supaya
    // user bisa langsung ketik tanpa perlu hapus angka 0 dulu.
    document.getElementById('f-22kw').value = p.kebutuhan_22kw || '';
    document.getElementById('f-30kw').value = p.kebutuhan_30kw || '';
    document.getElementById('f-50kw').value = p.kebutuhan_50kw || '';
    document.getElementById('f-60kw').value = p.kebutuhan_60kw || '';
    document.getElementById('f-120kw').value = p.kebutuhan_120kw || '';
    document.getElementById('f-180kw').value = p.kebutuhan_180kw || '';
    document.getElementById('f-mitra-mesin').value = p.mitra_mesin ?? '';
    document.getElementById('f-poin-jaringan').value = p.poin_perluasan_jaringan || '';
    document.getElementById('f-keterangan').value = p.keterangan ?? '';

    document.getElementById('f-fas-ruang-tunggu').value = p.fasilitas_ruang_tunggu ? '1' : '0';
    document.getElementById('f-fas-parkir').value = p.fasilitas_parkir ? '1' : '0';
    document.getElementById('f-fas-toilet').value = p.fasilitas_toilet ? '1' : '0';
    document.getElementById('f-fas-kafe').value = p.fasilitas_kafe ? '1' : '0';
    document.getElementById('f-oku-perumahan').value = p.okupansi_perumahan ? '1' : '0';
    document.getElementById('f-oku-pintu-tol').value = p.okupansi_pintu_tol ? '1' : '0';
    document.getElementById('f-oku-pusat-keramaian').value = p.okupansi_pusat_keramaian ? '1' : '0';
    document.getElementById('f-oku-ruas-jalan').value = p.okupansi_ruas_jalan ? '1' : '0';

    document.getElementById('edit-persentase').textContent = `${p.persentase_progres}%`;
    const badgeEl = document.getElementById('edit-kategori-badge');
    badgeEl.textContent = p.kategori ?? 'Belum Dihitung';
    badgeEl.className = 'badge-kategori ' + (p.kategori === '>50%' ? 'badge-hijau' : 'badge-kuning');

    const list = document.getElementById('edit-status-tahapan');
    list.innerHTML = '';

    Object.entries(TAHAPAN_LABELS).forEach(([key, label], idx) => {
        const b = badges[key];
        const selesai = b.warna === 'hijau';
        const row = document.createElement('button');
        row.type = 'button';
        row.className = 'tahap-quick-row' + (selesai ? ' tahap-quick-done' : '');
        row.style.cssText = 'width:100%; text-align:left; cursor:pointer; background:none; border:none; padding:0;';
        row.onclick = () => bukaRiwayat(p.id, key, label);
        row.innerHTML = `
            <div class="tahap-quick-header">
                <span class="tahap-quick-checkbox ${selesai ? 'checked' : ''}"></span>
                <span class="tahap-quick-nomor">${idx + 1}.</span>
                <span class="tahap-quick-label">${label}</span>
                <span class="badge-tahap-mini" style="margin-left:auto; font-size:11px; color:#64748b;">${b.label}</span>
            </div>
        `;
        list.appendChild(row);
    });

    if (window.lucide) lucide.createIcons();

    const modalEditEl = document.getElementById('modal-edit');
    if (!modalEditEl.open) {
        modalEditEl.showModal();
    }
};
</script>