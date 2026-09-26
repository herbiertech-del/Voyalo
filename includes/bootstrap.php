<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) { session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']),'samesite'=>'Lax']); session_start(); }
$config = require __DIR__ . '/../config/config.php';
try { $pdo = new PDO("mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset={$config['db']['charset']}", $config['db']['user'], $config['db']['pass'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false]); }
catch (PDOException $e) { http_response_code(500); exit('Connexion à la base de données impossible. Vérifiez config/config.php.'); }
require_once __DIR__ . '/functions.php';
