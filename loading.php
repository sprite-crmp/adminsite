<?php
function loading(int $time, string $header)
{
    echo '<!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="refresh" content="' . $time . '; url=' . $header . '">
        <link rel="icon" href="img/logosites.png" type="image/png">
        <link rel="stylesheet" href="css/loading.css">
        <title>Загрузка...</title>
    </head>
    <body>
        <div class="newtons-cradle">
            <div class="newtons-cradle__dot"></div>
            <div class="newtons-cradle__dot"></div>
            <div class="newtons-cradle__dot"></div>
            <div class="newtons-cradle__dot"></div>
        </div>
    </body>
    </html>';
    
    exit();
}