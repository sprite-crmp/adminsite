<?php
session_start();
date_default_timezone_set('UTC +3');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (in_array(date('F'), ['December', 'January', 'February'])) include 'lib/snow.php';
include 'lib/db.php';

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index");
    exit();
}

function check_auth() {
    global $database;

    if (!isset($_SESSION['name'])) {
        header('Location: index');
        exit();
    }

    $name = $_SESSION['name'];

    $sql = "SELECT * FROM accounts WHERE name = ?";
    $stmt = $database->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            $admin = $user['admin'];

            if ($admin === 0) {
                return header('Location: no_access');;
            }

            $_SESSION['name'] = $user['name'];
            $_SESSION['password'] = $user['password'];
            $_SESSION['admin'] = $user['admin'];

        } else {
            header('Location: error');
        }
    } else {
        header('Location: error');
    }
}

function admin_enumeration(bool $strictly, int $admin_lvl) {
    global $database;

    if ($strictly == false) {
        $result = $database->query("SELECT COUNT(1) as total FROM accounts WHERE admin >= $admin_lvl");

        if ($result) {
            $row = $result->fetch_assoc();

            if ($row["total"] == 0) {
                return "нет";
            }
            else {
                return $row['total'];
            }
        } else {
            return "null";
        }
    }
    else {
        $result = $database->query("SELECT COUNT(1) as total FROM accounts WHERE admin = $admin_lvl");

        if ($result) {
            $row = $result->fetch_assoc();

            if ($row["total"] == 0) {
                return "нет";
            }
            else {
                return $row['total'];
            }
        } else {
            return "null";
        }
    }

}

function admin_list() {
    global $database;

    $result = $database->query("SELECT name, admin FROM accounts WHERE admin > 0 ORDER BY admin DESC");

    if ($result->num_rows > 0) {
        echo "<div class=\"admin-list\">";
        echo "<ul>";
        
        while($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['name']) . " (Уровень: " . (int)$row['admin'] . ")</li>";
        }
        
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<h3>На сервере нет администраторов</h3>";
    }
}

function get_business() {
    global $database;

    $result = $database->query("SELECT COUNT(*) as total FROM business");

    if ($result) {
        $row = $result->fetch_assoc();

        if ($row["total"] == 0) {
            return "нет";
        }
        else {
            echo "
            <h3>Количество бизнесов: " . $row['total'] . "</h3>
            ";
        }
    } else {
        return "null";
    }
}

