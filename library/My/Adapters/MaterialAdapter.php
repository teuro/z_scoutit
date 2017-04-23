<?php

class My_Adapters_MaterialAdapter {
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
            $this->setDbTable('My_Models_DBtables_Material');
        }
		
        return $this->_dbTable;
    }
	
	public function save(My_Models_Material $tuote) {
        $data = array(
			'id' => $tuote->get_id(),
            'nimi'   => $tuote->get_name(),
            'kuvaus' => $tuote->get_description(),
            'paikka_id' => $tuote->get_place()->get_id(),
            'kategoria_id' => $tuote->get_category()->get_id(),
            'luokka_id' => $tuote->get_class()->get_id()
        );
 
        if (null === ($id = $tuote->get_id())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
	
	 public function find($id) {
		$hakulauseke = "
			SELECT 
				scoutit_materiaali.id AS tuote_id,
				scoutit_materiaali.nimi, 
				scoutit_materiaali.kuvaus, 
				scoutit_materiaali.luokka_id, 
				scoutit_materiaali.kategoria_id, 
				scoutit_materiaali.paikka_id,
				SUM(scoutit_ostot.ostomaara) AS varastossa,
				(SELECT GROUP_CONCAT(scoutit_varaukset.varaaja_id) FROM scoutit_varaukset WHERE scoutit_varaukset.tuote_id = scoutit_materiaali.id AND scoutit_varaukset.palautettu IS NULL) AS varaajat,
				(SELECT SUM(scoutit_varaukset.maara) FROM scoutit_varaukset WHERE scoutit_varaukset.tuote_id = scoutit_materiaali.id AND scoutit_varaukset.palautettu IS NULL) AS varattu
			FROM
				scoutit_materiaali
			LEFT JOIN
				scoutit_ostot ON scoutit_materiaali.id = scoutit_ostot.tuote_id 
			WHERE
				scoutit_materiaali.id = ?
			GROUP BY
				scoutit_materiaali.id";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivi = $kysely->query($hakulauseke, Array($id))->fetch();
		
        if (0 == count($rivi)) {
            return;
        }
		
		$luokkaAdapter = new My_Adapters_ClassAdapter;
		$paikkaAdapter = new My_Adapters_PlaceAdapter;
	
		$luokka = $luokkaAdapter->find($rivi['luokka_id']);
		$paikka = $paikkaAdapter->find($rivi['paikka_id']);
		
		$tuote = new My_Models_Material($rivi['nimi'], $rivi['kuvaus'], $luokka, $paikka, $rivi['varaajat'], $rivi['varattu'], $rivi['varastossa'], $rivi['tuote_id']);

		return $tuote;
    }
	
	public function fetchAll() {
		$hakulauseke = "
			SELECT 
				scoutit_materiaali.id AS tuote_id,
				scoutit_materiaali.nimi, 
				scoutit_materiaali.kuvaus, 
				scoutit_materiaali.luokka_id, 
				scoutit_materiaali.kategoria_id, 
				scoutit_materiaali.paikka_id,
				SUM(scoutit_ostot.ostomaara) AS varastossa,
				(SELECT GROUP_CONCAT(scoutit_varaukset.varaaja_id) FROM scoutit_varaukset WHERE scoutit_varaukset.tuote_id = scoutit_materiaali.id AND scoutit_varaukset.palautettu IS NULL) AS varaajat,
				(SELECT SUM(scoutit_varaukset.maara) FROM scoutit_varaukset WHERE scoutit_varaukset.tuote_id = scoutit_materiaali.id AND scoutit_varaukset.palautettu IS NULL) AS varattu
			FROM
				scoutit_materiaali
			LEFT JOIN
				scoutit_ostot ON scoutit_materiaali.id = scoutit_ostot.tuote_id
			GROUP BY
				scoutit_materiaali.id";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivit = $kysely->query($hakulauseke)->fetchAll();
		
        $entries = array();
		
        foreach ($rivit as $rivi) {
			$luokkaAdapter = new My_Adapters_ClassAdapter;
			$paikkaAdapter = new My_Adapters_PlaceAdapter;
		
			$luokka = $luokkaAdapter->find($rivi['luokka_id']);
			$paikka = $paikkaAdapter->find($rivi['paikka_id']);
			
			$tuote = new My_Models_Material($rivi['nimi'], $rivi['kuvaus'], $luokka, $paikka, $rivi['varaajat'], $rivi['varattu'], $rivi['varastossa'], $rivi['tuote_id']);
            $entries[] = $tuote;
        }
		
        return $entries;
    }
}