<?php

namespace App\Controllers\Auth;

use App\Models\User;
use Respect\Validation\Validator as Respect;
use App\Controllers\Controller;

class AuthController extends Controller
{
	public function getSignOut($request, $response)
	{
		$this->container->auth->logout();

		return $response->withRedirect($this->container->router->pathFor('home'));
	}

	public function getSignIn($request, $response)
	{
		return $this->container->view->render($response, 'auth/signin.twig');
	}

    public function postSignIn($request, $response)
    {
    	$auth = $this->container->auth->attempt(

               $request->getParam('email'),
               $request->getParam('password')
    	);

    	//Return user to signin page if !auth
    	if (!$auth) {
    		$this->container->flash->addMessage('error', 'You could not sign up with those details');
    		return $response->withRedirect($this->container->router->pathFor('auth.signin'));
    	}
         $this->container->flash->addMessage('warning', 'Welcome back');
       return $response->withRedirect($this->container->router->pathFor('home'));    
        
   }

	public function getSignUp($request, $response)
	{
       return $this->container->view->render($response, 'auth/signup.twig');
	}
		
		public function postSignUp($request, $response)
		{
			
            $validation = $this->container->validator->validate($request, [

              'email' => Respect::noWhitespace()->notEmpty()->email()->EmailAvaliable(),
              'name'  => Respect::notEmpty()->alpha(),
              'password' => Respect::noWhitespace()->notEmpty(),
            ]);

            if ($validation->failed()) {
				return $response->withRedirect($this->container->router->pathFor('auth.signup'));
			}


			$user = User::create([
               'email' => $request->getParam('email'),
               'name'  => $request->getParam('name'),
               'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
			]);
             
             $this->container->flash->addMessage('info', 'You have been signed up');
			$this->container->auth->attempt($user->email, $request->getParam('password'));

			return $response->withRedirect($this->container->router->pathFor('home'));
		}
}
