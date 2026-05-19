<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar SPPT</title>
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
    <h1>Daftar SPPT</h1>
    <table>
        <thead>
            <tr>
                <th>ID SPPT</th>
                <th>NOP</th>
                <th>Tahun</th>
                <th>NJOP Bumi</th>
                <th>NJOP Bangunan</th>
                <th>Pajak Terhutang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sppt as $item)
                <tr>
                    <td>{{ $item->id_sppt }}</td>
                    <td>{{ $item->nop }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ number_format($item->njop_bumi, 2, ',', '.') }}</td>
                    <td>{{ number_format($item->njop_bangunan, 2, ',', '.') }}</td>
                    <td>{{ number_format($item->pajak_terhutang, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($item->status_bayar) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
