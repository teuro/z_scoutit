<?php
 
class My_Forms_Material_OstaTuote extends Zend_Form {
    public function init() {
		$auth = Zend_Auth::getInstance();
		
		$this->setMethod('post');
		$this->setAction($this->getView()->url(Array('controller' => 'tuote', 'action' => 'osta')));
		
		$tuoteLista = new Zend_Form_Element_Select('tuote_id', array(
            'label'      => 'Tuote:',
            'required'   => true
        ));
		
		$this->addElement('hidden', 'ostaja', array(
			'value'	=>	$auth->getIdentity()->id
		));
		
		$materialAdapter = new My_Adapters_MaterialAdapter;
		$materials = $materialAdapter->fetchAll();
		
		foreach ($materials AS $material) {
			$tuoteLista->addMultiOption($material->get_id(), $material->get_name());
		}
		
		$this->addElement($tuoteLista);
		
		$this->addElement('text', 'ostomaara', array(
            'label'      => 'Ostomaara:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'alnum')
        )));
		
		$this->addElement('text', 'ostohinta', array(
            'label'      => 'Ostohinta:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'float')
        )));
		
		$this->addElement('text', 'tositenumero', array(
            'label'      => 'Tositenumero:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'alnum')
        )));
		
		 $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'lisää',
        ));
    }
}