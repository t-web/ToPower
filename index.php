<?php
/********
 * By Terry Chan 
 *   Blog http://www.terrychan.org
 * 
 * 入口文件
 * 
 * 
 * 
 * 
 * */
use \Slender\ModuleLoader\ModuleLoader;
use \Slender\ModuleLoader\ModuleInterface;
require 'vendor/autoload.php';


/**
 * An example module class
 */
class MyModule implements ModuleInterface
{
    public function invokeModule( \Slim\App $slim )
    {
        $slim->get('/foo',function(){
            /* ... */
        });
    }
}

// Create the module loader instance
$moduleManager = new SimpleModuleLoader();

// Try to load the module
$moduleManager->loadModule('\Acme\Example\MyModule', $slim);
