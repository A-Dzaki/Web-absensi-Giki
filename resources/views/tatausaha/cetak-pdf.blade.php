<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi - {{ $kelas }} - {{ date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2, .header h3 { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .text-center { text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>REKAP ABSENSI KELAS {{ $kelas }}</h2>
        <h3>SMP GIKI 2 SURABAYA</h3>
        <p>
            Periode: {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} {{ $tahun }} <br>
            @if($mapel) Mata Pelajaran: {{ $mapel }} @endif <br>
            Wali Kelas: {{ $kelasData->walikelas ?? '-' }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th class="text-center">L/P</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Sakit</th>
                <th class="text-center">Alfa</th>
                <th class="text-center">Total</th>
                <th class="text-center">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapAbsensi as $index => $s)
            @php
                $total = $s->hadir + $s->izin + $s->sakit + $s->alpa;
                $persentase = $total > 0 ? round(($s->hadir / $total) * 100) : 0;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $s->nis }}</td>
                <td>{{ $s->name }}</td>
                <td class="text-center">{{ $s->jenis_kelamin }}</td>
                <td class="text-center">{{ $s->hadir }}</td>
                <td class="text-center">{{ $s->izin }}</td>
                <td class="text-center">{{ $s->sakit }}</td>
                <td class="text-center">{{ $s->alpa }}</td>
                <td class="text-center">{{ $total }}</td>
                <td class="text-center">{{ $persentase }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; float: right; width: 200px; text-align: center;">
        <p>Surabaya, {{ date('d F Y') }} <br> Mengetahui,</p>
        <br><br><br>
        <p><strong>{{ $kelasData->walikelas ?? '.........................' }}</strong><br>Wali Kelas</p>
    </div>
</body>
</html>
