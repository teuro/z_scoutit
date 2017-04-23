<?php

class VarastoController extends Zend_Controller_Action {
    public function indexAction() {
		$ostoAdapter = new My_Adapters_OstoAdapter;
		$this->view->ostot = $ostoAdapter->fetchAll();
    }
	
	public function naytaAction() {
		$ostoAdapter = new My_Adapters_OstoAdapter;
		$this->view->ostot = $ostoAdapter->find_all_actions($this->_getParam("id"));
    }
}

