<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            margin: 40px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .info, .rekap, .gaji {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .gaji th, .gaji td, .rekap th, .rekap td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .right {
            text-align: right;
        }
        .signature {
            margin-top: 60px;
            text-align: right;
        }
    </style>
</head>
<body>
    <h2>Slip Gaji Pegawai</h2>

    <div class="info">
        <p><strong>Nama:</strong> {{ $gaji->pegawai->nama_lengkap }}</p>
        <p><strong>Jabatan:</strong> {{ $gaji->pegawai->jabatan->nama_jabatan ?? '-' }}</p>
        <p><strong>Periode:</strong> {{ $gaji->bulan_periode }}</p>
        <p><strong>No Slip:</strong> {{ $gaji->no_slip }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::parse($gaji->tanggal_cetak)->translatedFormat('d F Y') }}</p>
    </div>

    <table class="gaji">
        <tr>
            <th>Total Pendapatan</th>
            <td class="right">Rp{{ number_format($gaji->total_pendapatan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Potongan</th>
            <td class="right">Rp{{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Gaji Bersih</th>
            <td class="right"><strong>Rp{{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <h4 style="margin-top: 30px;">📋 Rekap Kehadiran</h4>
    <table class="rekap">
        <tr><th>Hadir</th><td class="right">{{ $gaji->absen_hadir ?? 0 }} hari</td></tr>
        <tr><th>Izin</th><td class="right">{{ $gaji->absen_izin ?? 0 }} hari</td></tr>
        <tr><th>Sakit</th><td class="right">{{ $gaji->absen_sakit ?? 0 }} hari</td></tr>
        <tr><th>Cuti</th><td class="right">{{ $gaji->absen_cuti ?? 0 }} hari</td></tr>
        <tr><th>Alpha</th><td class="right">{{ $gaji->absen_alpha ?? 0 }} hari</td></tr>
    </table>

    <div class="signature">
        <p>Disetujui oleh,</p>
        <br><br>
        <p><strong>Bagian Keuangan</strong></p>
    </div>
</body>
</html>
