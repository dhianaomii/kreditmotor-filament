<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Kredit</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 10px;
            font-size: 10px; /* Smaller font to fit more data */
        }
        table { 
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: auto;
        }
        tr { 
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td { 
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }
        th { 
            background-color: #4B49AC;
            color: white;
            font-weight: normal;
        }
        img { 
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .badge-success { 
            background-color: #28a745;
            color: white;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 9px;
            display: inline-block;
        }
        .badge-warning { 
            background-color: #ffc107;
            color: black;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 9px;
            display: inline-block;
        }
        .badge-danger { 
            background-color: #dc3545;
            color: white;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 9px;
            display: inline-block;
        }
        h2 {
            margin-bottom: 10px;
            font-size: 14px;
        }
        /* Format currency values */
        .currency {
            text-align: right;
        }
        /* Support for landscape orientation */
        @page {
            size: landscape;
        }
        @media print {
            th { 
                background-color: #4B49AC !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
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
            @forelse ($kredit as $k)
            <tr>
                <td>{{ $k->status_kredit }}</td>
                <td>{{ $k->PengajuanKredit->Pelanggan->nama_pelanggan }}</td>
                <td>{{ $k->MetodePembayaran->metode_pembayaran }}</td>
                <td>{{ $k->tgl_mulai_kredit }}</td>
                <td>{{ $k->tgl_selesai_kredit }}</td>
                <td>{{ $k->keterangan_status_kredit }}</td>
                <td>
                    @if ($k->url_bukti_bayar)
                        <img src="{{ public_path('storage/' . $k->url_bukti_bayar) }}" alt="Bukti Bayar DP">
                    @else
                        Tidak ada bukti
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">Tidak ada data kredit untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>