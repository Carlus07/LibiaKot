<?php

namespace Controllers;

class HomeController extends Controller {
	
    public function index() {
		//$categories = $this->getConnection()->getRepository('User')->findAll();
		//\Doctrine\Common\Util\Debug::dump($categories);
        $this->render('home.index');
    }
}
