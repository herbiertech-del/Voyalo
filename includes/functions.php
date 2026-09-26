<?php
function e(?string $v): string { return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function app_base_path(): string {
    $configured = trim((string)($GLOBALS['config']['base_url'] ?? ''));
    if ($configured !== '') return rtrim($configured, '/');
    $path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    if (in_array(basename($path), ['admin', 'agent', 'api', 'client'], true)) $path = dirname($path);
    return $path === '/' || $path === '.' ? '' : rtrim($path, '/');
}
function app_url(string $path = '/'): string { return app_base_path() . '/' . ltrim($path, '/'); }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="'.e(csrf_token()).'">'; }
function verify_csrf(): void { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Session expirée. Rechargez la page.'); } }
function user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!user()) { header('Location: ' . app_url('/login.php')); exit; } }
function require_role(array $roles): void { require_login(); if (!in_array(user()['role'], $roles, true)) { http_response_code(403); exit('Accès refusé.'); } }
function money($v): string { return number_format((float)$v, 0, ',', ' ') . ' FCFA'; }
function redirect(string $url): never { header('Location: ' . app_url($url)); exit; }
