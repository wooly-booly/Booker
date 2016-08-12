<?php

$includePaths = array(APP_PATH.'core',
                    APP_PATH.'classes',
                    APP_PATH.'classes'.DS.'models',
                    get_include_path(), );

$includePaths = implode(PATH_SEPARATOR, $includePaths);
set_include_path($includePaths);

function __autoload($className)
{
    $fileName = $className.'.php';
    require_once $fileName;
}

require_once APP_PATH.'config'.DS.'config.php';

try {
    Route::run();
} catch (Exception $e) {
    echo 'ERROR: '.$e->getMessage();
}
