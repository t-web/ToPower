Slender Module Loader
===
[![Latest Stable Version](https://poser.pugx.org/slender/module-manager/v/stable.svg)](https://packagist.org/packages/slender/module-manager) [![Total Downloads](https://poser.pugx.org/slender/module-manager/downloads.svg)](https://packagist.org/packages/slender/module-manager) [![License](https://poser.pugx.org/slender/module-manager/license.svg)](https://packagist.org/packages/slender/module-manager)

[![Build Status](https://travis-ci.org/alanpich/Slender-ModuleManager.svg?branch=develop)](https://travis-ci.org/alanpich/Slender-ModuleManager) [![Coverage Status](https://coveralls.io/repos/alanpich/Slender-ModuleManager/badge.png?branch=develop)](https://coveralls.io/r/alanpich/Slender-ModuleManager?branch=develop) 

---

Simple Module loading for decoupled application components


---

## Installation

Install via composer:

```
composer require slender/module-loader
```

## Usage

```php
<?php
    
use \Slender\ModuleLoader\ModuleLoader;
use \Slender\ModuleLoader\ModuleInterface;


$app = new \Slim\App();

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



```



## Module Locators
Module Locators are responsible for translating a module identifier string into a _callable_ object that can be invoked to load the module. Module Locators must implement `Slender\ModuleLoader\ModuleLocatorInterface`

ModuleManager includes two default Locators, which are loaded by default when using the `SimpleModuleLoader`. 

#### Slender Module Locator 
`Slender\ModuleLoader\Locator\SlenderModuleLocator`
This locator will use the module identifier as a fully-qualified class name and attempt to load the class as long as it implements `Slender\ModuleLoader\ModuleInterface`.


#### Invokable Class Locator
`Slender\ModuleLoader\Locator\InvokableClassModuleLocator`
This locator will use the module identifier as a fully-qualified class name and attempt to load the class and then invoke it.
