<?php
class My_Forms_Material_New extends Zend_Form {
	public function init() {
		$auth = Zend_Auth::getInstance();
		
		$classAdapter = new My_Adapters_ClassAdapter;
		$categoryAdapter = new My_Adapters_CategoryAdapter;
		$placeAdapter = new My_Adapters_PlaceAdapter;
		
		$categorys = $categoryAdapter->fetchAll();
		$classes = $classAdapter->fetchAll();
		$places = $placeAdapter->fetchAll();
		
		$this->setMethod('post');
		$this->setAction($this->getView()->url(Array('controller' => 'tuote', 'action' => 'uusi')));
		
		$categoryList = new Zend_Form_Element_Select('category', array(
            'label'      => 'Kategoria:',
            'required'   => true
        ));
				
		foreach ($categorys AS $category) {
			$categoryList->addMultiOption($category->get_id(), $category->get_name());
		}
		
		$classList = new Zend_Form_Element_Select('class', array(
            'label'      => 'Luokka:',
            'required'   => true
        ));
		
		foreach ($classes AS $class) {
			$classList->addMultiOption($class->get_id(), $class->get_name());
		}
		
		$placeList = new Zend_Form_Element_Select('place', array(
            'label'      => 'Paikka:',
            'required'   => true
        ));
		
		foreach ($places AS $place) {
			$placeList->addMultiOption($place->get_id(), $place->get_name());
		}
				
		$this->addElement('text', 'name', array(
            'label'      => 'Nimi:',
            'required'   => true,
            'filters'    => array('StringTrim'),
        ));
		
		$this->addElement('text', 'description', array(
            'label'      => 'Kuvaus:',
            'required'   => true,
            'filters'    => array('StringTrim'),
         ));
		
		$this->addElement($categoryList);
		$this->addElement($classList);
		$this->addElement($placeList);
		
		 $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'lisää',
        ));
    }
}