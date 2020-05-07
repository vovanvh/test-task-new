<?php

/**
 * Autoload function
 */
spl_autoload_register(function ($className) {
    $baseClassFile = __DIR__ . DIRECTORY_SEPARATOR . '{app_dir}'
        . DIRECTORY_SEPARATOR . str_replace('\\', '/', $className) . '.php';
    $classFile = str_replace('{app_dir}', 'lib', $baseClassFile);
    if (file_exists($classFile)) {
        require $classFile;
        return true;
    }
    $classFile = str_replace('{app_dir}' . DIRECTORY_SEPARATOR, '', $baseClassFile);
    if (file_exists($classFile)) {
        require $classFile;
        return true;
    }
});