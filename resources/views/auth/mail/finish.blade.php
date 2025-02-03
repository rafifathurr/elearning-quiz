<!DOCTYPE html>
<html>

<head>
    <title>Hasil Test</title>
</head>

<body>
    <p>Hallo {{ $data['name'] }},</p>
    <h3>Skor Anda: {{ $data['result']->total_score }}</h3>
    <h3>Waktu Selesai: {{ $data['result']->finish_time->format('d M Y H:i') }}</h3>
</body>


</html>
