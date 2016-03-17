<?php
use Slender\ModuleLoader\Locator\InvokableClassLocator;
use Slender\ModuleLoader\ModuleInterface;




class InvokableClassLocatorTest_ExampleModule
{

    public function __invoke($container = null)
    {
        throw new \Exception("TEST PASSED");
    }
}

class InvokableClassLocatorTest_InvalidModule {

}




class InvokableClassLocatorTest extends PHPUnit_Framework_TestCase
{


    public function testFind()
    {
        $locator = new InvokableClassLocator();
        $callable = $locator->find("InvokableClassLocatorTest_ExampleModule");

        $this->assertTrue(is_callable($callable));

        $this->setExpectedException("Exception","TEST PASSED");
        $callable();

    }

    public function testFindRequiresCorrectInterface()
    {
        $locator = new InvokableClassLocator();
        $result = $locator->find("InvokableClassLocatorTest_InvalidModule");

        $this->assertFalse($result);
    }



}
