<?php

class My_Adapters_RightsAdapter {
    protected $_dbTable;
 
    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
		
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('scoutit_oikeudet');
        }
		
        return $this->_dbTable;
    }
	
	public function save(My_Models_Right $right) {
        $data = array(
            'id'   => $right->get_id(),
            'nimi' => $right->get_name(),
            'oikeustaso' => $right->get_rightllevel(),
            'kommentit' => $right->get_comments(),
        );
 
        if (null === ($id = $right->anna_id())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
	
	 public function find($id) {
		$hakulauseke = "
			SELECT 
				scoutit_oikeudet.id,  
				scoutit_oikeudet.nimi AS nimi,
				scoutit_oikeudet.oikeustaso,
				scoutit_oikeudet.kommentit
			FROM 
				scoutit_oikeudet
			WHERE
				scoutit_oikeudet.id = ?";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivi = $kysely->query($hakulauseke, $id)->fetch();
		
        if (0 == count($rivi)) {
            return;
        }
		
        $right = new My_Models_Right($rivi['nimi'], $rivi['oikeustaso'], $rivi['kommentit'], $rivi['id']);
		
		return $right;
    }
	
	public function fetchAll() {
		$hakulauseke = "
		SELECT 
			scoutit_oikeudet.id,  
			scoutit_oikeudet.nimi,
			scoutit_oikeudet.oikeustaso,
			scoutit_oikeudet.kommentit
		FROM 
			scoutit_oikeudet";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivit = $kysely->query($hakulauseke)->fetchAll();
		
        $entries   = array();
		
        foreach ($rivit as $rivi) {
			$right = new My_Models_Right($rivi['nimi'], $rivi['oikeustaso'], $rivi['kommentit'], $rivi['id']);
            $entries[] = $right;
        }
		
        return $entries;
    }
}