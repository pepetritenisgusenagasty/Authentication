<?php

namespace App\Controllers\Auth;

use App\Models\User;
use Respect\Validation\Validator as Respect;
use App\Controllers\Controller;

class PasswordController extends Controller
{
	public function getChangePassword($request, $response)
	{
            return $this->container->view->render($response, 'auth/password/change.twig');
	}


	public function postChangePassword($request, $response)
	{
		$validation = $this->container->validator->validate($request, [

           'password_old' => Respect::noWhitespace()->notEmpty()->matchesPassword($this->container->auth->user()->password),
           'password'     => Respect::noWhitespace()->notEmpty(),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->container->router->pathFor('auth.password.change'));		
		}

		$this->container->auth->user()->setPassword($request->getParam('password'));

		$this->container->flash->addMessage('info', 'Your password is changed');

		return $response->withRedirect($this->container->router->pathFor('home'));

		// die('password changed');
	}
}
