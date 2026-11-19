<?php // ЛР3.2. Главная: автоматическая авторизация из cookie + применение настроек
require_once __DIR__ . "/db.php";

$auth = readAuthCookie(); // если пользователь не разлогинился — сессия продолжается
$bg = htmlspecialchars($auth["bg"] ?? "#ffffff");
$font = htmlspecialchars($auth["font"] ?? "#000000");
?>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { background: <?= $bg ?>; color: <?= $font ?>; font-family: sans-serif }
    </style>
</head>
<body>
<?php if ($auth): ?>
    <h2>Привет, пользователь #<?= (int)$auth["id"] ?>!</h2>
    <p>Ваши настройки применены из cookie (автоматическая авторизация).</p>
    <form method="post" action="logout.php">
        <button>Разлогиниться</button>
    </form>
<?php else: ?>
    <h2>Вы не авторизованы</h2>
    <a href="login.php">Войти</a> · <a href="register.php">Зарегистрироваться</a>
<?php endif; ?>
</body>
</html>
