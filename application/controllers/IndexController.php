<?php

class IndexController extends Zend_Controller_Action {
    public function init() {
		$materiaali = new My_Adapters_MaterialAdapter;

		$tuotteet = $materiaali->fetchAll();
		
		$this->view->tuotteet = $tuotteet;
    }

    public function indexAction() {
    }
}
