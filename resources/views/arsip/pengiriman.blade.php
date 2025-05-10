<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Pengiriman</title>
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
    <h2>Data Pengiriman</h2>
    <table>
        <thead>
            <tr>
                <th>Status Kirim</th>
                <th>No Invoice</th>
                <th>Nama Pelanggan</th>
                <th>Nama Kurir</th>
                <th>No HP</th>
                <th>Tanggal Pengiriman</th>
                <th>Tanggal Tiba</th>
                <th>Keterangan</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengiriman as $i)
            <tr>
                <td>{{ $i->status_kirim }}</td>
                <td>{{ $i->no_invoice }}</td>
                <td>{{ $i->Kredit->PengajuanKredit->Pelanggan->nama_pelanggan }}</td>
                <td>{{ $i->nama_kurir }}</td>
                <td>{{ $i->telpon_kurir }}</td>
                <td>{{ $i->tgl_kirim }}</td>
                <td>{{ $i->tgl_tiba }}</td>
                <td>{{ $i->keterangan }}</td>
                <td>
                    @if ($i->bukti_foto)
                        <img src="{{ public_path('storage/' . $i->bukti_foto) }}" alt="Bukti Foto">
                    @else
                        Tidak ada foto
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">Tidak ada data pengiriman untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>