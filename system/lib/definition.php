<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

abstract class Definition {
	
	// Is Definition Managed by Hanya
	public static $managed = true;
	
	// The Definition Settings
	public static $orderable = true;
	public static $destroyable = true;
	
	// The Definition Blueprint
	public static $blueprint = array();
	
	// Default Field Config
	public static $default_config = array(
		"hidden" => false,
		"label" => true,
		"validation" => array(),
	);
	
	// Definition Constructor Method (invoked by {new(example|argument)})
	static function create($entry,$argument) {
		return $entry;
	}
	
	// Definition Load Method (invoked by [example()])
	static function load($definition,$arguments) {
		$table = ORM::for_table($definition);
		return Helper::each_as_array($table->find_many());
	}
	
	// Before Create Event
	static public function before_create($entry) { return $entry; }
	
	// Before Update Event
	static public function before_update($entry) { return $entry; }
	
	// Before Destroy Event
	static public function before_destroy() { return true; }
	
}