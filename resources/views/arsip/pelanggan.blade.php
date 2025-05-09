<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 10px;
            font-size: 11px; /* Reduced font size to fit more content */
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
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        th { 
            background-color: #4B49AC;
            color: white;
            font-weight: normal; /* Lighter headers */
        }
        img { 
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
        .badge-success { 
            background-color: #28a745;
            color: white;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 10px;
        }
        .badge-danger { 
            background-color: #dc3545;
            color: white;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 10px;
        }
        h2 {
            margin-bottom: 10px;
        }
        @media print {
            h2 { font-size: 14px; }
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
                <th width="15%">Alamat</th>
                <th width="10%">Kota</th>
                <th width="10%">Provinsi</th>
                <th width="7%">Kode Pos</th>
                <th width="10%">Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelanggan as $p)
            <tr>
                <td>
                    @if($p->is_blocked == 1)
                    <span class="badge-danger">x</span>
                    @else
                    <span class="badge-success">✓</span>
                    @endif
                </td>
                <td>{{ $p->nama_pelanggan }}</td>
                <td>{{ $p->email }}</td>
                <td>{{ $p->no_hp }}</td>
                <td>{{ $p->alamat1 }}</td>
                <td>{{ $p->kota1 }}</td>
                <td>{{ $p->provinsi1 }}</td>
                <td>{{ $p->kode_pos1 }}</td>
                <td>
                    @if(file_exists(public_path('storage/' . $p->foto)))
                    <img src="{{ public_path('storage/' . $p->foto) }}" alt="Foto">
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