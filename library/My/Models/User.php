<?php

class My_Models_User {
	private $id;
	private $username;
	private $firstname;
	private $surname;
	private $email;
	private $phone;
	private $hash;
	private $rightlevel;
	
	public function __construct($_username, $_firstname, $_surname, $_email, $_phone, $_rightlevel, $_hash = "", $_id = -1) {
		$this->id = ($_id == -1) ? NULL : $_id;
		$this->firstname = $_firstname;
		$this->surname = $_surname;
		$this->email = $_email;
		$this->phone = $_phone;
		$this->username = $_username;
		$this->hash = $_hash;
		$this->rightlevel = $_rightlevel;
	}
	
	public function get_username() {
		return $this->username;
	}
	
	public function get_firstname() {
		return $this->firstname;
	}
	
	public function get_surname() {
		return $this->surname;
	}
	
	public function get_email() {
		return $this->email;
	}
	
	public function get_phone() {
		return $this->phone;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_hash() {
		return $this->hash;
	}
	
	public function get_rightlevel() {
		return $this->rightlevel;
	}
	
	public function set_id($_id) {
		$this->id = $_id;
	}
	
	public function set_username($_username) {
		$this->username = $_username;
	}
	
	public function set_firstname($_firstname) {
		$this->firstname = $_firstname;
	}
	
	public function set_surname($_surname) {
		$this->surname = $_surname;
	}
	
	public function set_email($_email) {
		$this->email = $_email;
	}
	
	public function set_phone($_phone) {
		$this->phone = $_phone;
	}
	
	public function set_rightlevel($_rightlevel) {
		$this->rightlevel = $_rightlevel;
	}
	
	public function change_password($_salasana, $_uudestaan) {
		if (strlen(trim($_salasana)) == 0 || $_salasana != $_uudestaan) {
			return false;
		} else {
			$this->hash = SHA1($_salasana);
		}
		
		return true;
	}
};
