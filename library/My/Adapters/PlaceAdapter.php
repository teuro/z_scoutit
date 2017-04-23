<?php

class My_Adapters_PlaceAdapter {
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
            $this->setDbTable('scoutit_hyllypaikat');
        }
		
        return $this->_dbTable;
    }
	
	public function save(My_Models_Paikka $paikka) {
        $data = array(
            'nimi'   => $paikka->get_name(),
            'syvyys' => $paikka->get_deep(),
            'korkeus' => $paikka->get_height(),
            'leveys' => $paikka->get_width(),
            'id' => $paikka->get_id()
        );
 
        if (null === ($id = $kategory->anna_id())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
	
	 public function find($id) {
		$hakulauseke = "
			SELECT 
				scoutit_hyllypaikat.id AS paikka_id, 
				scoutit_hyllypaikat.nimi AS paikka_nimi, 
				scoutit_hyllypaikat.leveys AS paikka_leveys,
				scoutit_hyllypaikat.korkeus AS paikka_korkeus,
				scoutit_hyllypaikat.syvyys AS paikka_syvyys
			FROM
				scoutit_hyllypaikat
			WHERE
				scoutit_hyllypaikat.id = ?";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivi = $kysely->query($hakulauseke, Array($id))->fetch();
		
        if (0 == count($rivi)) {
            return;
        }
		
        $paikka = new My_Models_Place($rivi['paikka_nimi'], $rivi['paikka_leveys'], $rivi['paikka_korkeus'], $rivi['paikka_syvyys'], $rivi['paikka_id']);
		
		return $paikka;
    }
	
	public function fetchAll() {
		$hakulauseke = "
			SELECT 
				scoutit_hyllypaikat.id AS paikka_id, 
				scoutit_hyllypaikat.nimi AS paikka_nimi, 
				scoutit_hyllypaikat.leveys AS paikka_leveys,
				scoutit_hyllypaikat.korkeus AS paikka_korkeus,
				scoutit_hyllypaikat.syvyys AS paikka_syvyys
			FROM
				scoutit_hyllypaikat";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivit = $kysely->query($hakulauseke)->fetchAll();
		
        $entries = array();
		
        foreach ($rivit as $rivi) {
			$place = new My_Models_Place($rivi['paikka_nimi'], $rivi['paikka_leveys'], $rivi['paikka_korkeus'], $rivi['paikka_syvyys'], $rivi['paikka_id']);
			
            $entries[] = $place;
        }
		
        return $entries;
    }
}