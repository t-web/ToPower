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
// 加载类
include "autoload.php";//必须包含A命名空间的文件

use ToPower\tClass;
use Silex\Application;
$app = new Silex\Application();


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views'
));
// $app->get('/', function () use ($app) {
//     return $app['twig']->render('index.html');
// });
$app->get('/kk/{variable}', function ($variable) use ($app) {
    return $variable();
});

//require_once __DIR__.'/app.php'; 
$app->get('/w/{variable}', function ($variable) use ($app) {
   return $app['twig']->render('index.html', array('variable' => $variable));
 
  
});

// $app->run();

// // function kk(){
// //    $obj = new tClass();
// //  return 'llllll';
// // }

// //use ToPower\tClass;
// // use Silex\Application;
// // use MJanssen\Provider\RoutingServiceProvider;
// // // $obj = new tClass();
// // $app = new Application();
// // $routingServiceProvider = new RoutingServiceProvider();

// // $routes = array(
// // //     'foo' => array(
// // //         //'name' => 'foo', --> you can omit the route name if a key is set
// // //         'pattern' => '/foo',
// // //         'controller' => 'Foo\Controller\FooController::fooAction',
// // //         'method' => array('get', 'post', 'put', 'delete', 'options', 'head')
// // //     ),
// // //     'baz' => array(
// // //         //'name' => 'baz', --> you can omit the route name if a key is set
// // //         'pattern' => '/baz',
// // //         'controller' => 'Baz\Controller\BazController::bazAction',
// // //         'method' => 'get'
// // //     ),
// //     'login' => array(
// //         //'name' => 'baz', --> you can omit the route name if a key is set
// //         'pattern' => '/login',
// //         'controller' => '\controller\Controller::index',
// //         'method' => 'get'
// //     )
// // );

// // $routingServiceProvider->addRoutes($app, $route);
// use Silex\Application;
// use AchrafSoltani\Provider\RoutingServiceProvider;
// use Symfony\Component\HttpFoundation\Response;

// $app = new Application();
// $app['debug'] = true;

// // Registering
// $app->register(new RoutingServiceProvider());

// // Defining routes
// // You could also implement a custom routes loader from different locations and server a RouteCollection
// // instance throough : $app['routing']->addRoutes($routes, $prefix);
// $route = new Symfony\Component\Routing\Route('/', array('controller' => 'controller\Controller::index'));
// // setting methods is optional
// $route->setMethods(array('GET', 'POST'));

// $route2 = new Symfony\Component\Routing\Route('/hello', array('controller' => 'Foo\Controller\MainController::hello'));

// $app['routing']->addRoute('home', $route);
// $app['routing']->addRoute('hello', $route2);

// // call this rigth before $app->run();
// $app['routing']->route();
// $app->run();
 