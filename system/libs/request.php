<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Request {

	public static function path() {
		if(array_key_exists("REQUEST_URI",$_SERVER)) {
			$parsed = parse_url($_SERVER["REQUEST_URI"]);
			return str_replace(array(Registry::get("base.path"),"index.php/"),"",$parsed["path"]);
		} else {
			return "";
		}
	}

	public static function post($key,$mode="string",$subarray=null) {
		return self::_load($_POST,$key,$mode,$subarray);
	}
	
	public static function get($key,$mode="string") {
		return self::_load($_GET,$key,$mode);
	}
	
	public static function has_get($key) {
		return array_key_exists($key,$_GET);
	}
	
	public static function has_post($key) {
		return array_key_exists($key,$_POST);
	}
	
	private static function _load($collection,$key,$mode,$subarray=null) {
		if($subarray) {
			$collection = $collection[$subarray];
		}
		
		if(array_key_exists($key,$collection)) {
			$value = $collection[$key];
		} else {
			return null;
		}
		
		switch($mode) {
			case "string": return trim(strip_tags($value)); break;
			case "int": return (int)$value; break;
			case "array": return (array)$value; break;
		}
	}
	
}