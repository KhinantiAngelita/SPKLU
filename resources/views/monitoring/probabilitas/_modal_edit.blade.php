<dialog id="modal-edit" class="modal modal-lg">
    <form id="form-edit" method="POST">
        @csrf
        @method('PUT')

        {{-- Identitas TIDAK ditampilkan sebagai input di modal ini (sesuai desain),
             tapi tetap dikirim sebagai hidden field supaya tidak ke-null-kan saat update. --}}
        <input type="hidden" name="lokasi" id="f-lokasi">
        <input type="hidden" name="ulp" id="f-ulp">
        <input type="hidden" name="tikor_lat" id="f-lat">
        <input type="hidden" name="tikor_lng" id="f-lng">
        <input type="hidden" name="skema" id="f-skema">

        <div class="modal-header-blue">
            <div>
                <h2 id="edit-judul">SPKLU</h2>
                <p id="edit-subjudul">TIKOR : — . ULP —</p>
            </div>
            <button type="button" class="modal-close" onclick="document.getElementById('modal-edit').close()">X</button>
        </div>

        <div class="modal-body">

            <div class="edit-card">
                <h3>Kebutuhan Mesin (unit)</h3>
                <div class="form-row form-row-6">
                    <div><label>22kW</label><input type="number" min="0" name="kebutuhan_22kw" id="f-22kw"></div>
                    <div><label>30kW</label><input type="number" min="0" name="kebutuhan_30kw" id="f-30kw"></div>
                    <div><label>50kW</label><input type="number" min="0" name="kebutuhan_50kw" id="f-50kw"></div>
                    <div><label>60kW</label><input type="number" min="0" name="kebutuhan_60kw" id="f-60kw"></div>
                    <div><label>120kW</label><input type="number" min="0" name="kebutuhan_120kw" id="f-120kw"></div>
                    <div><label>180kW</label><input type="number" min="0" name="kebutuhan_180kw" id="f-180kw"></div>
                </div>

                <div class="form-row">
                    <div><label>Mitra Mesin</label><input type="text" name="mitra_mesin" id="f-mitra-mesin"></div>
                    <div><label>Poin Perluasan Jaringan</label><input type="number" min="0" name="poin_perluasan_jaringan" id="f-poin-jaringan"></div>
                </div>
            </div>

            <div class="edit-card">
                <h3>Poin Fasilitas <span class="hint">(0 = tidak ada, 1 = ada)</span></h3>
                <div class="form-row form-row-4">
                    <div>
                        <label>Ruang Tunggu</label>
                        <select name="fasilitas_ruang_tunggu" id="f-fas-ruang-tunggu">
                            <option value="0">0</option><option value="1">1</option>
                        </select>
                    </div>
                    <div>
                        <label>Parkir</label>
                        <select name="fasilitas_parkir" id="f-fas-parkir">
                            <option value="0">0</option><option value="1">1</option>
                        </select>
                    </div>
                    <div>
                        <label>Toilet</label>
                        <select name="fasilitas_toilet" id="f-fas-toilet">
                            <option value="0">0</option><option value="1">1</option>
                        </select>
                    </div>
                    <div>
                        <label>Kafe</label>
                        <select name="fasilitas_kafe" id="f-fas-kafe">
                            <option value="0">0</option><option value="1">1</option>
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
                            <option value="0">0</option><option value="1">1</option>
                        </select>
                    </div>
                    <div>
                        <label>Pintu Tol</label>
                        <select name="okupansi_pintu_tol" id="f-oku-pintu-tol">
                            <option value="0">0</option><option value="1">1</option>
                        </select>
                    </div>
                    <div>
                        <label>Pusat Keramaian</label>
                        <select name="okupansi_pusat_keramaian" id="f-oku-pusat-keramaian">
                            <option value="0">0</option><option value="1">1</option>
                        </select>
                    </div>
                    <div>
                        <label>Ruas Jalan</label>
                        <select name="okupansi_ruas_jalan" id="f-oku-ruas-jalan">
                            <option value="0">0</option><option value="1">1</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="edit-card">
                <h3>Status Tahapan</h3>
                <p class="hint">Klik "Selesai" untuk mencatat kunjungan hari ini. Klik ikon status di tabel utama untuk lihat riwayat lengkap tiap tahap.</p>
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
let CURRENT_PROBABILITAS_ID = null;

