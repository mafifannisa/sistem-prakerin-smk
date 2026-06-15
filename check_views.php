<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/Http/Controllers'));
foreach($files as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        preg_match_all('/view\([\'"]([^\'"]+)[\'"]/', $content, $matches);
        foreach($matches[1] as $view) {
            $path = 'resources/views/' . str_replace('.', '/', $view) . '.blade.php';
            if (!file_exists($path)) {
                echo "Missing view $view in " . $file->getPathname() . "\n";
            }
        }
    }
}
echo "View check completed.\n";
