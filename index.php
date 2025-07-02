<?php
session_start();
date_default_timezone_set('UTC +3');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (date('F') == 'December' || date('F') == 'January' || date('F') == 'February') {
    include 'lib/snow.php';
}
require 'lib/db.php';
include 'loading.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(filter_var($_POST['login'], FILTER_SANITIZE_SPECIAL_CHARS));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS));

    $sql = "SELECT * FROM accounts WHERE name = ?";
    $stmt = $database->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            $storedSalt = $user['salt'];
            $storedHash = $user['password'];
            $admin = $user['admin'];

            if ($admin === 0) {
                return header('Location: no_access');;
            }

            $hashedInputPassword = hash('sha256', $password . $storedSalt);

            if (strcasecmp($hashedInputPassword, $storedHash) === 0) {
                $_SESSION['name'] = $user['name'];
                $_SESSION['password'] = $user['password'];
                $_SESSION['admin'] = $user['admin'];
                
                loading(2, "main");
            } else {
                header('Location: error');
            }
        } else {
            header('Location: error');
        }
    } else {
        header('Location: error');
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
    <head>
       <meta charset="UTF-8">
       <title>Авторизация</title>
       <link rel="icon" href="img/logosites.png" type="image/png">
       <meta name="apple-mobile-web-app-capable" content="yes">
       <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
       <meta name="apple-mobile-web-app-title" content="Авторизация">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
       <link rel="stylesheet" href="css/index.css" />
    </head>

    <body>
        <div class="container">
            <h3 id="sprite_author">Авторизация<br>by Sprite</h3>
            <form action="" method="post">
                <div class="inline">
                    <input type="text" class="form-control" id="login" name="login" placeholder="Введите никнейм" required>
                </div>
                <div class="inline">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Войти</button>
            </form>
        </div>
    </body> 
    <script src="js/index.js"></script>
</html>
