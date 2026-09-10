<div class="pgj-card">
    @if ($showValidasi)
        @if ($p->sudahDivalidasi())
            <span class="pgj-card-validasi pgj-card-validasi-done">✓ Tervalidasi</span>
        @else
            <button type="button" class="pgj-card-validasi"
                    onclick="bukaModalValidasi({{ $p->id }}, '{{ addslashes($p->lokasi) }}', '{{ addslashes($p->ulp) }}', {{ $p->estimasiTotalKw() }}, {{ $p->estimasiNozzle() }})">
                Validasi
            </button>
        @endif
    @endif

    <p class="pgj-card-title">{{ $p->lokasi }}</p>
    <p class="pgj-card-ulp">ULP {{ $p->ulp }}</p>

    <div class="pgj-card-tahap">
        <span class="pgj-card-tahap-dot"></span>
        Tahap: {{ $p->tahapSaatIni() }}
    </div>

    <p class="pgj-card-update">Update: {{ $p->tanggalUpdateTerakhir()->translatedFormat('d F Y') }}</p>
</div>