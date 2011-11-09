<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

abstract class Definition {
	
	// The Definition Settings
	public static $settings = array();
	
	// The Definition Blueprint
	public static $blueprint = array();
	
	// Default Field Config
	public static $default_config = array(
		"hidden" => false,
		"label" => true,
		"validation" => array(),
	);
	
	// Definition Load Method (invoked by [example()])
	static function load($table,$arguments) {
		return $table;
	}
	
	// Before Create Event
	static public function before_create($entry) { return $entry; }
	
	// Before Update Event
	static public function before_update($entry) { return $entry; }
	
	// Before Destroy Event
	static public function before_destroy() { return true; }
	
}