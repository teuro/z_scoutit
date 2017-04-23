<?php

class KayttajaController extends Zend_Controller_Action {
	private $users;
	private $user;
	private $oikeustasot;
	
    public function init() {
		$this->users = new My_Adapters_UserAdapter;
    }

    public function indexAction() {
		$this->view->users = $this->users->fetchAll();
    }
	
	public function paivitaAction() {
		$this->view->user_id = $this->getRequest()->getParam('id');
		$this->user = $this->users->find($this->_getParam("id"));
		$this->userForm = new My_Forms_User_Info(Array('user' => $this->user));
		$this->oikeustasot = new My_Adapters_RightsAdapter;
		
		$passwordOpts = array('requireAlpha' => true, 'requireNumeric' => true, 'minPasswordLength' => 5, 'noSequence' => true, 'maxSequenceLetters' => 3);
		$pwValidator = new My_Validators_PasswordValidator($passwordOpts);
		
		if ($this->getRequest()->getParam('id')) {
			$this->view->user = $this->user;
			
			$this->view->userForm = $this->userForm;
			
			if ($this->getRequest()->isPost()) {
				if ($this->userForm->isValid($_POST)) {
					$this->user->set_firstname($_POST['etunimi']);
					$this->user->set_surname($_POST['sukunimi']);
					$this->user->set_email($_POST['email']);
					$this->user->set_phone($_POST['puhelin']);
					$this->user->set_rightlevel($_POST['oikeustaso']);
					$this->user->set_id($_POST['user_id']);
					
					if (isset($_POST['salasana']) && !empty($_POST['salasana'])) {
						if ($this->user->change_password($_POST['salasana'], $_POST['uudestaan'])) {
							$this->users->save($this->user);
							$this->_redirect('/kayttaja');
						} else {
							$this->view->message = "Annetut salasanat eivät täsmää";
						}
					} else {
						$this->users->save($this->user);
						$this->_redirect('/kayttaja');
					}
				} 
			}
		}
	}
	
	 public function uusiAction() {
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity() && $auth->getIdentity()->oikeustaso->get_rightlevel() > 8) {
			$this->users = new My_Adapters_UserAdapter;
			
			$registerForm = new My_Forms_User_Info;
			
			$this->view->registerForm = $registerForm;
			$this->view->passwordRequirements = $registerForm->getPasswordRequirements();
			
			if ($this->getRequest()->isPost()) {
				if ($registerForm->isValid($_POST)) {
					if ($_POST['salasana'] == $_POST['uudestaan']) {
						$this->user = new My_Models_User($_POST['tunnus'], $_POST['etunimi'], $_POST['sukunimi'], $_POST['email'], $_POST['puhelin'], 1, SHA1($_POST['salasana']), -1);
						try {
							$this->users->save($this->user);
							$this->_redirect('/kayttaja');
						} catch (Exception $e) {
							$this->view->errorMessage = $e->getMessage();
						}
					} else {
						$this->view->errorMessage = "Salasanat eivät täsmää";
					}
				}
			} 
		} else {
			$this->view->errorMessage = "Kirjaudu sisään ensin";
		}
	}
}
