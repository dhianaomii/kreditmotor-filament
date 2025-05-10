<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Angsuran</title>
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
            @forelse ($angsuran as $a)
            <tr>
                <td>{{ $a->Kredit->PengajuanKredit->Pelanggan->nama_pelanggan }}</td>
                <td>{{ $a->tgl_bayar }}</td>
                <td>{{ $a->angsuran_ke }}</td>
                <td>{{ $a->total_bayar }}</td>
                <td>{{ $a->keterangan }} Bulan</td>
                <td>
                    @if ($a->bukti_angsuran)
                        <img src="{{ public_path('storage/' . $a->bukti_angsuran) }}" alt="Bukti Bayar">
                    @else
                        Tidak ada bukti
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">Tidak ada data angsuran untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>