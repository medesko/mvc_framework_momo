<?php

class Controller{
	
	protected $layout = 'default';
	
	function render($file, $data = array()){
		
		extract($data);
        ob_start();
        require($file);
        $str = ob_get_contents();
        ob_end_clean();
        return $str;		
	}
}