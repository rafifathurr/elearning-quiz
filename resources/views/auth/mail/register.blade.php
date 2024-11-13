<!DOCTYPE html>
<html>

<head>
    <title>ELearning - Quiz</title>
</head>

<style>
    table,
    th,
    td {
        border: 1px solid black;
    }
</style>

<body>
    <h2>Halo, {{ $data['name'] }}</h2>
    <h2>Daftar Akun Quiz</h2>
    <table>
        <thead>
            <tr>
                <th>
                    No
                </th>
                <th>
                    Quiz
                </th>
                <th>
                    Kode Akses
                </th>
                <th>
                    Password
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['quiz'] as $index => $quiz)
                <tr>
                    <td>
                        {{ $index + 1 }}
                    </td>
                    <td>
                        {{ $quiz['quiz'] }}
                    </td>
                    <td>
                        {{ $quiz['code_access'] }}
                    </td>
                    <td>
                        {{ $quiz['password'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Terimakasih</p>
</body>

</html>
