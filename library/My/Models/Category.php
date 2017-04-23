<?php

class My_Models_Category {
	private $id;
	private $name;
	private $description;
	
	public function __construct($_name, $_description, $_id = -1) {
		$this->id = ($_id == -1) ? false : $_id;
		$this->name = $_name;
		$this->description = $_description;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_description() {
		return $this->description;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function set_id($_id) {
		$this->id = $_id;
	}
	
	public function set_name($_name) {
		$this->name = $_name;
	}
	
	public function set_description($_description) {
		$this->description = $_description;
	}
};
