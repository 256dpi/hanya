<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Memory {
	
	// Initialize Memory System
	public static function initialize() {
		session_start();
	}
	
	// Get Value by Key
	public static function get($key) {
		if(array_key_exists($key,$_SESSION)) {
			return $_SESSION[$key];
		} else {
			return null;
		}
	}
	
	// Set Value by Key
	public static function set($key,$value) {
		$_SESSION[$key] = $value;
	}
	
	// Check Key
	public static function has($key) {
		return array_key_exists($key,$_SESSION);
	}
	
	// Get All Values
	public static function all() {
		return $_SESSION;
	}
	
}