function get_fuel_station() {
    global $database;

    $result = $database->query("SELECT COUNT(*) as total FROM fuel_stations");

    if ($result) {
        $row = $result->fetch_assoc();

        if ($row["total"] == 0) {
            return "нет";
        }
        else {
            echo "
            <h3>Количество АЗС: " . $row['total'] . "</h3>
            ";
        }
    } else {
        return "null";
    }
}
// ИИИИИИ ЛОНДОН ПРАГА НИЦЦА
function get_houses() {
    global $database;

    $result = $database->query("SELECT COUNT(*) as total FROM houses");

    if ($result) {
        $row = $result->fetch_assoc();

        if ($row["total"] == 0) {
            return "нет";
        }
        else {
            echo "
            <h3>Количество домов: " . $row['total'] . "</h3>
            ";
        }
    } else {
        return "null";
    }
}
// НОВЫЙ ПАААААСПОРТ НОВЫЙ ШТАТ
function get_promo() {
    global $database;

    $result = $database->query("SELECT COUNT(*) as total FROM promocode");

    if ($result) {
        $row = $result->fetch_assoc();

        if ($row["total"] == 0) {
            return "нет";
        }
        else {
            return $row['total'];
        }
    } else {
        return "null";
    }
}
// ГОНКА ПО ГРАНИЦАМ, МОЯ ЖИЗНЬ МОЙ ЧЕМОДАН!!!!!!!!!!!!
function get_ytpromo() {
    global $database;

    $result = $database->query("SELECT COUNT(*) as total FROM ytpromocode");

    if ($result) {
        $row = $result->fetch_assoc();

        if ($row["total"] == 0) {
            return "нет";
        }
        else {
            return $row['total'];
        }
    } else {
        return "null";
    }
}
// ВОЛЬНЫЙ БУДТО ПТИЦА, ТОЛЬКО ПТИЦЫ ВОЗВРАЩАЮТСЯ ДОМООООООЙ
function promo_list(bool $youtube) {
    global $database;

    echo "<div class=\"promo-list\">";
    echo "<ul>";

    $table = $youtube ? "ytpromocode" : "promocode";
    $cache = $database->query("SELECT * FROM {$table}");
    
    $counter = 1;

    while ($row = $cache->fetch_assoc()) {
        echo "<h3><strong>{$counter}.</strong> " . htmlspecialchars($row['code']) . "</h3>";
        $counter++;
    }

    echo "</ul>";
    echo "</div>";
}
// ПАРА ПАРА ПААА ПААА ПААА АВТОР СПИРТ КАРОЧЕ
check_auth();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="icon" href="img/logosites.png" type="image/png">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <title>Панель администратора</title>
</head>
<body>
    <button class="sidebar-toggle"><i class="fa-regular fa-rectangle-list"></i></button>

    <header>
        <div class="header">
            <h1><b><?php check_auth(); echo $_SESSION['name'];?></b><br/>Администратор <?php echo $_SESSION['admin'];?> уровня</h1>
            <img class="header-ico" src="img/adminico.png" width="40" alt="ico">
            <button class="adminpaneltext">Admin Site version 1.0 by Sprite</button>
        </div>
    </header>

    <div class="side-panel">
        <div class="menu">
            <ul>
                <?php check_auth();
                    if ($_SESSION['admin'] == 1) echo '
                    <li id="server_stats" class="active"><a href="#"><i class="fa-regular fa-rectangle-list"></i> Статистика сервера</a></li>
                    <li id="sign_out"><a href="#"><i class="fa-regular fa-circle-xmark"></i> Выйти из аккаунта</a></li>
                    ';

                    if ($_SESSION['admin'] == 2) echo '
                    <li id="server_stats" class="active"><a href="#"><i class="fa-regular fa-rectangle-list"></i> Статистика сервера</a></li>
                    <li id="sign_out"><a href="#"><i class="fa-regular fa-circle-xmark"></i> Выйти из аккаунта</a></li>
                    ';
                    
                    if ($_SESSION['admin'] == 3) echo '
                    <li id="server_stats" class="active"><a href="#"><i class="fa-regular fa-rectangle-list"></i> Статистика сервера</a></li>
                    <li id="sign_out"><a href="#"><i class="fa-regular fa-circle-xmark"></i> Выйти из аккаунта</a></li>
                    ';
                    
                    if ($_SESSION['admin'] == 4) echo '
                    <li id="server_stats" class="active"><a href="#"><i class="fa-regular fa-rectangle-list"></i> Статистика сервера</a></li>
                    <li id="sign_out"><a href="#"><i class="fa-regular fa-circle-xmark"></i> Выйти из аккаунта</a></li>
                    ';
                    
                    if ($_SESSION['admin'] == 5) echo '
                    <li id="server_stats" class="active"><a href="#"><i class="fa-regular fa-rectangle-list"></i> Статистика сервера</a></li>
                    <li id="server_admins"><a href="#"><i class="fa-regular fa-address-card"></i> Администрация сервера</a></li>
                    <li id="sign_out"><a href="#"><i class="fa-regular fa-circle-xmark"></i> Выйти из аккаунта</a></li>
                    ';
                    
                    if ($_SESSION['admin'] == 6) echo '
                    <li id="server_stats" class="active"><a href="#"><i class="fa-regular fa-rectangle-list"></i> Статистика сервера</a></li>
                    <li id="server_admins"><a href="#"><i class="fa-regular fa-address-card"></i> Администрация сервера</a></li>
                    <li id="sign_out"><a href="#"><i class="fa-regular fa-circle-xmark"></i> Выйти из аккаунта</a></li>
                    ';
                ?>
            </ul>
        </div>
    </div>

    <div class="dashboard" id="dash_server_admins">
        <div class="window_one">
            <h2>👨‍⚖️ Количество администрации</h2>
            <h3>Всего администраторов: <?php echo admin_enumeration(false, 1);?></h3>
            <h3><br>Администраторов 1 уровня: <?php echo admin_enumeration(true, 1);?></h3>
            <h3>Администраторов 2 уровня: <?php echo admin_enumeration(true, 2);?></h3>
            <h3>Администраторов 3 уровня: <?php echo admin_enumeration(true, 3);?></h3>
            <h3>Администраторов 4 уровня: <?php echo admin_enumeration(true, 4);?></h3>
            <h3>Администраторов 5 уровня: <?php echo admin_enumeration(true, 5);?></h3>
            <h3>Администраторов 6 уровня: <?php echo admin_enumeration(true, 6);?></h3>
        </div>
        <div class="window_two">
            <h2>🧾 Список администрации</h2>
            <h3><?php admin_list(); ?></h3>
            <button class="window_two-button"><i class="fa-regular fa-hand-pointer"></i></button>
        </div>
        <div class="window_two">
            <h2>🍕 Автор сайта</h2>
            <h3>Sprite Developer</h3>
            <a href="https://yoomoney.ru/to/4100117030483998" target="_blank"><button class="sprite_author"><i class="fa-regular fa-heart"></i> Кинуть на лапу</button></a>
            <a href="https://github.com/sprite-crmp" target="_blank"><button class="sprite_author"><i class="fa-regular fa-circle-user"></i> GitHub</button></a>
        </div>
    </div>

    <div class="dashboard" id="dash_server_stats">
        <div class="window_three">
            <h2>🎫 Список промокодов</h2>
            <div class="window_three_cont">
                <h3>Промокоды:</h3><br>
                <?php promo_list(false); ?><br>
                <h3>Ютуберские:</h3><br>
                <?php promo_list(true); ?><br>
                <h3>Всего промокодов: <?php echo get_ytpromo() + get_promo(); ?></h3>
            </div>
            <button class="window_three-button"><i class="fa-regular fa-hand-pointer"></i></button>    
        </div>
        <div class="window_one">
            <h2>🚩 PickUps</h2>
            <?php get_business(); get_fuel_station(); get_houses();?>
        </div>
    </div>

    <script src="js/scripts.js"></script>
</body>
</html>
