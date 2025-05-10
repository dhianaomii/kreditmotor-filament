<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan</title>
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
    <h2>Data Pelanggan</h2>
    <table>
        <thead>
            <tr>
                <th width="5%">Status</th>
                <th width="12%">Nama Pelanggan</th>
                <th width="15%">Email</th>
                <th width="8%">No HP</th>
                <th width="15%">Alamat 1</th>
                <th width="10%">Alamat 2</th>
                <th width="10%">Alamat 3</th>
                <th width="10%">Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelanggan as $p)
            <tr>
                <td>
                    @if($p->is_blocked == 1)
                    <span class="badge badge-danger">Bermasalah</span>
                    @else
                    <span class="badge badge-success">Aktif</span>
                    @endif
                </td>
                <td>{{ $p->nama_pelanggan }}</td>
                <td>{{ $p->email }}</td>
                <td>{{ $p->no_hp }}</td>
                <td>{{ $p->alamat1 }}, {{ $p->kota1 }}, {{ $p->provinsi1 }}, {{ $p->kode_pos1 }}</td>
                <td>{{ $p->alamat2 }}, {{ $p->kota2 }}, {{ $p->provinsi2 }}, {{ $p->kode_pos2 }}</td>
                <td>{{ $p->alamat3 }}, {{ $p->kota3 }}, {{ $p->provinsi3 }}, {{ $p->kode_pos3 }}</td>
                <td>
                    @if ($p->foto)
                        <img src="{{ public_path('storage/' . $p->foto) }}" alt="Foto Pelanggan">
                    @else
                        Tidak ada foto
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>