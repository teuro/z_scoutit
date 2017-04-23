<?php

class My_Adapters_ClassAdapter {
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
            $this->setDbTable('scoutit_luokat');
        }
		
        return $this->_dbTable;
    }
	
	public function save(My_Models_Luokka $luokka) {
        $data = array(
            'nimi'   => $luokka->get_name(),
            'kuvaus' => $luokka->get_description(),
            'kategoria_id' => $luokka->get_kategory(),
            'id' => $luokka->get_id()
        );
 
        if (null === ($id = $luokka->get_id())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
	
	 public function find($id) {
		$hakulauseke = "
			SELECT 
				scoutit_luokat.id AS luokka_id, 
				scoutit_luokat.nimi AS luokka_nimi, 
				scoutit_luokat.kuvaus AS luokka_kuvaus,
				scoutit_luokat.kategoria_id AS luokka_kategoria
			FROM
				scoutit_luokat
			WHERE
				scoutit_luokat.id = ?";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivi = $kysely->query($hakulauseke, Array($id))->fetch();
		
        if (0 == count($rivi)) {
            return;
        }
		
		$kategoriaAdapter = new My_Adapters_CategoryAdapter;

		$kategoria = $kategoriaAdapter->find($rivi['luokka_kategoria']);
        $luokka = new My_Models_Class($rivi['luokka_nimi'], $rivi['luokka_kuvaus'], $kategoria, $rivi['luokka_id']);
		
		return $luokka;
    }
	
	public function fetchAll() {
		$hakulauseke = "
			SELECT 
				scoutit_luokat.id AS luokka_id, 
				scoutit_luokat.nimi AS luokka_nimi, 
				scoutit_luokat.kuvaus AS luokka_kuvaus
			FROM
				scoutit_luokat";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivit = $kysely->query($hakulauseke)->fetchAll();
		
        $entries = array();
		
        foreach ($rivit as $rivi) {
			$luokka = new My_Models_Category($rivi['luokka_nimi'], $rivi['luokka_kuvaus'], $rivi['luokka_id']);
			
            $entries[] = $luokka;
        }
        return $entries;
    }
}