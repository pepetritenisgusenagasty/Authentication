<?php

namespace App\Controllers;

use SLim\Views\Twig as View;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\User;


class HomeController extends Controller
{
	public function index($request, $response)
	{
		// $users = User::where('email', 'monogasppar@gmail.com')->first();
		// $users = Capsule::table('users')->where('id', '=', 1)->get();
		// var_dump($users->email);
		// die();
		// var_dump($request->getParam('name'));
		
		return $this->container->view->render($response, 'home.twig');
	}
}
