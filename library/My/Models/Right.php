<?php

class My_Models_Right {
	private $id;
	private $name;
	private $rightlevel;
	private $comments;
	
	public function __construct($_name, $_rightlevel, $_comments, $_id = -1) {
		$this->id = ($_id == -1) ? false : $_id;
		$this->name = $_name;
		$this->rightlevel = $_rightlevel;
		$this->comments = $_comments;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_rightlevel() {
		return $this->rightlevel;
	}
	
	public function get_comments() {
		return $this->comments;
	}
	
	public function set_id($_id) {
		$this->id = $_id;
	}
	
	public function set_name($_name) {
		$this->name = $_name;
	}
	
	public function set_rightlevel($_rightlevel) {
		$this->rightlevel = $_rightlevel;
	}
	
	public function set_comments($_comments) {
		$this->comments = $_comments;
	}
};
