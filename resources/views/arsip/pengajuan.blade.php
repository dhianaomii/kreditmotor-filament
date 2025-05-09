<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Pengajuan Kredit</title>
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
    <h2>Data Pengajuan Kredit</h2>
    <table>
        <thead>
            <tr>
                <th width="10%">Status Pengajuan</th>
                <th width="8%">Tanggal</th>
                <th width="12%">Nama Pelanggan</th>
                <th width="12%">Motor</th>
                <th width="9%">Harga Kredit</th>
                <th width="8%">DP</th>
                <th width="8%">Lama Cicilan</th>
                <th width="9%">Asuransi</th>
                <th width="9%">Cicilan/Bulan</th>
                <th width="8%">Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuan as $i)
            <tr>
                <td>
                    @if($i->status_pengajuan == 'Bermasalah' || $i->status_pengajuan == 'Dibatalkan Pembeli' || $i->status_pengajuan == 'Dibatalkan Penjual')
                    <span class="badge-danger">{{ $i->status_pengajuan }}</span>
                    @elseif($i->status_pengajuan == 'Diterima' || $i->status_pengajuan == 'Selesai')
                    <span class="badge-success">{{ $i->status_pengajuan }}</span>
                    @else
                    <span class="badge-warning">{{ $i->status_pengajuan }}</span>
                    @endif
                </td>
                <td>{{ date('d/m/Y', strtotime($i->tgl_pengajuan_kredit)) }}</td>
                <td>{{ $i->Pelanggan->nama_pelanggan }}</td>
                <td>{{ $i->Motor->nama_motor }}</td>
                <td class="currency">Rp {{ number_format($i->harga_kredit, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($i->dp, 0, ',', '.') }}</td>
                <td>{{ $i->JenisCicilan->lama_cicilan }} Bulan</td>
                <td class="currency">Rp {{ number_format($i->biaya_asuransi_perbulan, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($i->cicilan_perbulan, 0, ',', '.') }}</td>
                <td>
                    @if(file_exists(public_path('storage/' . $i->url_foto)))
                    <img src="{{ public_path('storage/' . $i->url_foto) }}" alt="Foto">
                    @else
                    <span>Tidak Ada Gambar</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table