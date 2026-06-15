<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$routes = collect(\Route::getRoutes())->map->getName()->filter()->toArray();

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
$missing = [];
foreach($files as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        preg_match_all('/route\([\'"]([^\'"]+)[\'"]/', $content, $matches);
        foreach($matches[1] as $route) {
            if (!in_array($route, $routes)) {
                $missing[] = "Missing route $route in " . $file->getPathname();
            }
        }
    }
}
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/Http/Controllers'));
foreach($files as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        preg_match_all('/route\([\'"]([^\'"]+)[\'"]/', $content, $matches);
        foreach($matches[1] as $route) {
            if (!in_array($route, $routes)) {
                $missing[] = "Missing route $route in " . $file->getPathname();
            }
        }
    }
}
if (empty($missing)) {
    echo "All routes are valid.\n";
} else {
    echo implode("\n", array_unique($missing)) . "\n";
}
