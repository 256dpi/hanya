<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Helper {

	// Return Tolen
	public static function token() {
		$token = md5(strftime("%x %R"));
		Memory::set("token",$token);
		return $token;
	}
	
	// Invoke as_array on every child
	public static function each_as_array($results) {
		$ret = array();
		foreach($results as $result) {
			$ret[] = $result->as_array();
		}
		return $ret;
	}
	
	// Return a URL Safe Filename
	public static function filename($string) {
		$string = strtolower(trim($string));
		$string = preg_replace('/[^a-z0-9_.]/','',$string);
		return preg_replace('/_+/',"_",$string);
	}

	// Check Session for User Privilege
	public static function user_has_privilege($privilege) {
		if(Memory::get("logged_in")) {
			$users = Registry::get("auth.users");
			$privs = $users[Memory::get("logged_in_user")]["privileges"];
			return (in_array($privilege,$privs) or in_array("god",$privs));
		}
		return false;
	}

	// Pretty Print Errors
	public static function print_errors() {

		// Get Errors
		$error = Memory::errors();
		
		// Check & Display
		if($error) {
			return HTML::div(null,"errors",$error);
		} else {
			return "";
		}
	}
	
}