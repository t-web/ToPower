<?php
//require_once 'autoload.php'; 
// we've called a class ***




define(ALL_PATH, 'sites/all/');

 
/* autoload.php */
function TPower_load_module($className)
{
    $files=ALL_PATH.'/modules/'.$className . '.module';
    if (is_file($files)) {
        require $files;
    }
}
/* autoload.php */
// 加载模块
function load_module($className)
{
    $files=ALL_PATH.'/modules/'.$className . '.module';
    if (is_file($files)) {
        require $files;
    }
}
// 加载第三方库
function load_libs($className)
{
    $files=ALL_PATH.'/libs/'.$className . '.class.php';
    if (is_file($files)) {
        require $files;
    }
}
// 加载核心类
function load_core($className)
{
    $files='./core/'.$className . '.class.php';
    if (is_file($files)) {
        require $files;
    }
}


//注册了3个
spl_autoload_register('load_core');
spl_autoload_register('load_libs');
spl_autoload_register('load_module');
spl_autoload_register('TPower_load_module');
 $obj = new tClass();

var_dump(spl_autoload_functions());