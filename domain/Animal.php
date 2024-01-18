<?php

$accessLevelsByRole = [
	//'volunteer' => 1,
	'admin' => 2,
	'superadmin' => 3
];

class Animal {
	private $id;
	private $name;         // 
	private $breed; //
	private $age;      // 
	private $gender; // 
	private $spay_neuter_done;  // 
	private $microchip_done;   //
	
	private $rabies_given_date;   //
	private $rabies_due_date;   //
	private $heartworm_given_date;   //
	private $heartworm_due_date;   //
	private $distemper1_given_date;   //
	private $distemper1_due_date;   //
	private $distemper2_given_date;   //
	private $distemper2_due_date;   //
	private $distemper3_given_date;   //
	private $distemper3_due_date;   //
	 
	
	function __construct($i, $n, $b, $a, $g, $s, $m, $rg, $rd, $hg, $hd, $d1g, $d1d, $d2g, $d2d, $d3g, $d3d){//, $e) {
		$this->id = $i;
		$this->name = $n;
		$this->breed = $b;
		$this->age = $a;
		$this->gender = $g;
		$this->spay_neuter_done = $s;
        $this->microchip_done = $m;
	
		$this->rabies_given_date = $rg;
		$this->rabies_due_date = $rd;
		$this->heartworm_given_date = $hg;
		$this->heartworm_due_date = $hd;
		$this->distemper1_given_date = $d1g;
		$this->distemper1_due_date = $d1d;
		$this->distemper2_given_date = $d2g;
		$this->distemper2_due_date = $d2d;
		$this->distemper3_given_date = $d3g;
		$this->distemper3_due_date = $d3d;
	}

	function get_id() {
		return $this->id;
	}
	function get_name() {
		return $this->name;
	}
	function get_breed() {
		return $this->breed;
	}	
	function get_gender() {
		return $this->gender;
	}
	function get_age() {
		return $this->age;
	}
	function get_spay_neuter_done() {
		return $this->spay_neuter_done;
	}
	function get_microchip_done() {
		return $this->microchip_done;
	}

	function get_rabies_given_date() {
		return $this->rabies_given_date;
	}
	function get_rabies_due_date() {
		return $this->rabies_due_date;
	}
	function get_heartworm_given_date() {
		return $this->heartworm_given_date;
	}
	function get_heartworm_due_date() {
		return $this->heartworm_due_date;
	}
	function get_distemper1_given_date() {
		return $this->distemper1_given_date;
	}
	function get_distemper1_due_date() {
		return $this->distemper1_due_date;
	}
	function get_distemper2_given_date() {
		return $this->distemper2_given_date;
	}
	function get_distemper2_due_date() {
		return $this->distemper2_due_date;
	}
	function get_distemper3_given_date() {
		return $this->distemper3_given_date;
	}
	function get_distemper3_due_date() {
		return $this->distemper3_due_date;
	}

	function get_other($other){
		if($other == "rabies_given_date") return $this->get_rabies_given_date();
		if($other == "rabies_due_date") return $this->get_rabies_due_date();
		if($other == "heartworm_given_date") return $this->get_heartworm_given_date();
		if($other == "heartworm_due_date") return $this->get_heartworm_due_date();
		if($other == "distemper1_given_date") return $this->get_distemper1_given_date();
		if($other == "distemper1_due_date") return $this->get_distemper1_due_date();
		if($other == "distemper2_given_date") return $this->get_distemper2_given_date();
		if($other == "distemper2_due_date") return $this->get_distemper2_due_date();
		if($other == "distemper3_given_date") return $this->get_distemper3_given_date();
		if($other == "distemper3_due_date") return $this->get_distemper3_due_date();
	}
}