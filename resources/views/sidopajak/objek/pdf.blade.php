<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Objek Pajak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
        }

        th {
            background: #f7f7f7;
        }
    </style>
</head>

<body>
    <h1>Daftar Objek Pajak</h1>
    <table>
        <thead>
            <tr>
                <th>NOP</th>
                <th>Pemilik</th>
                <th>Letak</th>
                <th>Luas Bumi</th>
                <th>Luas Bangunan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($objek as $item)
                <tr>
                    <td>{{ $item->nop }}</td>
                    <td>{{ $item->subjekPajak?->nama ?? $item->nik_pemilik }}</td>
                    <td>{{ $item->letak_objek }}</td>
                    <td>{{ number_format($item->luas_bumi, 2, ',', '.') }}</td>
                    <td>{{ number_format($item->luas_bangunan, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($item->status_aktif) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
