<?php

class TuoteController extends Zend_Controller_Action {
    public function init() {
		$this->user = $auth = Zend_Auth::getInstance();
    }

    public function indexAction() {

    }
	
	public function naytaAction() {
		if ($this->getRequest()->getParam('id')) {
			$materiaali = new My_Adapters_MaterialAdapter;
			
			$tuote = $materiaali->find($this->_getParam("id"));
			
			$this->view->tuote = $tuote;
		}
    }
	
	public function ostaAction() {
		if ($this->user->hasIdentity()) {
			$lomake = new My_Forms_Material_OstaTuote;
			$this->view->lomake = $lomake;
			
			if ($this->getRequest()->isPost()) {
				if ($lomake->isValid($_POST)) {
					$tuoteAdapter = new My_Adapters_MaterialAdapter;
					$userAdapter = new My_Adapters_UserAdapter;
					$ostoAdapter = new My_Adapters_OstoAdapter;
					
					$tuote = $tuoteAdapter->find($_POST['tuote_id']);
					$varaaja = $userAdapter->find($this->user->getIdentity()->id);
					
					$osto = new My_Models_Osto($tuote, NULL, $_POST['ostohinta'], $_POST['ostomaara'], $_POST['tositenumero'], $varaaja);
					$ostoAdapter->save($osto);
					$this->_redirect('/index');
				} else {
					$this->view->message = "Ei onnistunut";
				}
			} 
		} else {
			$this->view->message = "Kirjaudupa sisään.";
		}
	}
	
	public function paivitaAction() {
		$updateForm = new Zend_Form;
		
		$tuoteAdapter = new My_Adapters_MaterialAdapter;
		$classAdapter = new My_Adapters_ClassAdapter;
		$categoryAdapter = new My_Adapters_CategoryAdapter;
		$placeAdapter = new My_Adapters_PlaceAdapter;
		
		$tuote = $tuoteAdapter->find($this->_getParam("id"));
		
		$updateForm->addElement('hidden', 'material', array('value' => $tuote->get_id(), 'order' => 0));
		$updateForm->addElement('text', 'name', array('value' => $tuote->get_name(), 'label' => 'nimi'));
		$updateForm->addElement('text', 'description', array('value' => $tuote->get_description(), 'label' => 'kuvaus'));
		
		$selectPlace = new Zend_Form_Element_Select('place', array('label' => 'paikka:', 'required' => true));
		$places = $placeAdapter->fetchAll();
				
		foreach ($places AS $place) {
			$selectPlace->addMultiOption($place->get_id(), $place->get_name());
		}
		
		$updateForm->addElement($selectPlace);
		$updateForm->populate(Array('place' => $tuote->get_place()->get_id()));
		
		$selectCategory = new Zend_Form_Element_Select('category', array('label' => 'kategoria:', 'required' => true));
		$categorys = $categoryAdapter->fetchAll();
				
		foreach ($categorys AS $category) {
			$selectCategory->addMultiOption($category->get_id(), $category->get_name());
		}
		
		$updateForm->addElement($selectCategory);
		$updateForm->populate(Array('category' => $tuote->get_category()->get_id()));
		
		$selectClass = new Zend_Form_Element_Select('class', array('label' => 'luokka:', 'required' => true));
		$classes = $classAdapter->fetchAll();
				
		foreach ($classes AS $class) {
			$selectClass->addMultiOption($class->get_id(), $class->get_name());
		}
		
		$updateForm->addElement($selectClass);
		$updateForm->populate(Array('class' => $tuote->get_class()->get_id()));
		
		$updateForm->addElement(new Zend_Form_Element_Submit('submit'));
		
		$this->view->updateForm = $updateForm;
		
		if ($this->getRequest()->isPost()) {
			if ($updateForm->isValid($_POST)) {
				$materialAdapter = new My_Adapters_MaterialAdapter;
				$classAdapter = new My_Adapters_ClassAdapter;
				$categoryAdapter = new My_Adapters_CategoryAdapter;
				$placeAdapter = new My_Adapters_PLaceAdapter;
				
				$material 	= $materialAdapter->find($_POST['material']);
				$class 		= $classAdapter->find($_POST['class']);
				$category 	= $categoryAdapter->find($_POST['category']);
				$place 		= $placeAdapter->find($_POST['place']);
				
				$material->set_name($_POST['name']);
				$material->set_description($_POST['description']);
				$material->set_class($class);
				$material->set_category($category);
				$material->set_place($place);
				
				$materialAdapter->save($material);
				
				$this->_redirect('/index');
			} else {
				$this->view->message = "Ei onnistunut";
			}
		} 
	}
	
	public function uusiAction() {
		$materialForm = new My_Forms_Material_New;
		
		$this->view->materialForm = $materialForm;
		
		if ($this->getRequest()->isPost()) {
			if ($materialForm->isValid($_POST)) {
				$materialAdapter = new My_Adapters_MaterialAdapter;
				$classAdapter = new My_Adapters_ClassAdapter;
				$categoryAdapter = new My_Adapters_CategoryAdapter;
				$placeAdapter = new My_Adapters_PlaceAdapter;
				
				$class 		= $classAdapter->find($_POST['class']);
				$category 	= $categoryAdapter->find($_POST['category']);
				$place 		= $placeAdapter->find($_POST['place']);
				
				$class->set_category($category);
				
				$material = new My_Models_Material($_POST['name'], $_POST['description'], $class, $place);

				try {
					$materialAdapter->save($material);
					$this->_redirect('/index');
				} catch (Exception $e) {
					$this->view->errorMessage = $e->getMessage();
				}
			}
		} 
    }
}

