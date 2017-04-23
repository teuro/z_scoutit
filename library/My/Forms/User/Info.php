<?php
 
class My_Forms_User_Info extends Zend_Form {
	private $requirements;
	public $passwordOpts;
	private $user;
	
	public function setUser(My_Models_User $_user = NULL) {
		if ($_user !== NULL) {
			$this->user = $_user;
		}
	}
	
    public function init() {
		$this->passwordOpts = array('requireAlpha' => true, 'requireNumeric' => true, 'minPasswordLength' => 5, 'noSequence' => true, 'maxSequenceLetters' => 3);
		$pwValidator = new My_Validators_PasswordValidator($this->passwordOpts);
		
		$this->requirements = $pwValidator->getRequirementString();
		
		$this->setMethod('post');
		
		$this->addElement('text', 'etunimi', array(
            'label'      => 'etunimi:',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'value'		=> ($this->user !== NULL) ? $this->user->get_firstname() : "",
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(2, 20))
        )));
		
		$this->addElement('text', 'sukunimi', array(
            'label'      => 'sukunimi:',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'value'		=> ($this->user !== NULL) ? $this->user->get_surname() : "",
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(2, 40))
        )));
		
		$this->addElement('text', 'tunnus', array(
            'label'      => 'tunnus:',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'value'		=> ($this->user !== NULL) ? $this->user->get_username() : "",
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(2, 20))
        )));
		
		$this->addElement('text', 'email', array(
            'label'      => 'sähköposti:',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'value'		=> ($this->user !== NULL) ? $this->user->get_email() : "",
            'validators' => array(
                'EmailAddress',
            )
        ));
		
		$this->addElement('text', 'puhelin', array(
            'label'      => 'puhelin:',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'value'		=> ($this->user !== NULL) ? $this->user->get_phone() : "",
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(2, 40))
        )));
		
		$auth = Zend_Auth::getInstance();
		
		if ($auth->hasIdentity()) { 
			if (($this->user == NULL && $auth->getIdentity()->oikeustaso->get_rightlevel() > 8) || ($this->user != NULL && $this->user->get_id() == $auth->getIdentity()->id)) {
				$this->addElement('password', 'salasana', array(
					'label'      => 'salasana:',
					'required'   => true,
					'filters'    => array('StringTrim'),
					'validators' => Array($pwValidator)
				));
				
				$this->addElement('password', 'uudestaan', array(
					'label'      => 'uudestaan:',
					'required'   => true,
					'filters'    => array('StringTrim'),
					'validators' => Array($pwValidator)
				));
			}
			
			if ($auth->getIdentity()->oikeustaso->get_rightlevel() > 8 && $this->user != NULL && $this->user->get_id() != $auth->getIdentity()->id) {
				$rightAdapter = new My_Adapters_RightsAdapter;
				$rights = $rightAdapter->fetchAll();
				
				$rightList = new Zend_Form_Element_Select('oikeustaso', Array('label:' => 'Oikeusataso'));
				
				foreach ($rights AS $right) {
					$rightList->addMultiOption($right->get_id(), $right->get_name());
				}
				
				$this->addElement($rightList);
				$this->populate(Array('oikeustaso' => $this->user->get_rightlevel()));
			} else {
				$this->addElement('hidden', 'oikeustaso', Array('value' => ($this->user !== NULL) ? $this->user->get_rightlevel() : ""));
			}
			
		}
		
		 $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'lisää',
        ));
		
		$this->addElement('hash', 'csrf', array('ignore' => true,));
        $this->addElement('hidden', 'user_id', Array('value' => ($this->user !== NULL) ? $this->user->get_id() : ""));
    }
	
	public function getPasswordRequirements() {
		return $this->requirements;
	}
}