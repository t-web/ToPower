<?php

use Slender\ModuleLoader\ModuleLoader;

class ModuleLoaderTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Tests `addLocator` adds the locator
     */
    public function testAddLocator()
    {
        $loader = new ModuleLoader();

        $refP = new ReflectionProperty("Slender\\ModuleLoader\\ModuleLoader", 'locators');
        $refP->setAccessible(true);

        $locator = new Slender\ModuleLoader\Locator\SlenderModuleLocator();
        $loader->addLocator($locator);

        $value = $refP->getValue($loader);
        $this->assertInternalType('array',$value);
        $this->assertCount(1, $value);
        $this->assertEquals( $locator, $value[0]);
    }


    /**
     * Tests `addLocator` prevents duplicates being added
     */
    public function testAddLocatorPreventsDuplicates()
    {
        $loader = new ModuleLoader();

        $refP = new ReflectionProperty($loader, 'locators');
        $refP->setAccessible(true);

        $locator = new Slender\ModuleLoader\Locator\SlenderModuleLocator();
        $loader->addLocator($locator);
        $loader->addLocator($locator);

        $value = $refP->getValue($loader);
        $this->assertInternalType('array',$value);
        $this->assertCount(1, $value);
        $this->assertEquals( $locator, $value[0]);
    }


    /**
     * Tests `removeLocator` works
     */
    public function testRemoveLocator()
    {
        $loader = new ModuleLoader();
        $locator = new Slender\ModuleLoader\Locator\SlenderModuleLocator();

        $refP = new ReflectionProperty($loader, 'locators');
        $refP->setAccessible(true);
        $refP->setValue($loader,[$locator]);

        $loader->removeLocator($locator);

        $value = $refP->getValue($loader);
        $this->assertInternalType('array',$value);
        $this->assertCount(0, $value);
    }


    /**
     * Tests `isLoaded` works as expected
     */
    public function testIsLoaded()
    {
        $loader = new ModuleLoader();
        $MODULE = 'MyModule';

        $refP = new ReflectionProperty($loader, 'loadedModules');
        $refP->setAccessible(true);
        $refP->setValue($loader,[$MODULE]);


        $value = $loader->isLoaded($MODULE);
        $this->assertInternalType('boolean',$value);
        $this->assertTrue($value);

        $this->assertFalse($loader->isLoaded("UNKNOWN_MODULE"));
    }



    public function testLoad()
    {
        $loader = new ModuleLoader();
        $MODULE = "MyModule";
        $CALLABLE = [new StdClass, 'toString'];

        $refP = new ReflectionProperty("Slender\\ModuleLoader\\ModuleLoader","loadedModules");
        $refP->setAccessible(true);

        $locator = Mockery::mock('Slender\ModuleLoader\Locator\InvokableClassLocator');
        $locator
            ->shouldReceive('find')
            ->once()
            ->andReturn(function($arg=null){});

        $locP = new ReflectionProperty("Slender\\ModuleLoader\\ModuleLoader","locators");
        $locP->setAccessible(true);
        $locP->setValue($loader,[$locator]);


        $loader->load($MODULE);


        $loaded = in_array($MODULE, $refP->getValue($loader) );

        $this->assertTrue($loaded);

    }


    public function testLoadPreventsDuplicates()
    {
        $loader = new ModuleLoader();
        $MODULE = "MyModule";

        $refP = new ReflectionProperty("Slender\\ModuleLoader\\ModuleLoader","loadedModules");
        $refP->setAccessible(true);

        $locator = Mockery::mock('Slender\ModuleLoader\Locator\InvokableClassLocator');
        $locator
            ->shouldReceive('find')
            ->once()
            ->andReturn(function($arg=null){});

        $locP = new ReflectionProperty("Slender\\ModuleLoader\\ModuleLoader","locators");
        $locP->setAccessible(true);
        $locP->setValue($loader,[$locator]);


        $this->setExpectedException('Slender\ModuleLoader\Exception\AttemptedToLoadModuleTwiceException');

        $loader->load($MODULE);
        $loader->load($MODULE);
    }

} 
