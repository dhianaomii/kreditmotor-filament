<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Pengiriman</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 10px;
            font-size: 10px; /* Smaller font size */
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
        h2 {
            margin-bottom: 10px;
            font-size: 14px;
        }
        /* Using landscape orientation */
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
                <th width="9%">Status Kirim</th>
                <th width="10%">No Invoice</th>
                <th width="14%">Nama Pelanggan</th>
                <th width="12%">Nama Kurir</th>
                <th width="10%">No HP</th>
                <th width="10%">Tanggal Pengiriman</th>
                <th width="10%">Tanggal Tiba</th>
                <th width="15%">Keterangan</th>
                <th width="10%">Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengiriman as $i)
            <tr>
                <td>
                    @if($i->status_kirim == 'Sedang Dikirim')
                    <span class="badge-warning">{{ $i->status_kirim }}</span>
                    @else
                    <span class="badge-success">{{ $i->status_kirim }}</span>
                    @endif
                </td>
                <td>{{ $i->no_invoice }}</td>
                <td>{{ $i->Kredit->PengajuanKredit->Pelanggan->nama_pelanggan }}</td>
                <td>{{ $i->nama_kurir }}</td>
                <td>{{ $i->telpon_kurir }}</td>
                <td>{{ date('d/m/Y', strtotime($i->tgl_kirim)) }}</td>
                <td>
                    @if($i->tgl_tiba)
                    {{ date('d/m/Y', strtotime($i->tgl_tiba)) }}
                    @else
                    -
                    @endif
                </td>
                <td>{{ $i->keterangan ?: '-' }}</td>
                <td>
                    @if(file_exists(public_path('storage/' . $i->bukti_foto)))
                    <img src="{{ public_path('storage/' . $i->bukti_foto) }}" alt="Foto">
                    @else
                    <span>Tidak Ada Gambar</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>