<?php
use Slender\ModuleLoader\Locator\SlenderModuleLocator;
use Slender\ModuleLoader\ModuleInterface;




class ExampleModule implements ModuleInterface
{
    public function invokeModule($container = null)
    {
        throw new \Exception("TEST PASSED");
    }
}

class InvalidModule {

}




class SlenderModuleLocatorTest extends PHPUnit_Framework_TestCase
{


    public function testFind()
    {
        $locator = new SlenderModuleLocator();
        $callable = $locator->find("ExampleModule");

        $this->assertTrue(is_callable($callable));

        $this->setExpectedException("Exception","TEST PASSED");
        $callable();

    }

    public function testFindRequiresCorrectInterface()
    {
        $locator = new SlenderModuleLocator();
        $result = $locator->find("InvalidModule");

        $this->assertFalse($result);
    }



}
