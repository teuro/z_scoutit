<?php

class My_Adapters_CategoryAdapter {
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
            $this->setDbTable('scoutit_kategoriat');
        }
		
        return $this->_dbTable;
    }
	
	public function save(My_Models_Kategory $kategory) {
        $data = array(
            'nimi'   => $kategory->get_name(),
            'kuvaus' => $kategory->get_description(),
            'id' => $kategory->get_id()
        );
 
        if (null === ($id = $kategory->get_id())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
	
	 public function find($id) {
		$hakulauseke = "
			SELECT 
				scoutit_kategoriat.id AS kategoria_id, 
				scoutit_kategoriat.nimi AS kategoria_nimi, 
				scoutit_kategoriat.kuvaus AS kategoria_kuvaus, 
				scoutit_kategoriat.id AS kategoria
			FROM
				scoutit_kategoriat
			WHERE
				scoutit_kategoriat.id = ?";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivi = $kysely->query($hakulauseke, Array($id))->fetch();
		
        if (0 == count($rivi)) {
            return;
        }
		
        $kategoria = new My_Models_Category($rivi['kategoria_nimi'], $rivi['kategoria_kuvaus'], $rivi['kategoria_id']);
		
		return $kategoria;
    }
	
	public function fetchAll() {
		$hakulauseke = "
			SELECT 
				scoutit_kategoriat.id AS kategoria_id, 
				scoutit_kategoriat.nimi AS kategoria_nimi, 
				scoutit_kategoriat.kuvaus AS kategoria_kuvaus, 
				scoutit_kategoriat.id AS kategoria
			FROM
				scoutit_kategoriat";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivit = $kysely->query($hakulauseke)->fetchAll();
		
        $entries = array();
		
        foreach ($rivit as $rivi) {
			$kategoria = new My_Models_Category($rivi['kategoria_nimi'], $rivi['kategoria_kuvaus'], $rivi['kategoria_id']);
			
            $entries[] = $kategoria;
        }
        return $entries;
    }
}