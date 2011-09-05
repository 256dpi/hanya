<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

abstract class Definition {
	
	// The Definition Blueprint
	public static $blueprint = array();
	
	// Default Field Config
	public static $default_config = array(
		"hidden" => false,
		"label" => true,
		"validation" => array(),
	);
	
	// Before Create Event
	static public function before_create($entry) { return $entry; }
	
	// Before Update Event
	static public function before_update($entry) { return $entry; }
	
	// Before Destroy Event
	static public function before_destroy() { return true; }
	
}