<?php
 
class My_Forms_Reserve_Reserve extends Zend_Form {
    public function init() {
		$this->setMethod('post');
		$this->setAction($this->getView()->url(Array('controller' => 'varaukset', 'action' => 'varaa')));
		
		$this->addElement('text', 'begin', array(
            'label'      => 'Varaus alkaa:',
			'class'		=> 'datepicker',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(6, 60))
        )));
		
		$this->addElement('text', 'end', array(
            'label'      => 'Varaus loppuu:',
			'class'		=> 'datepicker',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(6, 60))
        )));
		
		 $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'lisää',
        ));
    }
}