window.isiModalEdit = function (data) {
    const p = data.probabilitas;
    const badges = data.badges;
    CURRENT_PROBABILITAS_ID = p.id;

    document.getElementById('form-edit').action = `/monitoring/probabilitas/${p.id}`;
    document.getElementById('edit-judul').textContent = p.lokasi;
    document.getElementById('edit-subjudul').textContent = `TIKOR : ${p.tikor_lat}, ${p.tikor_lng} . ULP ${p.ulp}`;

    document.getElementById('f-lokasi').value = p.lokasi;
    document.getElementById('f-ulp').value = p.ulp;
    document.getElementById('f-lat').value = p.tikor_lat;
    document.getElementById('f-lng').value = p.tikor_lng;
    document.getElementById('f-skema').value = p.skema ?? '';

    document.getElementById('f-22kw').value = p.kebutuhan_22kw;
    document.getElementById('f-30kw').value = p.kebutuhan_30kw;
    document.getElementById('f-50kw').value = p.kebutuhan_50kw;
    document.getElementById('f-60kw').value = p.kebutuhan_60kw;
    document.getElementById('f-120kw').value = p.kebutuhan_120kw;
    document.getElementById('f-180kw').value = p.kebutuhan_180kw;
    document.getElementById('f-mitra-mesin').value = p.mitra_mesin ?? '';
    document.getElementById('f-poin-jaringan').value = p.poin_perluasan_jaringan ?? '';
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
    const hariIni = new Date().toISOString().slice(0, 10);

    Object.entries(TAHAPAN_LABELS).forEach(([key, label], idx) => {
        const b = badges[key];
        const selesai = b.warna === 'hijau';
        const row = document.createElement('div');
        row.className = 'tahap-quick-row' + (selesai ? ' tahap-quick-done' : '');
        row.innerHTML = `
            <div class="tahap-quick-header">
                <span class="tahap-quick-checkbox ${selesai ? 'checked' : ''}"></span>
                <span class="tahap-quick-nomor">${idx + 1}.</span>
                <span class="tahap-quick-label">${label}</span>
                <button type="button" class="btn-selesai" onclick="tambahKunjunganCepat('${key}', this)">
                    ${selesai ? 'Selesai' : 'Tandai Selesai'}
                </button>
            </div>
            <div class="tahap-quick-body">
                <div>
                    <label>Tanggal</label>
                    <input type="date" class="tq-tanggal" value="${hariIni}">
                </div>
                <div>
                    <label>Catatan</label>
                    <input type="text" class="tq-catatan" placeholder="Opsional">
                </div>
            </div>
        `;
        list.appendChild(row);
    });

    if (window.lucide) lucide.createIcons();

    document.getElementById('modal-edit').showModal();
};

function tambahKunjunganCepat(tahapKey, btn) {
    const row = btn.closest('.tahap-quick-row');
    const tanggal = row.querySelector('.tq-tanggal').value;
    const catatan = row.querySelector('.tq-catatan').value;

    fetch(`/monitoring/probabilitas/${CURRENT_PROBABILITAS_ID}/tahapan`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ tahap: tahapKey, tanggal, hasil: 'berhasil', catatan }),
    })
    .then(r => r.json())
    .then(() => bukaEdit(CURRENT_PROBABILITAS_ID)) // reload data biar badge & progres update
    .catch(err => alert('Gagal menyimpan kunjungan: ' + err));
}
</script>