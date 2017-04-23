<?php

class VarauksetController extends Zend_Controller_Action {
    public function init() {
		$this->form = new My_Forms_Reserve_Reserve;
    }

    public function indexAction() {
		$varaukset = new My_Adapters_VarausAdapter;
		
		$this->view->varaukset = $varaukset->fetchAll();
    }
	
	public function varaaAction() {
		$varaukset = new My_Adapters_VarausAdapter;
		$auth = Zend_Auth::getInstance();
		
		if ($auth->hasIdentity()) {
			if ($this->getRequest()->getParam('id')) {
				$this->view->varaa_lomake = $this->form;
				
				$tuoteAdapter = new My_Adapters_MaterialAdapter;
				$ostot = new My_Adapters_OstoAdapter;
				
				$tuote = $tuoteAdapter->find($this->getRequest()->getParam('id'));
				
				$selectAmount = new Zend_Form_Element_Select('amount', array('label' => 'Määrä:', 'required' => true, 'order' => 3));
				
				for ($i = 1; $i <= ($tuote->get_amount() - $tuote->get_reserved()); ++$i) {
					$selectAmount->addMultiOption($i, $i);
				}
				
				$this->form->addElement($selectAmount);
				
				$this->form->addElement('hidden', 'material_id', array('value' => $this->getRequest()->getParam('id'), 'order' => 0));
				
				$this->view->lomake = $this->form;
				$this->view->tuote = $tuote;
			}
		}
		 
		if ($this->getRequest()->isPost()) {
			if ($this->form->isValid($_POST)) {
				try {
					$tuoteAdapter = new My_Adapters_MaterialAdapter;
					$userAdapter = new My_Adapters_UserAdapter;
					
					$material = $tuoteAdapter->find($_POST['material_id']);
					$varaaja = $userAdapter->find($auth->getInstance()->getIdentity()->id);
					
					$alkaa = strtotime($_POST['begin']);
					$loppuu = strtotime($_POST['end']);
					
					if ($alkaa > $loppuu) {
						throw new Exception("Varaus ei voi alkaa päättymisen jälkeen!");
					} else if ($alkaa < time() - 86400) {
						throw new Exception("Varaus ei voi alkaa menneisyydessä!");
					} else if ($loppuu < time() - 86400) {
						throw new Exception("Varaus ei voi loppua menneisyydessä!");
					}
					
					$material->set_reserved($_POST['amount']);
					
					$varaus = new My_Models_Varaus($material, $_POST['begin'], $_POST['end'], $varaaja, $_POST['amount'], NULL, NULL);
					$varaukset->save($varaus);
					$this->_redirect('/index');
				} catch (Exception $e) {
					echo "<p>{$e->getMessage()}</p>";
				}
			} else {
				$this->view->viesti = "Ei onnistunut";
			}
		} 
    }
	
	public function palautaAction() {
		$varaukset = new My_Adapters_VarausAdapter;
		/** Varauksen id ei tuotteenn **/
		if ($this->getRequest()->getParam('id')) {
			$varaus = $varaukset->find('id', $this->getRequest()->getParam('id'));
			$varaukset->save($varaus);
			$this->_redirect('/varaukset');
		}
	}
}

