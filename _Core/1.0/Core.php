<?php

if ($url[0] != 'scripts')
    session_start();

$config['scripts_modules'] = [];

// Функции ядра
require_once 'Core_functions.php';

// Загрузчик модулей
require_once $config['path']['modules'] . '_ModuleLoader/1.0/ModuleLoader.php';


if ($url[0] === 'scripts') {

    $config['scripts_modules'][] = $config['path']['modules'] . '_Core/1.0/scripts.js';

    header('Expires: 0');
    header('Pragma: no-cache');
    header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
    header('Content-Type: application/javascript; charset=utf-8');
    $scripts = '';

    foreach ($config['scripts_modules'] as $item) {
        $scripts .= file_get_contents($item);
    }
    echo $scripts;

} else {
    // Конфиг сайта
    require_once $config['path']['root'] . 'config.php';
}

if ($url[0] === 'api') {

    if ($_POST['action'] != null) {

        $name_action = $_POST['action'];

        unset($_POST['action']);

        $params = $_POST;

        $handler_render_action = false;

        $action = $config['path']['methods'] . $name_action . '.php';

        if (is_file($action))
            include_once($action);

        @header('Expires: 0');
        @header('Pragma: no-cache');
        @header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        @header('Content-Type: application/javascript; charset=utf-8');


        if (@$response !== false) {

            if ($handler_render_action === true)
                $response['render'] = 'render_' . $name_action;

            echo json_encode(array('response' => $response));

        }
    }

}

// Файл для пользовательских php скриптов
if ($config['scripts_mode'] == false && $config['api_mode'] == false
    && $system['status_admin_panel'] == false) {
    require_once $config['path']['root'] . 'site/router.php';
} else
    exit;
?>