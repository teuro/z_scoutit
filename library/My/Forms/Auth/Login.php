<?php
class My_Forms_Auth_Login extends Zend_Form {
    public function init() {
        $this->setMethod('post');
 
        $this->addElement(
            'text', 'tunnus', array(
                'label' => 'Tunnus:',
                'required' => true,
                'filters'    => array('StringTrim'),
            ));
 
        $this->addElement('password', 'salasana', array(
            'label' => 'Salasana:',
            'required' => true,
            ));
 
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Login',
            ));
 
    }
}