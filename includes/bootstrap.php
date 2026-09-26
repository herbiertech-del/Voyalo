<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) { session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']),'samesite'=>'Lax']); session_start(); }
// Local WAMP keeps credentials in config/config.php; Vercel injects them as env vars.
$localConfig = __DIR__ . '/../config/config.php';
if (is_file($localConfig)) {
    $config = require $localConfig;
} else {
    $config = ['db' => [
        'host' => getenv('DB_HOST') ?: '',
        'name' => getenv('DB_NAME') ?: '',
        'user' => getenv('DB_USER') ?: '',
        'pass' => getenv('DB_PASSWORD') ?: '',
        'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
    ]];
}
foreach (['host', 'name', 'user', 'pass'] as $key) {
    if (!array_key_exists($key, $config['db'] ?? []) || $config['db'][$key] === '') {
        http_response_code(503);
        exit('Voyalo est en ligne, mais sa base de données doit encore être configurée.');
    }
}
try { $pdo = new PDO("mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset={$config['db']['charset']}", $config['db']['user'], $config['db']['pass'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false]); }
catch (PDOException $e) { http_response_code(500); exit('Connexion à la base de données impossible. Vérifiez config/config.php.'); }
require_once __DIR__ . '/functions.php';
