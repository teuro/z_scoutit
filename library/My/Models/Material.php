<?php

class My_Models_Material {
	private $id;
	private $name;
	private $description;
	private $category;
	private $class;
	private $place;
	private $reserved;
	private $amount;
	private $persons;
	
	public function __construct($_nimi, $_kuvaus, My_Models_Class $_luokka, My_Models_Place $_paikka, $_persons = 0, $_reserved = 0, $_amount = 0, $_id = -1) {
		$this->id = ($_id == -1) ? null : $_id;
		$this->name = $_nimi;
		$this->description = $_kuvaus;
		$this->class = $_luokka;
		$this->place = $_paikka;
		$this->reserved = $_reserved;
		$this->amount = $_amount;
		$this->persons = $_persons;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_description() {
		return $this->description;
	}
	
	public function get_category() {
		return $this->class->get_category();
	}
	
	public function get_class() {
		return $this->class;
	}
	
	public function get_place() {
		return $this->place;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_reserved() {
		return $this->reserved;
	}
	
	public function get_amount() {
		return $this->amount;
	}
	
	public function get_persons() {
		return $this->persons;
	}
	
	public function set_reserved($_reserved) {
		$this->reserved = $_reserved;
	}
	
	public function set_name($_name) {
		$this->name = $_name;
	}
	
	public function set_description($_description) {
		$this->description = $_description;
	}
	
	public function set_class(My_Models_Class $_class) {
		$this->class = $_class;
	}
	
	public function set_category(My_Models_Category $_category) {
		$this->category = $_category;
	}
	
	public function set_place(My_Models_Place $_place) {
		$this->place = $_place;
	}
};
