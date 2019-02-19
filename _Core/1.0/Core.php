<?php

if ($url[0] != 'scripts')
    session_start();

$config['scripts_modules'] = [];

// Функции ядра
require_once 'Core_functions.php';

// Загрузчик модулей
require_once $config['path']['modules'] . '_ModuleLoader/1.0/ModuleLoader.php';

$config['scripts_modules'][] = $config['path']['modules'] . '_Core/1.0/scripts.js';

if ($url[0] == 'scripts') {

    header('Expires: 0');
    header('Pragma: no-cache');
    header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
    header('Content-Type: application/javascript; charset=utf-8');
    $scripts = '';
    if (!empty($config['scripts_modules']))
        foreach ($config['scripts_modules'] as $item) {
            $scripts .= file_get_contents($item);
        }

    echo $scripts;
}