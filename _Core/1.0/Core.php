<?php

if ($url[0] != 'scripts')
    session_start();

$config['scripts_modules'] = [];

// Функции ядра
require_once 'Core_functions.php';

// Загрузчик модулей
require_once $config['path']['modules'] . '_ModuleLoader/1.0/ModuleLoader.php';


if ($url[0] == 'scripts') {

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

} else if ($url[0] == 'api') {

    $name_action = $_POST['action'];

    unset ($_POST['action']);

    $params = $_POST;

   $handler_render_action = false;

    if (is_file($config['path']['methods'] . $name_action . '.php')) {
        include_once($config['path']['methods'] . $name_action . '.php');
    }

    @header('Expires: 0');
    @header('Pragma: no-cache');
    @header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
    @header('Content-Type: application/javascript; charset=utf-8');


    if (@$response !== false) {

        if($handler_render_action === true)
            $response['render'] = 'render_'.$name_action;

        echo json_encode(array('response' => $response));

    }

}
?>