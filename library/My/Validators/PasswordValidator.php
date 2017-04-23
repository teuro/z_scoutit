<?php

class My_Validators_PasswordValidator extends Zend_Validate_Abstract {
    const ALL_WHITESPACE 	= 'allWhitespace';
    const NOT_LONG       	= 'notLong';
    const NO_NUMERIC     	= 'noNumeric';
    const NO_ALPHA       	= 'noAlpha';
    const NO_CAPITAL 		= 'noCapital';
	const SEQUENCE_LETTERS 	= 'noSequence';

    protected $_minPasswordLength 	= 8;
	protected $_maxSequenceLetters 	= 3;
    protected $_requireNumeric    	= true;
    protected $_requireAlpha      	= true;
    protected $_requireCapital    	= false;
	protected $_noSequence		  	= true;
	private $password_string		= "";

    protected $_messageTemplates = array(
        self::ALL_WHITESPACE 	=> 'password cannot consist of all whitespace',
        self::NOT_LONG       	=> 'password must be at least %len% characters in length',
        self::NO_NUMERIC     	=> 'password must contain at least 1 numeric character',
        self::NO_ALPHA       	=> 'password must contain at least one alphabetic character',
        self::NO_CAPITAL     	=> 'password must contain at least one capital letter',
		self::SEQUENCE_LETTERS 	=> 'password can\'t contain more than %len% characters in sequence',
    );

    public function __construct($options = array()) {
        if (isset($options['minPasswordLength']) && Zend_Validate::is($options['minPasswordLength'], 'Digits') && (int)$options['minPasswordLength'] > 3) {
			$this->_minPasswordLength = $options['minPasswordLength'];
		}
		
		if (isset($options['maxSequenceLetters']) && Zend_Validate::is($options['maxSequenceLetters'], 'Digits') && (int)$options['maxSequenceLetters'] > 2) {
			$this->_maxSequenceLetters = $options['maxSequenceLetters'];
		}
		
        if (isset($options['requireNumeric'])) {
			$this->_requireNumeric = (bool)$options['requireNumeric'];
		}
		
        if (isset($options['requireAlpha'])) {   
			$this->_requireAlpha   = (bool)$options['requireAlpha'];
		}
		
        if (isset($options['requireCapital'])) { 
			$this->_requireCapital = (bool)$options['requireCapital'];
		}
		
		if (isset($options['noSequence'])) { 
			$this->_noSequence = (bool)$options['noSequence'];
		}
		
		$this->_messageTemplates[self::NOT_LONG] = str_replace('%len%', $this->_minPasswordLength, $this->_messageTemplates[self::NOT_LONG]);
        $this->_messageTemplates[self::SEQUENCE_LETTERS] = str_replace('%len%', $this->_maxSequenceLetters, $this->_messageTemplates[self::SEQUENCE_LETTERS]);
    }

    /**
     * Validate a password with the set requirements
     * 
     * @see Zend_Validate_Interface::isValid()
     * @return bool true if valid, false if not
     */
    public function isValid($value, $context = null) {
        $value = (string)$value;
        $this->_setValue($value);
		
        if (trim($value) == '') {
            $this->_error(self::ALL_WHITESPACE);
        } 
		
		if (strlen($value) < $this->_minPasswordLength) {
            $this->_error(self::NOT_LONG, $this->_minPasswordLength);
        } 
		
		if ($this->_requireNumeric == true && preg_match('/\d/', $value) == false) {
            $this->_error(self::NO_NUMERIC);
        } 
		
		if ($this->_requireAlpha == true && preg_match('/[a-z]/i', $value) == false) {
            $this->_error(self::NO_ALPHA);
        } 
		
		if ($this->_requireCapital == true && preg_match('/[A-Z]/', $value) == false) {
            $this->_error(self::NO_CAPITAL);
        } 
		
		if ($this->_noSequence == true && $this->checkSequence($value)) {
			$this->_error(self::SEQUENCE_LETTERS);
        }

        if (sizeof($this->_errors) > 0) {
            return false;
        } else {
            return true;
        }
    }
	
	private function checkSequence($value) {
		$aakkoset = "abcdefghijklmnoqrstuvxyz0123456789";
		
		for ($i = 0; $i < strlen($value); ++$i) {
			$search = substr($value, $i, $this->_maxSequenceLetters);
			
			if (strlen($search) == $this->_maxSequenceLetters) {
				for ($j = 0; $j < strlen($aakkoset); ++$j) {
					$compare = substr($aakkoset, $j, $this->_maxSequenceLetters);
					if (strlen($compare) == $this->_maxSequenceLetters) {
						if ($search == $compare) {
							return true;
						}
					}
				}
			}
		}
		
		return false;
	}

    /**
     * Return a string explaining the current password requirements such as length and character set
     * 
     * @return string The printable message explaining password requirements
     */
    public function getRequirementString() {
        $parts = array();

        $parts[] = 'Salasanan tulee olla ainakin ' . $this->_minPasswordLength . ' merkkiä pitkä';

        if ($this->_requireNumeric) $parts[] = 'sisältää aiankin yksi numero (0-9)';
        if ($this->_requireAlpha)   $parts[] = 'sisältää ainakin yksi aakkosmerkki (a-z)';
        if ($this->_requireCapital) $parts[] = 'sisältää ainkin yksi iso kirjain (A-Z)';
        if ($this->_noSequence) 	$parts[] = 'eikä sisältää ' . $this->_maxSequenceLetters . ' tai useampaa merkkiä peräkkäin';

        if (sizeof($parts) == 1) {
            return $parts[0] . '.';
        } else if (sizeof($parts) == 2) {
            return $parts[0] . ' ja ' . $parts[1] . '.';
        } else {
            $str = $parts[0];
            for ($i = 1; $i < sizeof($parts) - 1; ++$i) {
                $str .= ', ' . $parts[$i];
            }

            $str .= ' ja ' . $parts[$i];

            return $str . '.';
        }
    }
}