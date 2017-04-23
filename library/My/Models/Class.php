<?php

class My_Models_Class {
	private $id;
	private $name;
	private $description;
	private $category;
	
	public function __construct($_name, $_description, My_Models_Category $_category, $_id = -1) {
		$this->id = ($_id == -1) ? false : $_id;
		$this->name = $_name;
		$this->description = $_description;
		$this->category = $_category;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_description() {
		return $this->description;
	}
	
	public function get_category() {
		return $this->category;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function set_category(My_Models_Category $_category) {
		$this->category = $_category;
	}
};
