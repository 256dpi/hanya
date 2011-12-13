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
	public static $groups = array();
	
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
	
	/* GETTER */
	
	static public function is_managed() {
		return self::$managed;
	}
	
	static public function is_orderable() {
		return self::$orderable;
	}
	
	static public function is_destroyable() {
		return self::$destroyable;
	}
	
	static public function get_blueprint() {
		return self::$blueprint;
	}
	
	static public function get_default_config() {
		return self::$default_config;
	}
	
	static public function get_groups() {
		return self::$groups;
	}
	
}