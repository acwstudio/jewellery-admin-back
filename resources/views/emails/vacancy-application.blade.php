<?php

declare(strict_types=1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявка на вакансию</title>
</head>
<body>
<h2>Новая заявка на вакансию</h2>

<p><strong>Город:</strong> {{ $applicationData->city }}</p>
<p><strong>Департамент:</strong> {{ $applicationData->department }}</p>
<p><strong>Вакансия:</strong> {{ $applicationData->job }}</p>
<p><strong>Фамилия:</strong> {{ $applicationData->surname }}</p>
<p><strong>Имя:</strong> {{ $applicationData->name }}</p>
<p><strong>Гражданство:</strong> {{ $applicationData->citizenship }}</p>
<p><strong>Телефон:</strong> {{ $applicationData->phone }}</p>

@if ($applicationData->resume instanceof \Illuminate\Http\UploadedFile)
    <p><strong>Резюме:</strong> Прикреплено</p>
@else
    <p><strong>Резюме:</strong> Не прикреплено</p>
@endif
</body>
</html>
