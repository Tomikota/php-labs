<?php // ЛР3.2. Авторизация: проверка пароля, применение cookie
require_once __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = db()->prepare("SELECT * FROM users WHERE login = :l");
    $stmt->execute([":l" => $_POST["login"] ?? ""]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($_POST["password"] ?? "", $user["password_hash"])) {
        die("Неверный логин или пароль<br><a href='login.php'>Назад</a>");
    }
    setAuthCookie((int)$user["id"], $user);
    header("Location: index.php");
    exit;
}
?>
<form method="post">
  <h2>Авторизация</h2>
  <label>Логин: <input name="login" required></label><br><br>
  <label>Пароль: <input name="password" type="password" required></label><br><br>
  <button>Войти</button>
</form>
<a href="register.php">Регистрация</a>
