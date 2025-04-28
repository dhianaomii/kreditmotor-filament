<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kredit</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Laporan Data Kredit</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Pelanggan</th>
                <th>Metode Pembayaran</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Status Kredit</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kredits as $kredit)
                <tr>
                    <td>{{ $kredit->pengajuanKredit->pelanggan->nama_pelanggan }}</td>
                    <td>{{ $kredit->MetodePembayaran->metode_pembayaran }}</td>
                    <td>{{ $kredit->tgl_mulai_kredit }}</td>
                    <td>{{ $kredit->tgl_selesai_kredit }}</td>
                    <td>{{ $kredit->status_kredit }}</td>
                    <td>{{ $kredit->keterangan_status_kredit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>