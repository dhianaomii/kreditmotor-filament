<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Angsuran</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #4B49AC; color: white; }
        img { width: 100px; height: 100px; }
    </style>
</head>
<body>
    <h2>Data Angsuran</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Pelanggan</th>
                <th>Tanggal Bayar</th>
                <th>Angsuran Ke</th>
                <th>Total Bayar</th>
                <th>Keterangan</th>
                <th>Bukti Angsuran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($angsuran as $a)
            <tr>
                <td>{{ $a->Kredit->PengajuanKredit->Pelanggan->nama_pelanggan }}</td>
                <td>{{ $a->tgl_bayar }}</td>
                <td>{{ $a->angsuran_ke }}</td>
                <td>{{ $a->total_bayar }}</td>
                <td>{{ $a->keterangan }} Bulan</td>
                <td>
                    <img src="{{ public_path('storage/' . $a->bukti_angsuran) }}" alt="Bukti Bayar">
                </td>
            @endforeach
        </tbody>
    </table>
</body>
</html>