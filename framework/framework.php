<?php

class Framework{

	private $framework_dir = null;

	private $app_dir = null;
	private $app_config = array();

	private $request = array();
	private $routes = array();

	public function __construct($config){
		
		// Init directory
		$this->framework_dir = dirname(__FILE__);
		$this->app_dir = dirname($config);

		// Init config
		$this->setAppConfig($config);

		// Init load libraries
		$this->loadLibraries();
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
	}

	private function dispatcher(){

	}

	public function run(){
		$this->request();
		$this->router();
		$this->dispatcher();
	}

}