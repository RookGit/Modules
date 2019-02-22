<?php

$local_data['module_name'] = $name;

$config['modules'][$local_data['module_name']]['tpl_path'] = $config['path']['root'] . 'assets/tpl/';
$config['modules'][$local_data['module_name']]['cache_tpl_path'] = $config['path']['root'] . 'assets/temp/twig/';

if($config['debug'] == false)
	$config['modules'][$local_data['module_name']]['cache'] = get_config_modules('cache_tpl_path');
else
	$config['modules'][$local_data['module_name']]['cache'] = false;

$loader = new Twig_Loader_Filesystem(get_config_modules('tpl_path'));
$twig = new Twig_Environment($loader, array(
    //'cache' => get_config_modules('cache_tpl_path'),
    'cache' => $config['modules'][$local_data['module_name']]['cache'],
));

//echo $twig->render('index.html', array(
//    'user_name' => 'Rook Developer!',
//    'variable_1' => 'Название переменной!',
//    'boxes' => ['file1.html','file2.html']
//));

