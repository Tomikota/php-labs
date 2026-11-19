<?php
// ЛР3.2. Общий модуль подключения к БД и работы с сессией
// Запуск в Apache: БД должна существовать (см. lab.db.sql)
const DB_DSN  = "mysql:host=127.0.0.1;dbname=php_savinov;charset=utf8mb4";
const DB_USER = "root";
const DB_PASS = "";

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            // Убедимся, что таблица пользователей есть
            $pdo->query(
                "CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    login VARCHAR(64) NOT NULL UNIQUE,
                    password_hash VARCHAR(255) NOT NULL,
                    bg_color VARCHAR(16) NOT NULL DEFAULT '#ffffff',
                    font_color VARCHAR(16) NOT NULL DEFAULT '#000000'
                ) CHARACTER SET utf8mb4"
            );
        } catch (PDOException $e) {
            die("Ошибка подключения к БД: " . $e->getMessage());
        }
    }
    return $pdo;
}

const COOKIE_NAME = "auth_uid";

/** Сохраняет параметры пользователя в cookie (срок действия — год). */
function setAuthCookie(int $userId, array $user): void {
    $payload = urlencode(json_encode([
        "id"   => $userId,
        "bg"   => $user["bg_color"],
        "font" => $user["font_color"],
    ]));
    setcookie(COOKIE_NAME, $payload, time() + 365 * 24 * 3600, "/");
}

/** Восстанавливает параметры из cookie. Возврат: массив или null. */
function readAuthCookie(): ?array {
    $raw = $_COOKIE[COOKIE_NAME] ?? null;
    if (!$raw) return null;
    $data = json_decode(urldecode($raw), true);
    return is_array($data) ? $data : null;
}

/** Разлогин: cookie усталяется в прошлое — «сессия в cookie» прерывается. */
function unsetAuthCookie(): void {
    setcookie(COOKIE_NAME, "", time() - 3600, "/");
}
