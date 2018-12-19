<?php

namespace App\Controllers;




class Controller
{
	protected $container;

	public function __construct($container)
	{
		return $this->container = $container;
	}
}
