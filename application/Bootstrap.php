<?php
// application/Bootstrap.php
 
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
    }
	
	 public function _initAutoloader() {
		$autoloader = Zend_Loader_Autoloader::getInstance();

        return $autoloader;
    }
	
	public function _initAcl() {
		$helper= new My_Controller_Helper_Acl();
		$helper->setRoles();
		$helper->setResources();
		$helper->setPrivilages();
		$helper->setAcl();
	}
	
	protected function _initRequest() {
		$this->bootstrap('FrontController');
		$front = $this->getResource('FrontController');
		
		//$front->registerPlugin(new My_Controller_Plugin_Acl());
    }
}