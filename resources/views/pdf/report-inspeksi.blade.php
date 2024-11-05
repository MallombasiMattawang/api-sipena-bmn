<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Inspeksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0px;
            font-size: 10pt;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
        }

        .header img {
            width: 100px; /* Sesuaikan dengan ukuran logo */
            height: auto;
        }

        .header-title {
            text-align: center;
            font-size: 14pt; /* Ukuran font judul */
            font-weight: bold;
            padding: 10px; /* Padding untuk judul */
        }

        h2 {
            margin-top: 30px;
            margin-bottom: 10px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
            page-break-inside: auto; /* Memungkinkan pemisahan halaman di dalam tabel */
        }

        th,
        td {
            /* border: 1px solid #ddd; */
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
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

        /* CSS untuk mencetak */
        @media print {
            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                margin-bottom: 20px;
            }

            .header img {
                width: 80px; /* Sesuaikan dengan ukuran logo */
                height: auto;
            }

            table {
                page-break-after: auto; /* Memungkinkan pemisahan halaman setelah tabel */
            }

            thead {
                display: table-header-group; /* Menampilkan thead di setiap halaman */
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <td rowspan="2" style="width: 100px; vertical-align: middle;">
                    <img src="{{ public_path('img/logo-login.png') }}" alt="Logo Instansi"> <!-- Ganti dengan path logo Anda -->
                </td>
                <td colspan="3" class="header-title">
                    <span style="font-size: 11pt">KEMENTERIAN PERHUBUNGAN </span><br>
                    DIREKTORAT JENDERAL PERHUBUNGAN UDARA <br>
                    <span style="font-size: 11pt">KANTOR OTORITAS BANDAR UDARA WILAYAH V MAKASSAR</span>
                </td>
            </tr>
            <tr>
                <td style="width: 30%; font-size:9pt;background-color: white;border-right: 1px solid black;">
                    JL. OTORITAS BANDAR UDARA NO.5 <br>
                    MAROS, SULAWESI SELATAN 90552
                </td>
                <td style="width: 16%; font-size:9pt;background-color: white;border-right: 1px solid black;">
                    TELP : <br> (0411) 3656222
                </td>
                <td style="width: 40%; font-size:9pt;background-color: white;">
                    FAX: (0411) 3656221 <br>
                    EMAIL: otban_wil.v@dephub.go.id <br>
                    Website: www.otban5.com
                </td>
            </tr>
        </table>
        <hr style="margin-top: -20px">
    </div>

    <h2>Daftar Aset yang di WASDAL E-BMN</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Aset</th>
                <th>Aset BMN</th>
                <th>Masa Manfaat</th>
                <th>Status Inspeksi</th>
                <th>Hasil dan Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assets as $index => $asset)
            @php
                // Mengkonversi nilai ke angka
                $tahunPerolehan = (int) $asset['tahun'] ?: 0;
                $masaPakai = (int) $asset['masa_pakai'] ?: 0;
                $tahunAkhir = $tahunPerolehan + $masaPakai;
        
                // Hitung tahun saat ini
                $currentYear = date('Y');
        
                // Hitung persentase progres
                if ($currentYear >= $tahunAkhir) {
                    $progressPercentage = 100;
                } else {
                    if ($masaPakai > 0) {
                        $progressPercentage = (($currentYear - $tahunPerolehan) / $masaPakai) * 100;
                    } else {
                        $progressPercentage = 0; // Jika masa pakai 0, set progres ke 0
                    }
                }
        
                // Pastikan progressPercentage tidak kurang dari 0
                $progressPercentage = max(0, $progressPercentage);
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    Kode:{{ $asset['kode_aset'] }} <br>
                    NUP:{{ $asset['nup'] }} <br>
                </td>
                <td>
                    Nama Aset: {{ $asset['nama_aset'] }} <br>
                    Kategori: {{ $asset['kategori'] }} <hr>
                    Merk/Tipe: {{ $asset['merk'] }} <br>
                    Tahun: {{ $asset['tahun'] }} <hr>
                    Pemegang Aset: <br> {{ $asset['pemegang_aset'] }} <br>
                    
                </td>
                <td>
                    Masa Manfaat: {{ $asset['masa_pakai'] }} Thn <br>
                    Presentase: {{ $progressPercentage }}%<br>
                    Tahun Akhir: {{ $tahunAkhir }} <br>
                </td>
                <td>
                    Kondisi: {{ $asset['kondisi'] }}<br>
                    Status: {{ $asset['status'] }}<br>
                    Lokasi: {{ $asset['lokasi'] }} <hr>
                    Pemeriksa: <br> {{ $asset['petugas'] }} <br>
                </td>
                <td>
                    Hasil inspeksi: <br> {{ $asset['hasil_inspeksi'] }} <hr>
                    Rekomendasi: <br> {{ $asset['rekomendasi'] }} <br>
                </td>
                
            </tr>
        @endforeach
        </tbody>
    </table>

    
</body>

</html>