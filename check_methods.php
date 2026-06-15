<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$routes = collect(Illuminate\Support\Facades\Route::getRoutes())->map(function ($route) { return $route->getActionName(); });
foreach ($routes as $action) {
    if (strpos($action, '@') !== false) {
        list($class, $method) = explode('@', $action);
        if (!method_exists($class, $method)) {
            echo 'Missing method: ' . $class . '@' . $method . PHP_EOL;
        }
    }
}
echo 'Done!' . PHP_EOL;
