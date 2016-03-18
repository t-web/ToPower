<?php
require_once __DIR__.'/vendor/autoload.php'; 



define(ALL_PATH, 'sites/all/');

 
/* autoload.php */
function TPower_load_module($className)
{
    $files=ALL_PATH.'/modules/'. str_replace('\\', '/', $className) . '.module';
    if (is_file($files)) {
        require $files;
    }
}
/* autoload.php */
// 加载模块
function load_module($className)
{
  // . str_replace('\\', '/', $class) . '
    $files=ALL_PATH.'/modules/'. str_replace('\\', '/', $className) . '.module';
    if (is_file($files)) {
        require $files;
    }
}
// 加载第三方库
function load_libs($className)
{
    $files=ALL_PATH.'/libs/'. str_replace('\\', '/', $className) . '.class.php';
    if (is_file($files)) {
        require $files;
    }
}
// 加载核心类
function load_core($className)
{
    $files='./core/'. str_replace('\\', '/', $className) . '.class.php';
    if (is_file($files)) {
        require $files;
    }
}


//注册了3个
spl_autoload_register('load_core');
spl_autoload_register('load_libs');
spl_autoload_register('load_module');
//spl_autoload_register('TPower_load_module');
 // $obj = new tClass();

//var_dump(spl_autoload_functions());