<?php
/**
 * Slender Module Loader
 *
 * @author      Alan Pich <alan.pich@gmail.com>
 * @copyright   2015 Alan Pich
 * @link        http://github.com/alanpich/Slender-ModuleLoader
 * @license     http://github.com/alanpich/Slender-ModuleLoader/blob/master/LICENSE
 * @package     Slender\ModuleLoader
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Slender\ModuleLoader;

use Slender\ModuleLoader\Exception\AttemptedToLoadModuleTwiceException;
use \Slender\ModuleLoader\ModuleLocatorInterface;

/**
 * Class ModuleLoader
 *
 * @package Slender\ModuleLoader
 */
class ModuleLoader
{

    /**
     * Array holding all registered ModuleLoader's
     *
     * @var ModuleLocatorInterface[]
     */
    protected $locators = [];

    /**
     * Array holding identifiers of all loaded modules
     *
     * @var string[]
     */
    protected $loadedModules = [];



    /**
     * Add a ModuleLocator instance that will be used in the order
     * they were added to locate a named module.
     *
     * @param ModuleLocatorInterface $locator
     * @return $this
     */
    public function addLocator(ModuleLocatorInterface $locator)
    {
        if (!in_array($locator, $this->locators)) {
            $this->locators[] = $locator;
        }
        return $this;
    }


    /**
     * Remove a locator that has previously been added
     *
     * @param ModuleLocatorInterface $locator
     * @return $this
     */
    public function removeLocator(ModuleLocatorInterface $locator)
    {
        if(($key = array_search($locator, $this->locators)) !== false) {
            unset($this->locators[$key]);
        }
        return $this;
    }


    /**
     * Load a module by its identiier
     *
     * @param string $moduleIdentifier
     * @param mixed  $arg
     * @throws AttemptedToLoadModuleTwiceException if module is already loaded
     * @return $this
     */
    public function load( $moduleIdentifier, $arg = null )
    {
        if(in_array($moduleIdentifier, $this->loadedModules)){
            throw new AttemptedToLoadModuleTwiceException("Attempted to load module $moduleIdentifier multiple times");
        }
        foreach($this->locators as $locator){
            /** @var Callable $invoker */
            if(($invoker = $locator->find($moduleIdentifier)) !== false){
                // Invoke the module
                if(is_callable($invoker)) {
                    call_user_func($invoker, $arg);
                    $this->loadedModules[] = $moduleIdentifier;
                }
                break;
            }
        }
        return $this;
    }


    /**
     * Returns if a module has already been loaded
     *
     * @param string $moduleIdentifier
     * @return bool
     */
    public function isLoaded( $moduleIdentifier )
    {
        return in_array($moduleIdentifier, $this->loadedModules);
    }


} 
