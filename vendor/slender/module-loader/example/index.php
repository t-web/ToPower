<?php

use Slender\ModuleLoader\ModuleInterface;
use Slender\ModuleLoader\ModuleLoader;
use Slender\ModuleLoader\SimpleModuleLoader;

require dirname(__DIR__).'/vendor/autoload.php';


$myDI = [
    'router' => new \StdClass(),
    'config' => [
        'foo' => 'bar'
    ]
];



class MyModule implements ModuleInterface
{
    public function invokeModule($di)
    {
        echo "Module has been invoked!\n";
        echo "Foo = " . $di['config']['foo']."\n";
    }
}




$loader = new SimpleModuleLoader();
$loader
    ->load("MyModule", $myDI);
