<?php // ЛР3.2. Разлогин: удаление cookie; после него — страница авторизации
require_once __DIR__ . "/db.php";
unsetAuthCookie();
header("Location: index.php");
exit;
