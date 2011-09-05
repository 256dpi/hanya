<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

abstract class Plugin {
	
	// On After Initialize Event
	public static function after_initialize() {}
	
	// On Before Execution Event
	public static function before_execution() {}
	
	// On Command Event
	public static function on_command($options) {}
	
	// Internal Action Delegation 
	protected static function _delegate($for,$action) {
		$method = "action_".$action;
		if(method_exists($for,$method)) {
			$for::$method();
		}
	}

}