<dialog id="modal-riwayat" class="modal">
    <div class="modal-header-blue">
        <div>
            <h2 id="riwayat-judul">Riwayat Tahap</h2>
            <p id="riwayat-subjudul"></p>
        </div>
        <button type="button" class="modal-close" onclick="document.getElementById('modal-riwayat').close()">X</button>
    </div>

    <div class="modal-body">

        {{-- ============ RIWAYAT TERAKHIR ============ --}}
        <div id="riwayat-terakhir-wrap">
            <h4 class="riwayat-label">Kunjungan Terakhir</h4>
            <div id="riwayat-terakhir"></div>

            <a href="#" id="link-riwayat-lengkap" class="btn btn-primary btn-block">
                Lihat Semua Riwayat (<span id="riwayat-total">0</span>)
            </a>
        </div>

        {{-- ============ FORM TAMBAH KUNJUNGAN ============ --}}
        <div id="riwayat-error" style="display:none; margin-bottom:12px; padding:10px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#b91c1c; font-size:13px;"></div>
        <h3>Tambah Kunjungan Baru</h3>
        <form id="form-tambah-riwayat" method="POST">
            @csrf
            <input type="hidden" name="tahap" id="rt-tahap">

            <div class="form-row">
                <div>
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" required>
                </div>
                <div>
                    <label>Petugas/PIC</label>
                    <input type="text" name="petugas_pic">
                </div>
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

function buatKartuRiwayat(item, probabilitasId) {
    const csrf = document.querySelector('meta[name=csrf-token]')?.content ?? '';
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
            <input type="hidden" name="_token" value="${csrf}">
            <button type="submit" class="btn-icon-sm" title="Hapus entri">🗑️</button>
        </form>
    `;
    return card;
}

window.isiModalRiwayat = function (probabilitasId, tahapKey, tahapLabel, data) {
    const terakhirBox = document.getElementById('riwayat-terakhir');
    const totalSpan    = document.getElementById('riwayat-total');
    const linkLengkap  = document.getElementById('link-riwayat-lengkap');
    const errorBox     = document.getElementById('riwayat-error');

    errorBox.style.display = 'none';
    errorBox.textContent = '';

    document.getElementById('riwayat-judul').textContent = `Riwayat — ${tahapLabel}`;
    document.getElementById('riwayat-subjudul').textContent = `${data.riwayat.length} kunjungan tercatat`;
    document.getElementById('rt-tahap').value = tahapKey;
    document.getElementById('form-tambah-riwayat').action = `/monitoring/probabilitas/${probabilitasId}/tahapan`;
    document.getElementById('form-tambah-riwayat').reset();
    document.getElementById('rt-tahap').value = tahapKey;

    terakhirBox.innerHTML = '';
    totalSpan.textContent = data.riwayat.length;
    linkLengkap.href = `/monitoring/probabilitas/${probabilitasId}/tahapan/${tahapKey}/riwayat`;

    if (data.riwayat.length === 0) {
        terakhirBox.innerHTML = '<p class="empty-state">Belum ada kunjungan untuk tahap ini.</p>';
        linkLengkap.style.display = 'none';
    } else {
        linkLengkap.style.display = 'block';
        const riwayatUrut = [...data.riwayat].sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));
        terakhirBox.appendChild(buatKartuRiwayat(riwayatUrut[0], probabilitasId));
    }

    document.getElementById('modal-riwayat').showModal();
};

document.getElementById('form-tambah-riwayat').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const errorBox = document.getElementById('riwayat-error');
    const submitBtn = form.querySelector('button[type="submit"]');

    errorBox.style.display = 'none';
    errorBox.textContent = '';
    submitBtn.disabled = true;

    const formData = new FormData(form);
    const csrf = document.querySelector('meta[name=csrf-token]')?.content ?? '';

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf,
        },
        body: formData,
    })
        .then(async (res) => {
            const data = await res.json();

            if (!res.ok) {
                const pesan = data.errors
                    ? Object.values(data.errors).flat().join(' ')
                    : (data.message || 'Terjadi kesalahan, silakan coba lagi.');

                errorBox.textContent = pesan;
                errorBox.style.display = 'block';
                return;
            }

            window.location.reload();
        })
        .catch(() => {
            errorBox.textContent = 'Gagal menghubungi server. Coba lagi.';
            errorBox.style.display = 'block';
        })
        .finally(() => {
            submitBtn.disabled = false;
        });
});
</script>