<?php

class My_Adapters_VarausAdapter {
    protected $_dbTable;
 
    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Virheellinen taulumääritys');
        }
		
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('My_Models_DBtables_Varaus');
        }
		
        return $this->_dbTable;
    }
	
	public function save(My_Models_Varaus $varaus) {
        $data = array(
            'tuote_id' => $varaus->anna_tuote()->get_id(),
            'maara' => $varaus->anna_tuote()->get_reserved(),
            'varaaja_id' => $varaus->anna_varaaja()->get_id(),
            'varaus_alkaa' => $varaus->anna_alkaa(),
            'varaus_loppuu' => $varaus->anna_loppuu(),
            'palautettu' => new Zend_Db_Expr('NOW()'),
			'id' => $varaus->anna_id()
        );
		
		if (null === ($id = $varaus->anna_id())) {
            unset($data['id']);
            unset($data['palautettu']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
	
	public function find($field, $value) {
		$kysely = Zend_Db_Table::getDefaultAdapter();
		
		$search_reservation = $kysely->select()
			->from('scoutit_varaukset', 
				Array(
					'scoutit_varaukset.id AS varaus_id', 
					new Zend_Db_Expr('SUM(scoutit_varaukset.maara) AS maara'), 
					'scoutit_varaukset.varaus_alkaa AS varaus_alkaa', 
					'scoutit_varaukset.varaus_loppuu AS varaus_loppuu', 
					'scoutit_varaukset.varaaja_id AS varaus_varaaja', 
					'scoutit_varaukset.palautettu', 
					'scoutit_varaukset.maara', 
					'scoutit_varaukset.tuote_id')
				)->where('palautettu IS NULL')->where("{$field} = ?", $value);
				
		//echo $etsi_varaus->__tostring(); die();
		//var_dump($search_reservation->query());
		
		$userAdapter = new My_Adapters_UserAdapter;
		$tuoteAdapter = new My_Adapters_MaterialAdapter;
		
		$line = $search_reservation->query()->fetch();
		$user = $userAdapter->find($line['varaus_varaaja']);
		$tuote = $tuoteAdapter->find($line['tuote_id']);
		
		$varaus = new My_Models_Varaus($tuote, $line['varaus_alkaa'], $line['varaus_loppuu'], $user, $line['maara'], $line['palautettu'], $line['varaus_id']);
		
		return $varaus;
    }
	
	public function fetchAll() {
		$kysely = Zend_Db_Table::getDefaultAdapter();
		
		$search_reservations = $kysely->select()
			->from('scoutit_varaukset', 
				Array(
					'scoutit_varaukset.id AS varaus_id', 
					new Zend_Db_Expr('SUM(scoutit_varaukset.maara) AS maara'), 
					'scoutit_varaukset.varaus_alkaa AS varaus_alkaa', 
					'scoutit_varaukset.varaus_loppuu AS varaus_loppuu', 
					'scoutit_varaukset.varaaja_id AS varaus_varaaja', 
					'scoutit_varaukset.palautettu', 
					'scoutit_varaukset.tuote_id')
				)->where('palautettu IS NULL')->group('varaaja_id')->group('tuote_id')->group('varattu');
		
		$lines = $search_reservations->query()->fetchAll();
		
        $entries = array();
		
        foreach ($lines as $line) {
			$userAdapter = new My_Adapters_UserAdapter;
			$materialAdapter = new My_Adapters_MaterialAdapter;
			
			$user = $userAdapter->find($line['varaus_varaaja']);
			$material = $materialAdapter->find($line['tuote_id']);
			$reservation = new My_Models_Varaus($material, $line['varaus_alkaa'], $line['varaus_loppuu'], $user, $line['maara'], $line['palautettu'], $line['varaus_id']);
			
            $entries[] = $reservation;
        }
        return $entries;
    }
}