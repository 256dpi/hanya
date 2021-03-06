<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

abstract class Definition {
	
	// Is Definition Managed by Hanya
	public $managed = true;
	
	// The Definition Settings
	public $orderable = true;
	public $destroyable = true;
	public $groups = array();
	
	// The Definition Blueprint
	public $blueprint = array();
	
	// Default Field Config
	public $default_config = array(
		"hidden" => false,
		"label" => true,
		"validation" => array(),
	);
	
	// Definition Constructor Method (invoked by {new(example|argument)})
	public function create($entry,$argument) {
		return $entry;
	}
	
	// Definition Load Method (invoked by [example()])
	public function load($definition,$arguments) {
	  if($this->orderable) {
	    $table = ORM::for_table($definition)->order_by_asc("ordering");
	  } else {
	    $table = ORM::for_table($definition);
	  }
		return Helper::each_as_array($table->find_many());
	}
	
	// Before Create Event
	public function before_create($entry) { return $entry; }
	
	// Before Update Event
	public function before_update($entry) { return $entry; }
	
	// Before Destroy Event
	public function before_destroy() { return true; }
	
}