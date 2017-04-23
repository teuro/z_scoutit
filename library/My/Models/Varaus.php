<?php

class My_Models_Varaus {
	private $id;
	private $tuote;
	private $varaaja;
	private $alkaa;
	private $loppuu;
	private $maara;
	private $palauettu;
	
	public function __construct(My_Models_Material $_tuote, $_alkaa, $_loppuu, My_Models_User $_varaaja, $_maara, $_palautettu, $_id = -1) {
		$this->tuote = $_tuote;
		$this->varaaja = $_varaaja;
		$this->alkaa = $_alkaa;
		$this->loppuu = $_loppuu;
		$this->palauetttu = ($_palautettu == NULL) ? NULL : $_palautettu;
		$this->maara = $_maara;
		$this->id = ($_id == -1) ? NULL : $_id;
	}
	
	public function anna_id() {
		return $this->id;
	}
	
	public function anna_tuote() {
		return $this->tuote;
	}
	
	public function anna_varaaja() {
		return $this->varaaja;
	}
	
	public function anna_alkaa() {
		return $this->alkaa;
	}
	
	public function anna_loppuu() {
		return $this->loppuu;
	}
	
	public function anna_palautettu() {
		return $this->palautettu;
	}
}