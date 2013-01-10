<?php

class Controller{
	
	protected $layout = 'default';
	
	protected $controller_name = null;

	protected $view_dir;


	public function setControllerName($controller_name){
		$this->controller_name = $controller_name;
	}

	public function setViewDirectory($view_dir){
		$this->view_dir = $view_dir;
	}

	public function render($file, $data = array()){
	
		$view = $this->view_dir . ucfirst($this->controller_name) . '/' . $file;


		if(!file_exists($view)){
			exit('No file view available');
		}


		extract($data);
        
        require($view);	
	}
}