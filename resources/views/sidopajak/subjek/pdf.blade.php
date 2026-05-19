<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Subjek Pajak</title>
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
    <h1>Daftar Subjek Pajak</h1>
    <table>
        <thead>
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>RT/RW</th>
                <th>No HP</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjek as $item)
                <tr>
                    <td>{{ $item->nik }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->rt }}/{{ $item->rw }}</td>
                    <td>{{ $item->no_hp }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
