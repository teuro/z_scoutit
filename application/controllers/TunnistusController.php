<?php

class TunnistusController extends Zend_Controller_Action {
    public function loginAction() {
        $db = Zend_Db_Table::getDefaultAdapter();
		$auth = Zend_Auth::getInstance();
 
        $loginForm = new My_Forms_Auth_Login();
		
		if ($this->getRequest()->isPost()) {
			if ($loginForm->isValid($_POST)) {
				$userAdapter = new Zend_Auth_Adapter_DbTable ($db, 'scoutit_henkilot', 'tunnus', 'tiiviste', 'id', 'oikeus_id');
				$oikeusAdapter = new My_Adapters_RightsAdapter;
	 
				$userAdapter->setIdentity($loginForm->getValue('tunnus'));
				$userAdapter->setCredential(SHA1($loginForm->getValue('salasana')));
	 
				$result = $auth->authenticate($userAdapter);
			
				if ($result->isValid()) {
					$user = $userAdapter->getResultRowObject();
					$user->oikeustaso = $oikeusAdapter->find($user->oikeus_id);
					$auth->getStorage()->write($user);
					$this->_redirect('/index');
				}
			}
		}
 
        $this->view->loginForm = $loginForm;
    }
	
	public function logoutAction() {
		Zend_Auth::getInstance()->clearIdentity();
		
		$this->_redirect('/index');
	}
}