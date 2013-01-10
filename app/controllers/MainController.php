<?php

class MainController extends Controller{

	public function actionIndex(){
		// echo 'controller_main : action_index';

		$this->render('index.php', array('title' => 'Hello Controller Main'));
	}

}