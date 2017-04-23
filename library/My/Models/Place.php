<?php

class My_Models_Place {
	private $id;
	private $name;
	private $width;
	private $height;
	private $deep;
	
	public function __construct($_name, $_width, $_height, $_deep, $_id = -1) {
		$this->id = ($_id == -1) ? false : $_id;
		$this->name = $_name;
		$this->width = $_width;
		$this->height = $_height;
		$this->deep = $_deep;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_width() {
		return $this->width;
	}
	
	public function get_height() {
		return $this->height;
	}
	
	public function get_deep() {
		return $this->deep;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function set_id($_id) {
		$this->id = $_id;
	}
	
	public function set_width($_width) {
		$this->width = $_width;
	}
	
	public function set_height($_height) {
		$this->height = $_height;
	}
	
	public function set_deep($_deep) {
		$this->deep = $_deep;
	}
};
