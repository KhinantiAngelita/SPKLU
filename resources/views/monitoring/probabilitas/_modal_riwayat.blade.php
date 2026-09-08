<dialog id="modal-riwayat" class="modal">
    <div class="modal-header-blue">
        <div>
            <h2 id="riwayat-judul">Riwayat Tahap</h2>
            <p id="riwayat-subjudul"></p>
        </div>
        <button type="button" class="modal-close" onclick="document.getElementById('modal-riwayat').close()">X</button>
    </div>

    <div class="modal-body">
        <div id="riwayat-list" class="riwayat-list">
            {{-- diisi via JS: satu kartu per kunjungan --}}
        </div>

        <h3>Tambah Kunjungan Baru</h3>
        <form id="form-tambah-riwayat" method="POST">
            @csrf
            <input type="hidden" name="tahap" id="rt-tahap">

            <div class="form-row">
                <div><label>Tanggal</label><input type="date" name="tanggal" required></div>
                <div><label>Petugas/PIC</label><input type="text" name="petugas_pic"></div>
            </div>

            <label>Hasil</label>
            <select name="hasil" required>
                <option value="berhasil">Berhasil</option>
                <option value="perlu_kunjungan_ulang">Perlu Kunjungan Ulang</option>
                <option value="gagal">Gagal</option>
            </select>

            <label>Catatan</label>
            <textarea name="catatan" rows="2" placeholder="Opsional"></textarea>

            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Tambah Kunjungan</button>
            </div>
        </form>
    </div>
</dialog>

<script>
const HASIL_LABEL = {
    berhasil: 'Berhasil',
    perlu_kunjungan_ulang: 'Perlu Kunjungan Ulang',
    gagal: 'Gagal',
};
const HASIL_WARNA = {
    berhasil: 'hijau',
    perlu_kunjungan_ulang: 'kuning',
    gagal: 'merah',
};

window.isiModalRiwayat = function (probabilitasId, tahapKey, tahapLabel, data) {
    document.getElementById('riwayat-judul').textContent = `Riwayat — ${tahapLabel}`;
    document.getElementById('riwayat-subjudul').textContent = `${data.riwayat.length} kunjungan tercatat`;
    document.getElementById('rt-tahap').value = tahapKey;
    document.getElementById('form-tambah-riwayat').action = `/monitoring/probabilitas/${probabilitasId}/tahapan`;

    const list = document.getElementById('riwayat-list');
    list.innerHTML = '';

    if (data.riwayat.length === 0) {
        list.innerHTML = '<p class="empty-state">Belum ada kunjungan untuk tahap ini.</p>';
    }

    data.riwayat.forEach(item => {
        const card = document.createElement('div');
        card.className = 'riwayat-card';
        card.innerHTML = `
            <div class="riwayat-card-header">
                <span class="badge-tahap badge-${HASIL_WARNA[item.hasil]}">${HASIL_LABEL[item.hasil]}</span>
                <span class="riwayat-tanggal">${item.tanggal}</span>
            </div>
            <div class="riwayat-pic">${item.petugas_pic ?? '—'}</div>
            ${item.catatan ? `<div class="riwayat-catatan">${item.catatan}</div>` : ''}
            <form method="POST" action="/monitoring/probabilitas/${probabilitasId}/tahapan/${item.id}" class="riwayat-hapus">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]')?.content ?? ''}">
                <button type="submit" class="btn-icon-sm" title="Hapus entri">🗑️</button>
            </form>
        `;
        list.appendChild(card);
    });

    document.getElementById('modal-riwayat').showModal();
};
</script>