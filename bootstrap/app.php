<?php
use Respect\Validation\Validator as v;

// Starting Session
session_start();

// Turniing off notice error
error_reporting(E_ALL & ~E_NOTICE);
require __DIR__ . '/../vendor/autoload.php';

// Instantiating Slim and adding settings to display Error
$app = new \Slim\App([
   'settings' => [
       
       'displayErrorDetails' => true,
       'determineRouteBeforeAppMiddleware' => true, 
       'addContentLengthHeader' => false,
   ]

]);

$container = $app->getContainer();

use Illuminate\Database\Capsule\Manager as Capsule;

// Instatiating Illuminate
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'admission',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['auth'] = function($container){
   return new App\Auth\Auth;
};

$container['flash'] = function ($container) {
    return new Slim\Flash\Messages;
};

$container['view'] = function($container){
     $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
               'cache' => false,
     ]);

     $view->addExtension(new \Slim\Views\TwigExtension(
            $container->router,
            $container->request->geturi()
     ));

     $view->getEnvironment()->addGlobal('auth', [
         'check' => $container->auth->check(),
         'user'  => $container->auth->user()
     ]);

     $view->getEnvironment()->addGlobal('flash', $container->flash);
       
       return $view;
};

$container['PasswordController'] = function($container){
  return new App\Controllers\Auth\PasswordController($container);
};
// Pulling files into containers
$container['csrf'] = function($container){
  return new Slim\Csrf\Guard;
};

$container['HomeController'] = function($container)
{
	return new App\Controllers\HomeController($container);
};

$container['AuthController'] = function($container)
{
  return new App\Controllers\Auth\AuthController($container);
};

$container['validator'] = function($container){
   return new App\Validation\validator;
};




// Adding Middleware
$app->add(new App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new App\Middleware\OldInputMiddleware($container));
$app->add(new App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);
v::with('App\\Validation\\Rules\\');


require __DIR__ . '/../app/route.php';