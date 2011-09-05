<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Registry {
	
	// Storage
	private static $_data = array();
	
	// Load Array
	public static function load($array) {
		self::$_data = array_merge(self::$_data,$array);
	}
	
	// Set Value by Key
	public static function set($key,$value) {
		self::$_data[$key] = $value;
	}
	
	// Get Value by Key
	public static function get($key) {
		if(array_key_exists($key,self::$_data)) {
			return self::$_data[$key];
		} else {
			return null;
		}
	}
	
	// Check for Key
	public static function has($key) {
		return array_key_exists($key,self::$_data);
	}
	
	// Get All Values
	public function all() {
		return self::$_data;
	}
	
	// Append String to a Value
	public function append($key,$value) {
		self::set($key,self::get($key).$value);
	}
	
}