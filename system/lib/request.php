<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Request {

	// Get Request Path from Server
	public static function path() {
		if(array_key_exists("REQUEST_URI",$_SERVER)) {
			$parsed = parse_url($_SERVER["REQUEST_URI"]);
			$path = $parsed["path"];
			if(Registry::get("base.path") != "/" && Registry::get("base.path") != "") {
				$path = str_replace(Registry::get("base.path"),"",$path);
			}
			return str_replace("index.php/","",$path);
		} else {
			return "";
		}
	}

	// Get Segments of a Path
	public static function get_segments($path) {
		$segments = explode("/",$path);
		$i = count($segments);
		if($i > 1) {
			if($segments[0] == "") {
				array_shift($segments);
			}
		}
		return $segments;
	}

	// Get Variable from POST
	public static function post($key,$mode="string",$subarray=null) {
		return self::_load($_POST,$key,$mode,$subarray);
	}
	
	// Get File from FILES
	public static function file($key,$subarray=null) {
		return self::_load($_FILES,$key,"array",$subarray);
	}
	
	// Get Variable from GET
	public static function get($key,$mode="string") {
		return self::_load($_GET,$key,$mode);
	}
	
	// Check for a GET Variable
	public static function has_get($key) {
		return array_key_exists($key,$_GET);
	}
	
	// Check for a POST Variable
	public static function has_post($key) {
		return array_key_exists($key,$_POST);
	}
	
	// Load Variable from an Array an Cast it
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
			case "raw": return $value; break;
		}
	}
	
}