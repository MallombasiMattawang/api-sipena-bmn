<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Lokasi Aset - {{ $data->nama_lokasi }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 10pt;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .header img {
            width: 80px; 
            height: auto;
        }

        .header-title {
            /* text-align: center; */
            font-size: 12pt; 
            color: red;
            font-weight: bold;
            padding-bottom: 5px; 
            line-height: 1.2;
        }

        .header-code {
            /* text-align: center; */
            font-size: 8pt;
            margin-top: -8px; 
        }

        .tahun {
            font-size: 12pt;
        }

        .table-container {
            width: 100%;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
        }

        th {
            background-color: #007bff;
            color: white;
            font-size: 10pt;
        }

        .info-section {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 10pt;
        }

        .info-section div {
            width: 32%;
        }

        .info-label {
            font-weight: bold;
        }

        .barcode {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .qrcode {
            text-align: center;
            margin-top: 20px;
        }

        .signature-section {
            margin-top: 40px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid black;
            width: 300px;
            margin: 0 auto;
        }
        .qrcode img {
            width: 150px;
        }

        @media print {
            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                margin-bottom: 20px;
            }

            table {
                page-break-after: auto; 
            }

            thead {
                display: table-header-group; 
            }
        }
    </style>
</head>

<body>
    <!-- Print Button -->
    <div class="print-button" style="text-align: right; margin-bottom: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 12pt;">Cetak</button>
    </div>
    <div class="header">
        <table>
            <tr>
                <td style="width: 80px; vertical-align: middle;" rowspan="2">
                    <img src="{{ asset('img/logo-login.png') }}" alt="Logo Instansi">
                </td>
                <td>
                    <div class="header-title">
                        INVENTARIS BARANG MILIK NEGARA
                    </div>
                    <div class="header-code">
                        KANTOR OTORITAS BANDAR UDARA WILAYAH V MAKASSAR<br>
                        022.05.1900.465672.KD
                    </div>
                </td>
                
                <td style="width: 100px; text-align: center; vertical-align: middle;">
                    <img src="{{ $qrCodeImage }}" alt="QR Code">
                </td>
            </tr>
            <tr>
                <td colspan="2"><strong>KODE RUANGAN : &nbsp;&nbsp; {{ $data->kode_lokasi }}</strong> </td>
                
            </tr>
            
        </table>

        <table>
            
                <thead>
                    <tr>
                    <th rowspan="2">NO</th>
                    <th rowspan="2">KODE BARANG</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th colspan="3" style="text-align: center">IDENTITAS BARANG</th>
                    {{-- <th rowspan="2">JUMLAH BARANG</th> --}}
                    <th rowspan="2">KETERANGAN</th>
                </tr>
                <tr>
                    <th>MERK/TYPE</th>
                    <th>NUP</th>
                    <th>TAHUN</th>
                </tr>
                </thead>
                <tbody>
                    @php
                        $n = 1;
                    @endphp
                    @foreach ($aset as $r)
                        <tr>
                            <td>{{ $n++ }}</td>
                            <td>{{ $r->kode_aset }}</td>
                            <td>{{ $r->nama_aset }}</td>
                            <td>{{ $r->merk_type }}</td>
                            <td>{{ $r->nup }}</td>
                            <td>{{ $r->tahun_perolehan }}</td>
                            <td>{{ strip_tags($r->deskripsi) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            
        </table>

        
    </div>

    
</body>

</html>