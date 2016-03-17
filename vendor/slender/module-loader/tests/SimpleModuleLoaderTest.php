<?php
use Slender\ModuleLoader\SimpleModuleLoader;

/**
 * Created by PhpStorm.
 * User: alanp
 * Date: 13/12/14
 * Time: 13:52
 */

class SimpleModuleLoaderTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }


    public function testConstructorAddsDefaultLocators()
    {
        $refP = new ReflectionProperty("Slender\\ModuleLoader\\SimpleModuleLoader","locators");
        $refP->setAccessible(true);

        $loader = new SimpleModuleLoader();
        $locators = $refP->getValue($loader);

        $this->assertCount(2, $locators);
        $this->assertInstanceOf("Slender\\ModuleLoader\\ModuleLocatorInterface", $locators[0]);
        $this->assertInstanceOf("Slender\\ModuleLoader\\ModuleLocatorInterface", $locators[1]);

        $this->assertInstanceOf("Slender\\ModuleLoader\\Locator\\SlenderModuleLocator", $locators[0]);
        $this->assertInstanceOf("Slender\\ModuleLoader\\Locator\\InvokableClassLocator", $locators[1]);

    }


    public function testConstructorAllowsAdditionalLocators()
    {
        $refP = new ReflectionProperty("Slender\\ModuleLoader\\SimpleModuleLoader","locators");
        $refP->setAccessible(true);

        $mockLocator = Mockery::mock("Slender\\ModuleLoader\\ModuleLocatorInterface");

        $loader = new SimpleModuleLoader( $mockLocator );
        $locators = $refP->getValue($loader);

        $this->assertCount(3, $locators);
        $this->assertInstanceOf("Slender\\ModuleLoader\\ModuleLocatorInterface", $locators[0]);
        $this->assertInstanceOf("Slender\\ModuleLoader\\ModuleLocatorInterface", $locators[1]);
        $this->assertInstanceOf("Slender\\ModuleLoader\\ModuleLocatorInterface", $locators[2]);

        $this->assertInstanceOf("Slender\\ModuleLoader\\Locator\\SlenderModuleLocator", $locators[0]);
        $this->assertInstanceOf("Slender\\ModuleLoader\\Locator\\InvokableClassLocator", $locators[1]);
    }


    public function testConstructorThrowsInvalidArgumentException()
    {
        $refP = new ReflectionProperty("Slender\\ModuleLoader\\SimpleModuleLoader","locators");
        $refP->setAccessible(true);

        $notAnAcceptableArgument = "INVALID";

        $this->setExpectedException("Slender\\ModuleLoader\\Exception\\InvalidArgumentException");
        new SimpleModuleLoader( $notAnAcceptableArgument );

    }

}
