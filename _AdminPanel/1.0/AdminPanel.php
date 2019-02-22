<?

$local_data['module_name'] = $name;

// Url при котором будет открываться админ-панель
if ($config['admin_url'] == null)
    $config['modules'][$local_data['module_name']]['admin_url'] = 'admin';
else
    $config['modules'][$local_data['module_name']]['admin_url'] = $config['admin_url'];

// Подключаем скрипты
if ($url[0] == $config['modules'][$local_data['module_name']]['admin_url'])
{
    $system['status_admin_panel'] = true;

    if($config['debug'] == false)
        $config['modules'][$local_data['module_name']]['cache'] = get_config_modules('cache_tpl_path');
    else
        $config['modules'][$local_data['module_name']]['cache'] = false;

    $loader_admin = new Twig_Loader_Filesystem($module_path.'twig_tpl/');
    $twig_admin = new Twig_Environment($loader_admin, array(
        //'cache' => get_config_modules('cache_tpl_path'),
        'cache' => $config['modules'][$local_data['module_name']]['cache'],
    ));


    $params = [];

    if($system['user']->auth == 0)
    {
        echo $twig_admin->render('root.html', $params);
    }
    else
    {
        echo $twig_admin->render('main_panel.html', $params);
        $config['scripts_modules'][] = $module_path . 'AdminPanel.js';
    }
}
else
{

    $system['status_admin_panel'] = false;
}

?>