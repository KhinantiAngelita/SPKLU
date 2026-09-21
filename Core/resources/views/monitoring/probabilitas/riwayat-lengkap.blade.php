@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/probabilitas.css') }}">
@endpush

@section('content')
<div class="riwayat-page">
    <div class="riwayat-page-card">

        <div class="riwayat-page-header">
            <a href="{{ url()->previous() }}" class="btn-back" title="Kembali">
                <span class="btn-back-icon">&larr;</span>
            </a>

            <div class="riwayat-page-title">
                <p class="riwayat-eyebrow">Riwayat Tahap</p>
                <h1>{{ $tahapLabel }}</h1>
                <p class="riwayat-page-sub">
                    {{ $probabilitas->lokasi ?? '—' }}
                </p>
            </div>
        </div>

        @php
            $totalBerhasil = $riwayat->where('hasil', 'berhasil')->count();
            $totalUlang    = $riwayat->where('hasil', 'perlu_kunjungan_ulang')->count();
            $totalGagal    = $riwayat->where('hasil', 'gagal')->count();
        @endphp

        <div class="riwayat-stats">
            <div class="riwayat-stat">
                <span class="riwayat-stat-number">{{ $riwayat->count() }}</span>
                <span class="riwayat-stat-label">Total</span>
            </div>
            <div class="riwayat-stat-divider"></div>
            <div class="riwayat-stat">
                <span class="riwayat-stat-number stat-hijau">{{ $totalBerhasil }}</span>
                <span class="riwayat-stat-label">Berhasil</span>
            </div>
            <div class="riwayat-stat-divider"></div>
            <div class="riwayat-stat">
                <span class="riwayat-stat-number stat-kuning">{{ $totalUlang }}</span>
                <span class="riwayat-stat-label">Perlu Ulang</span>
            </div>
            <div class="riwayat-stat-divider"></div>
            <div class="riwayat-stat">
                <span class="riwayat-stat-number stat-merah">{{ $totalGagal }}</span>
                <span class="riwayat-stat-label">Gagal</span>
            </div>
        </div>

        <div class="riwayat-page-body">
            @forelse ($riwayat as $item)
                @php
                    $labelHasil = [
                        'berhasil' => 'Berhasil',
                        'perlu_kunjungan_ulang' => 'Perlu Kunjungan Ulang',
                        'gagal' => 'Gagal',
                    ][$item->hasil] ?? $item->hasil;

                    $warnaHasil = [
                        'berhasil' => 'hijau',
                        'perlu_kunjungan_ulang' => 'kuning',
                        'gagal' => 'merah',
                    ][$item->hasil] ?? '';

                    $tanggal = \Carbon\Carbon::parse($item->tanggal);
                @endphp

                <div class="riwayat-item">
                    <div class="riwayat-item-rail">
                        <div class="riwayat-item-marker badge-{{ $warnaHasil }}"></div>
                    </div>

                    <div class="riwayat-card">
                        <div class="riwayat-card-top">
                            <div class="riwayat-card-top-left">
                                <span class="badge-tahap badge-{{ $warnaHasil }}">{{ $labelHasil }}</span>
                                <span class="riwayat-tanggal">
                                    {{ $tanggal->translatedFormat('d F Y') }}
                                    <span class="riwayat-tanggal-relatif">· {{ $tanggal->diffForHumans() }}</span>
                                </span>
                            </div>

                            <button type="button"
                                    class="btn-icon-sm"
                                    title="Hapus entri"
                                    onclick="bukaModalHapus(
                                        '{{ route('monitoring.probabilitas.tahapan.destroy', [$probabilitas, $item->id]) }}',
                                        '{{ $tanggal->translatedFormat('d F Y') }}'
                                    )">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </div>

                        <div class="riwayat-card-body">
                            <div class="riwayat-pic">
                                <span class="riwayat-pic-avatar">{{ strtoupper(substr($item->petugas_pic ?? '?', 0, 1)) }}</span>
                                <span>{{ $item->petugas_pic ?? 'Tidak ada PIC tercatat' }}</span>
                            </div>

                            @if ($item->catatan)
                                <div class="riwayat-catatan">{{ $item->catatan }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="riwayat-empty">
                    <div class="riwayat-empty-icon">📋</div>
                    <p>Belum ada kunjungan untuk tahap ini.</p>
                    <span class="riwayat-empty-sub">Kunjungan baru akan muncul di sini setelah dicatat.</span>
                </div>
            @endforelse
        </div>

    </div>
</div>

{{-- ============ MODAL KONFIRMASI HAPUS ============ --}}
<dialog id="modal-hapus-riwayat" class="modal-hapus">
    <div class="modal-hapus-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 6h18"></path>
            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
            <line x1="10" y1="11" x2="10" y2="17"></line>
            <line x1="14" y1="11" x2="14" y2="17"></line>
        </svg>
    </div>

    <h3>Hapus entri kunjungan?</h3>
    <p id="modal-hapus-desc">
        Kunjungan tanggal <strong id="modal-hapus-tanggal"></strong> akan dihapus permanen dan tidak bisa dikembalikan.
    </p>

    <div class="modal-hapus-actions">
        <button type="button" class="btn-batal" onclick="document.getElementById('modal-hapus-riwayat').close()">
            Batal
        </button>
        <form id="form-hapus-riwayat" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-hapus-confirm">Ya, Hapus</button>
        </form>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
function bukaModalHapus(actionUrl, tanggalLabel) {
    document.getElementById('form-hapus-riwayat').action = actionUrl;
    document.getElementById('modal-hapus-tanggal').textContent = tanggalLabel;
    document.getElementById('modal-hapus-riwayat').showModal();
}
</script>
@endpush

@push('styles')
<style>
    :root {
        --riwayat-border: #e5e7eb;
        --riwayat-text-muted: #6b7280;
        --riwayat-blue: #2563eb;
        --riwayat-hijau: #16a34a;
        --riwayat-hijau-bg: #dcfce7;
        --riwayat-kuning: #b45309;
        --riwayat-kuning-bg: #fef3c7;
        --riwayat-merah: #dc2626;
        --riwayat-merah-bg: #fee2e2;
    }

    .riwayat-page {
        max-width: 860px;
        margin: 24px auto 60px;
        padding: 0 16px;
    }

    /* ---------- Outer card wrapper ---------- */
    .riwayat-page-card {
        background: #fff;
        border: 1px solid var(--riwayat-border);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 28px 32px 32px;
    }

    /* ---------- Header ---------- */
    .riwayat-page-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 22px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid var(--riwayat-border);
        color: #374151;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s, transform 0.15s;
    }
    .btn-back-icon {
        font-size: 17px;
        line-height: 1;
    }
    .btn-back:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
        transform: translateX(-2px);
    }

    .riwayat-page-title {
        flex: 1;
        min-width: 0;
    }
    .riwayat-eyebrow {
        font-size: 11px;
        font-weight: 600;
        color: var(--riwayat-blue);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin: 0 0 3px;
    }
    .riwayat-page-title h1 {
        font-size: 21px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 3px;
        letter-spacing: -0.01em;
        text-transform: capitalize;
    }
    .riwayat-page-sub {
        font-size: 13px;
        color: var(--riwayat-text-muted);
        margin: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ---------- Stats bar ---------- */
    .riwayat-stats {
        display: flex;
        align-items: center;
        background: #f9fafb;
        border: 1px solid var(--riwayat-border);
        border-radius: 12px;
        padding: 14px 8px;
        margin-bottom: 26px;
    }
    .riwayat-stat {
        flex: 1;
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .riwayat-stat-number {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        line-height: 1;
    }
    .riwayat-stat-number.stat-hijau  { color: var(--riwayat-hijau); }
    .riwayat-stat-number.stat-kuning { color: var(--riwayat-kuning); }
    .riwayat-stat-number.stat-merah  { color: var(--riwayat-merah); }
    .riwayat-stat-label {
        font-size: 10.5px;
        color: var(--riwayat-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .riwayat-stat-divider {
        width: 1px;
        height: 30px;
        background: var(--riwayat-border);
    }

    /* ---------- Timeline list ---------- */
    .riwayat-page-body {
        display: flex;
        flex-direction: column;
    }

    .riwayat-item {
        display: flex;
        gap: 14px;
    }
    .riwayat-item:not(:last-child) { padding-bottom: 16px; }

    .riwayat-item-rail {
        position: relative;
        width: 12px;
        flex-shrink: 0;
        display: flex;
        justify-content: center;
    }
    .riwayat-item:not(:last-child) .riwayat-item-rail::after {
        content: '';
        position: absolute;
        top: 20px;
        bottom: -16px;
        width: 2px;
        background: var(--riwayat-border);
    }
    .riwayat-item-marker {
        width: 11px;
        height: 11px;
        border-radius: 50%;
        margin-top: 9px;
        background: #9ca3af;
        box-shadow: 0 0 0 3px #fff;
    }
    .riwayat-item-marker.badge-hijau  { background: var(--riwayat-hijau); }
    .riwayat-item-marker.badge-kuning { background: var(--riwayat-kuning); }
    .riwayat-item-marker.badge-merah  { background: var(--riwayat-merah); }

    .riwayat-card {
        flex: 1;
        min-width: 0;
        background: #fff;
        border: 1px solid var(--riwayat-border);
        border-radius: 12px;
        padding: 14px 16px;
        transition: box-shadow 0.15s, border-color 0.15s, transform 0.15s;
    }
    .riwayat-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transform: translateY(-1px);
    }

    .riwayat-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }
    .riwayat-card-top-left {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .badge-tahap {
        display: inline-block;
        width: fit-content;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
    }
    .badge-tahap.badge-hijau  { background: var(--riwayat-hijau-bg); color: var(--riwayat-hijau); }
    .badge-tahap.badge-kuning { background: var(--riwayat-kuning-bg); color: var(--riwayat-kuning); }
    .badge-tahap.badge-merah  { background: var(--riwayat-merah-bg); color: var(--riwayat-merah); }

    .riwayat-tanggal {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
    }
    .riwayat-tanggal-relatif {
        font-weight: 400;
        color: var(--riwayat-text-muted);
    }

    .riwayat-card-body {
        font-size: 13.5px;
        color: #4b5563;
    }
    .riwayat-pic {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }
    .riwayat-pic-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #eef2ff;
        color: #4338ca;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .riwayat-catatan {
        background: #f9fafb;
        border-left: 3px solid #d1d5db;
        padding: 9px 12px;
        border-radius: 6px;
        line-height: 1.55;
        white-space: pre-line;
        color: #374151;
    }

    /* ---------- Tombol hapus (ikon) ---------- */
    .btn-icon-sm {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 6px;
        border-radius: 7px;
        color: #9ca3af;
        flex-shrink: 0;
        transition: color 0.15s, background 0.15s;
    }
    .btn-icon-sm:hover {
        color: var(--riwayat-merah);
        background: var(--riwayat-merah-bg);
    }

    /* ---------- Empty state ---------- */
    .riwayat-empty {
        text-align: center;
        padding: 70px 20px;
        color: #9ca3af;
    }
    .riwayat-empty-icon {
        font-size: 34px;
        margin-bottom: 10px;
        opacity: 0.8;
    }
    .riwayat-empty p {
        font-size: 14.5px;
        font-weight: 600;
        color: #6b7280;
        margin: 0 0 4px;
    }
    .riwayat-empty-sub {
        font-size: 12.5px;
    }

    /* ---------- Modal konfirmasi hapus ---------- */
    .modal-hapus {
        border: none;
        border-radius: 16px;
        padding: 28px 26px 24px;
        width: 100%;
        max-width: 360px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        text-align: center;
    }
    .modal-hapus::backdrop {
        background: rgba(17, 24, 39, 0.5);
        backdrop-filter: blur(1px);
    }

    .modal-hapus-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: var(--riwayat-merah-bg);
        color: var(--riwayat-merah);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-hapus h3 {
        font-size: 16.5px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px;
    }
    .modal-hapus p {
        font-size: 13.5px;
        color: var(--riwayat-text-muted);
        line-height: 1.55;
        margin: 0 0 22px;
    }
    .modal-hapus p strong {
        color: #374151;
    }

    .modal-hapus-actions {
        display: flex;
        gap: 10px;
    }
    .modal-hapus-actions form {
        flex: 1;
    }

    .btn-batal,
    .btn-hapus-confirm {
        width: 100%;
        padding: 10px 0;
        border-radius: 9px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s;
    }
    .btn-batal {
        flex: 1;
        background: #fff;
        border: 1px solid var(--riwayat-border);
        color: #374151;
    }
    .btn-batal:hover {
        background: #f3f4f6;
    }
    .btn-hapus-confirm {
        border: none;
        background: var(--riwayat-merah);
        color: #fff;
    }
    .btn-hapus-confirm:hover {
        background: #b91c1c;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 600px) {
        .riwayat-page-card { padding: 20px 18px 24px; }
        .riwayat-stat-label { font-size: 9.5px; }
        .riwayat-stat-number { font-size: 16px; }
        .riwayat-tanggal-relatif { display: block; }
        .modal-hapus { margin: auto 16px; }
    }
</style>
@endpush