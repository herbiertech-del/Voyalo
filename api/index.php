<?php
declare(strict_types=1);

// Vercel runs PHP only from api/. Dispatch known application pages from one function.
$pages = [
    '/index.php' => __DIR__ . '/../index.php',
    '/hotels.php' => __DIR__ . '/../hotels.php',
    '/hotel-detail.php' => __DIR__ . '/../hotel-detail.php',
    '/billets.php' => __DIR__ . '/../billets.php',
    '/forfaits.php' => __DIR__ . '/../forfaits.php',
    '/checkout.php' => __DIR__ . '/../checkout.php',
    '/login.php' => __DIR__ . '/../login.php',
    '/register.php' => __DIR__ . '/../register.php',
    '/logout.php' => __DIR__ . '/../logout.php',
    '/dashboard.php' => __DIR__ . '/../dashboard.php',
    '/admin/index.php' => __DIR__ . '/../admin/index.php',
    '/admin/catalog.php' => __DIR__ . '/../admin/catalog.php',
    '/admin/export.php' => __DIR__ . '/../admin/export.php',
    '/admin/setup.php' => __DIR__ . '/../admin/setup.php',
    '/agent/index.php' => __DIR__ . '/../agent/index.php',
    '/api/availability.php' => __DIR__ . '/availability.php',
];

$route = (string)($_GET['__voyalo_route'] ?? '');
unset($_GET['__voyalo_route']);
if (!isset($pages[$route])) {
    http_response_code(404);
    exit('Page introuvable.');
}

$_SERVER['SCRIPT_NAME'] = $route;
$_SERVER['PHP_SELF'] = $route;
require $pages[$route];
