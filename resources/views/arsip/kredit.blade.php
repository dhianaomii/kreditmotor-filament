<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Kredit</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #4B49AC; color: white; }
        img { width: 100px; height: 100px; }
        .badge-success { background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px; }
        .badge-warning { background-color: #ffc107; color: black; padding: 4px 8px; border-radius: 4px; }
        .badge-danger { background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>Data Kredit</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Nama Pelanggan</th>
                <th>Metode Pembayaran</th>
                <th>Tanggal Mulai Kredit</th>
                <th>Tanggal Selesai Kredit</th>
                <th>Keterangan Kredit</th>
                <th>Bukti Bayar DP</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kredit as $k)
            <tr>
                <td>
                    @if($k->status_kredit == 'Macet')
                    <span class="badge-danger">Macet</span>
                    @elseif($k->status_kredit == 'Dicicil')
                    <span class="badge-warning">Dicicil</span>
                    @else
                    <span class="badge-success">Lunas</span>
                    @endif
                </td>
                <td>{{ $k->PengajuanKredit->Pelanggan->nama_pelanggan }}</td>
                <td>{{ $k->MetodePembayaran->metode_pembayaran }}</td>
                <td>{{ $k->tgl_mulai_kredit }}</td>
                <td>{{ $k->tgl_selesai_kredit }}</td>
                <td>{{ $k->keterangan_status_kredit }}</td>
                <td>
                    <img src="{{ public_path('storage/' . $k->url_bukti_bayar) }}" alt="Bukti Bayar">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>