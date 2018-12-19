<?php
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

// Route to Homepage
$app->get('/', 'HomeController:index')->setName('home');

$app->group('', function(){

// Signup Page
$this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
$this->post('/auth/signup', 'AuthController:postSignUp');

// Signin Page
$this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
$this->post('/auth/signin', 'AuthController:postSignIn');

})->add(new GuestMiddleware($container));

$app->group('', function(){

// Change Password Page
$this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
$this->post('/auth/password/change', 'PasswordController:postChangePassword');


$this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');
})->add(new AuthMiddleware($container));

