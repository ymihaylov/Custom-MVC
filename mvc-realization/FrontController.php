<?php 
namespace php_mvc;

class FrontController {
	private static $instance = null;
	private $name_space = null;
	private $controller = null;
	private $method = null;


	public function __construct()
	{

	}

	/*
		Когато бъде извикан, FrontController-а трябва да намери кой router трябва да бъде използван
	 */
	public function dispatch()
	{
		$a = new \php_mvc\Routers\DefaultRouter();
		$_uri = $a->get_uri();
		$routes = \php_mvc\App::get_instance()->get_config()->routes;

		// Set the namespace by route
		if (is_array($routes) AND count($routes)>0)
		{
			foreach ($routes as $key => $value) 
			{
				if (stripos($_uri, $key) === 0 AND isset($value['namespace']))
				{
					$this->name_space = $value['namespace'];
					break;
				}	
			}
		}
		else
		{
			throw new \Exception('Default route is missing', 500);
		}

		// Using the default namespace
		if ($this->name_space == null AND isset($routes['*']['namespace']))
		{
			$this->name_space = $routes['*']['namespace'];
		}
		elseif ($this->name_space == null AND !$routes['*']['namespace'])
		{
			throw new \Exception("Default route missing");
		}
		echo $this->name_space;

		/* Version 1
		// Setting the current controller
		$controller = $a->get_controller();
		if ($controller == null)
		{
			$controller = $this->get_default_controller();
		}

		// Setting the current method
		$method = $a->get_method();

		if ($method == null)
		{
			$method = $this->get_default_method();
		}

		echo $controller." ".$method;*/
	}	

	public function get_default_controller()
	{
		$controller = \php_mvc\App::get_instance()->get_config()->app['default_controller'];
		if ($controller)
			return $controller;
		return 'Index';
	}

	public function get_default_method()
	{
		$method = \php_mvc\App::get_instance()->get_config()->app['default_method'];
		if($method)
			return $method;
		return 'Index';
	}

	public static function get_instance()
	{
		if (self::$instance === null)
		{
			self::$instance = new \php_mvc\FrontController();
		}

		return self::$instance;
	}
}