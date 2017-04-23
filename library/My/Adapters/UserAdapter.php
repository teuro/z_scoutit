<?php

class My_Adapters_UserAdapter {
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
            $this->setDbTable('My_Models_DBtables_User');
        }
		
        return $this->_dbTable;
    }
	
	public function save(My_Models_User $user) {
        $data = array(
            'etunimi'   => $user->get_firstname(),
            'sukunimi' => $user->get_surname(),
            'email' => $user->get_email(),
            'puhelin' => $user->get_phone(),
            'id' => $user->get_id(),
			'tunnus' => $user->get_username(),
			'tiiviste' => $user->get_hash(),
			'oikeus_id' => $user->get_rightlevel()
        );
 
        if (null === ($id = $user->get_id())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
	
	 public function find($id) {
		$hakulauseke = "
			SELECT 
				scoutit_henkilot.id,
				scoutit_henkilot.tunnus,
				scoutit_henkilot.etunimi,
				scoutit_henkilot.sukunimi,
				scoutit_henkilot.email,
				scoutit_henkilot.puhelin,
				scoutit_henkilot.oikeus_id,
				scoutit_henkilot.tiiviste
			FROM 
				scoutit_henkilot
			WHERE
				scoutit_henkilot.id = ?";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivi = $kysely->query($hakulauseke, $id)->fetch();
		
        if (0 == count($rivi)) {
            return;
        }
		
        $user = new My_Models_User($rivi['tunnus'], $rivi['etunimi'], $rivi['sukunimi'], $rivi['email'], $rivi['puhelin'], $rivi['oikeus_id'], $rivi['tiiviste'], $rivi['id']);
		
		return $user;
    }
	
	public function fetchAll() {
		$hakulauseke = "
		SELECT 
			scoutit_henkilot.id,
			scoutit_henkilot.tunnus,
			scoutit_henkilot.etunimi,
			scoutit_henkilot.sukunimi,
			scoutit_henkilot.email,
			scoutit_henkilot.puhelin,
			scoutit_henkilot.oikeus_id,
			scoutit_henkilot.tiiviste
		FROM 
			scoutit_henkilot
		ORDER BY
			sukunimi";
			
		$kysely = Zend_Db_Table::getDefaultAdapter();
		$rivit = $kysely->query($hakulauseke)->fetchAll();
		
        $entries   = array();
		
        foreach ($rivit as $rivi) {
			$user = new My_Models_User($rivi['tunnus'], $rivi['etunimi'], $rivi['sukunimi'], $rivi['email'], $rivi['puhelin'], $rivi['oikeus_id'], $rivi['tiiviste'], $rivi['id']);
            $entries[] = $user;
        }
        return $entries;
    }
}