<?php

function pre($array, $comment = false, $dump = false)
{
    if ($comment) echo '<!--';

    echo '<pre>';

    if ($dump)
        var_dump($array);
    else
        print_r($array);

    echo '</pre>';

    if ($comment) echo '-->';
}

function redirect($path)
{
    header('location: '.$path);
}




function get_config_modules($key, $module_name = null)
{
    global $config;

    if($module_name === null)
    {
        global $local_data;
        $module_name = $local_data['module_name'];
    }

    return $config['modules'][$module_name][$key];
}




// Получение корректных url_param
$path_info = $_SERVER['REDIRECT_URL'];
$param = explode('/', $path_info);
@array_shift($param);

if (@$param[0] == 'index.php') @array_shift($param);
if (count($param) == 0) $param = array('root');

$config['url'] = $url = $param;

if($config['url'][0] == 'api')
    $config['api_mode'] = true;

else if($config['url'][0] == 'scripts')
    $config['scripts_mode'] = true;



