<?php

$accessLevelsByRole = [
	//'volunteer' => 1,
	'admin' => 2,
	'superadmin' => 3
];

class Service {
	private $id;
	private $name;         // 
	private $type; //
	private $duration;      // 
	 
	
	function __construct($n, $t, $d){//, $e) {
		$this->name = $n;
		$this->type = $t;
		$this->duration = $d;
	}

	function get_id() {
		return $this->id;
	}
	function get_name() {
		return $this->name;
	}
	function get_type() {
		return $this->type;
	}	
	function get_duration() {
		return $this->gender;
	}



}