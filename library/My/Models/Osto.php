<?php

class My_Models_Osto {
	private $id;
	private $material;
	private $purchaseday;
	private $price;
	private $amount;
	private $receiptnumber;
	private $buyer;
	
	public function __construct(My_Models_Material $_material, $_purchaseday, $_price, $_amount, $_receiptnumber, My_Models_user $_buyer, $_id = -1) {
		$this->id = ($_id == -1) ? NULL : $_id;
		$this->material = $_material;
		$this->purchaseday = $_purchaseday;
		$this->price = $_price;
		$this->amount = $_amount;
		$this->receiptnumber = $_receiptnumber;
		$this->buyer = $_buyer;
	}
	
	public function get_material() {
		return $this->material;
	}
	
	public function get_purchaseday() {
		return $this->purchaseday;
	}
	
	public function get_price() {
		return $this->price;
	}
	
	public function get_amount() {
		return $this->amount;
	}
	
	public function get_receiptnumber() {
		return $this->receiptnumber;
	}
	
	public function get_buyer() {
		return $this->buyer;
	}
	
	public function get_id() {
		return $this->id;
	}
};
