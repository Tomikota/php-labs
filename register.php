<?php // ЛР3.2. Регистрация: логин, пароль и настройки вида → сохранение в БД + cookie
require_once __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"] ?? "");
    $pass  = $_POST["password"] ?? "";
    $bg    = preg_replace("/[^#0-9a-fA-F]/", "", $_POST["bg"] ?? "#ffffff");
    $font  = preg_replace("/[^#0-9a-fA-F]/", "", $_POST["font"] ?? "#000000");

    if ($login === "" || $pass === "") {
        die("Заполните логин и пароль");
    }
    $stmt = db()->prepare("SELECT id FROM users WHERE login = :l");
    $stmt->execute([":l" => $login]);
    if ($stmt->fetch()) die("Пользователь с таким логином уже существует");

    $stmt = db()->prepare(
        "INSERT INTO users (login, password_hash, bg_color, font_color)
         VALUES (:l, :p, :bg, :f)"
    );
    $stmt->execute([":l" => $login, ":p" => password_hash($pass, PASSWORD_DEFAULT), ":bg" => $bg, ":f" => $font]);
    $userId = (int)db()->lastInsertId();

    // передаём параметры клиентской стороне (cookie)
    setAuthCookie($userId, ["bg_color" => $bg, "font_color" => $font]);
    header("Location: index.php");
    exit;
}
?>
<form method="post">
  <h2>Регистрация</h2>
  <label>Логин: <input name="login" required></label><br><br>
  <label>Пароль: <input name="password" type="password" required></label><br><br>
  <label>Цвет фона: <input name="bg" value="#ffffff"></label><br><br>
  <label>Цвет шрифта: <input name="font" value="#000000"></label><br><br>
  <button>Зарегистрироваться</button>
</form>
<a href="login.php">Войти</a>
