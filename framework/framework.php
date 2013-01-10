<?php

class Framework{

	private $framework_dir = null;

	private $app_dir = null;
	private $app_config = array();

	private $request = array();
	private $routes = array();
	private $rule = null;

	public function __construct($config){
		
		// Init directory
		$this->framework_dir = dirname(__FILE__);
		$this->app_dir = dirname($config);

		// Init config
		$this->setAppConfig($config);

		// Init load libraries
		$this->loadLibraries();

		// Init load application
		$this->loadApplication();
	}

	private function setAppConfig($config){
		$this->app_config = include_once($config);
	}

	private function loadLibraries(){
		$libraries = scandir($this->framework_dir . '/libraries');

		foreach($libraries as $i => $librarie) {
			if(pathinfo($librarie, PATHINFO_EXTENSION) == 'php'){
				require_once($this->framework_dir . '/libraries/' . $librarie);
			}
		}
	}

	private function loadApplication(){

		$controllers_dir = scandir($this->app_dir . '/../controllers/');
		$models_dir = scandir($this->app_dir . '/../models/');


		foreach($controllers_dir as $i => $controller) {

			if(pathinfo($controller, PATHINFO_EXTENSION) == 'php'){

				require_once($this->app_dir . '/../controllers/' . $controller);
			}
		}

		foreach($models_dir as $i => $model) {

			if(pathinfo($model, PATHINFO_EXTENSION) == 'php'){

				require_once($this->app_dir . '/../models/' . $model);
			}
		}

	}


	private function request(){
		$request = trim(substr($_SERVER['REQUEST_URI'], strlen($website_url)), '/');

		$request = strtolower($request);
		$request = explode('?', $request, 2);
		$request = array_slice($request, 1);
		if(isset($request[count($request)-1]) && empty($request[count($request)-1])) unset($request[count($request)-1]);

		if(count($request) == 1){
			$request = $request[0];

			$request = explode('&', $request);

			$this->request = array(
				'controller' => isset($request[0]) ? $request[0] : '',
				'action' => (isset($_GET['action']) && !empty($_GET['action'])) ? $_GET['action'] : 'index',
			);
		}
		else{
			$this->request = array(
				'controller' => '',
				'action' => 'index'
			);
		}
	}

	private function router(){
		$this->routes = $this->app_config['routes'];

		if(empty($this->request['controller']) && array_key_exists($this->request['controller'], $this->routes)){
			// OK
			$this->rule = '';
		}
		elseif($this->request['action'] == 'index' && array_key_exists($this->request['controller'], $this->routes)) {
			// OK
			$this->rule = $this->request['controller'];
		}
		elseif(array_key_exists($this->request['controller'] . '/' . $this->request['action'], $this->routes)) {
			// OK
			$this->rule = $this->request['controller'] . '/' . $this->request['action'];
		}
		else{
			exit('No route available');
		}
	}

	private function dispatcher(){

		$rule = $this->rule;
		$route = $this->routes[$rule];
		$route = explode('/', $route);


		// Define controller / action
		$controller = $route[0] . 'Controller';
		$action = 'action'. ucfirst($route[1]);
		
		if(class_exists($controller)){
			$controller = new $controller;
			$controller->setControllerName($route[0]);
			$controller->setViewDirectory($this->app_dir . '/../views/');

			if(method_exists($controller, $action)){
				$controller->$action();
			}
			else{
				exit('No action available');
			}
			
		}
		else{
			exit('No controller available');
		}

	}

	public function run(){		
		$this->request();
		$this->router();
		$this->dispatcher();
	}

}