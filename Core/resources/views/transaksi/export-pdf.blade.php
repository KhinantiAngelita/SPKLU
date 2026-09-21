<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1E293B; }
        h1 { font-size: 16px; margin-bottom: 2px; }
        p.period { color: #64748B; margin-top: 0; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #023E8A; color: #fff; text-align: left; padding: 6px 8px; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background: #f8fafc; }
    </style>
</head>
<body>
    <h1>Rekap Transaksi SPKLU</h1>
    <p class="period">Periode: {{ $mulai->translatedFormat('d F Y') }} – {{ $sampai->translatedFormat('d F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>SPKLU</th>
                <th>Jumlah Transaksi</th>
                <th>Energi (kWh)</th>
                <th>Pendapatan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rincian as $row)
                <tr>
                    <td>{{ $row->tanggal->translatedFormat('d F Y') }}</td>
                    <td>{{ $row->spklu->nama ?? '—' }}</td>
                    <td>{{ number_format($row->jumlah_transaksi) }}</td>
                    <td>{{ number_format($row->energi_kwh, 1) }}</td>
                    <td>{{ number_format($row->pendapatan_rp, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>