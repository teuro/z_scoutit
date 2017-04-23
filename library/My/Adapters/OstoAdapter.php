<?php

class My_Adapters_OstoAdapter {
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
            $this->setDbTable('My_Models_DBtables_Osto');
        }
		
        return $this->_dbTable;
    }
	
	public function save(My_Models_Osto $osto) {
        $data = array(
            'id' => $osto->get_id(),
            'tuote_id' => $osto->get_material()->get_id(),
            'ostomaara' => $osto->get_amount(),
            'ostohinta' => $osto->get_price(),
            'ostopaiva' => new Zend_Db_Expr('NOW()'),
            'tositenumero' => $osto->get_receiptnumber(),
            'ostaja_id' => $osto->get_buyer()->get_id()
        );
 
        if (null === ($id = $osto->get_id())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
	
	public function find_all_actions($id) {
		$hakulauseke = "
			SELECT 
				scoutit_ostot.id AS osto_id, 
				scoutit_materiaali.nimi, 
				SUM(scoutit_ostot.ostomaara) AS ostomaara, 
				DATE_FORMAT(scoutit_ostot.ostopaiva, '%d.%m.%Y %h:%i:%s') AS ostopaiva,
				scoutit_ostot.ostaja_id AS ostaja,
				SUM(scoutit_ostot.ostohinta) AS ostohinta,
				scoutit_ostot.tositenumero
			FROM
				scoutit_ostot,
				scoutit_materiaali
			WHERE
				scoutit_ostot.tuote_id = ?
			AND
				scoutit_ostot.tuote_id = scoutit_materiaali.id
			GROUP BY
				scoutit_ostot.ostopaiva";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$linet = $kysely->query($hakulauseke, Array($id))->fetchAll();
		
		return $linet;
	}
	
	 public function find($id) {
		$hakulauseke = "
			SELECT 
				scoutit_ostot.id AS osto_id, 
				scoutit_ostot.tuote_id AS tuote_id, 
				SUM(scoutit_ostot.ostomaara) AS ostomaara, 
				DATE_FORMAT(scoutit_ostot.ostopaiva, '%d.%m.%Y %h:%i:%s') AS ostopaiva,
				scoutit_ostot.ostaja_id AS ostaja,
				SUM(scoutit_ostot.ostohinta) AS ostohinta,
				scoutit_ostot.tositenumero
			FROM
				scoutit_ostot
			WHERE
				scoutit_ostot.tuote_id = ?
			GROUP BY
				scoutit_ostot.tuote_id";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$line = $kysely->query($hakulauseke, Array($id))->fetch();
		
		$materialAdapter = new My_Adapters_MaterialAdapter;
		$userAdapter = new My_Adapters_UserAdapter;
		
		$material = $materialAdapter->find($line['tuote_id']);
		$user = $userAdapter->find($line['ostaja']);
        
		$osto = new My_Models_Osto($material, $line['ostopaiva'], $line['ostohinta'], $line['ostomaara'], $line['tositenumero'], $user, $line['osto_id']);
		return $osto;
    }
	
	public function fetchAll() {
		$kysely = Zend_Db_Table::getDefaultAdapter();
		
		$search_warehouse_actions = $kysely->select()
			->from('scoutit_ostot', 
				Array(
					'scoutit_ostot.id AS osto_id', 
					new Zend_Db_Expr('SUM(scoutit_ostot.ostomaara) AS ostomaara'), 
					new Zend_Db_Expr('SUM(scoutit_ostot.ostohinta) AS ostohinta'), 
					'DATE_FORMAT(scoutit_ostot.ostopaiva, \'%d.%m.%Y %h:%i:%s\') AS ostopaiva',
					'scoutit_ostot.ostaja_id AS ostaja',
					'scoutit_ostot.tositenumero',
					'scoutit_ostot.tuote_id')
				)->group('tuote_id');
		
		$lines = $search_warehouse_actions->query()->fetchAll();
		
        $entries = array();
		
        foreach ($lines as $line) {
			$userAdapter = new My_Adapters_UserAdapter;
			$user = $userAdapter->find($line['ostaja']);
			
			$materialAdapter = new My_Adapters_MaterialAdapter;
			$material = $materialAdapter->find($line['tuote_id']);
			
			$osto = new My_Models_Osto($material, $line['ostopaiva'], $line['ostohinta'], $line['ostomaara'], $line['tositenumero'], $user, $line['osto_id']);
			
            $entries[] = $osto;
        }
		
        return $entries;
    }
}