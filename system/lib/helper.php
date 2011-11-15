<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Helper {
	
	// Invoke as_array on every child
	public static function each_as_array($results) {
		$ret = array();
		foreach($results as $result) {
			$ret[] = $result->as_array();
		}
		return $ret;
	}
	
	// Get a Tree File from Segments
	public static function tree_file_from_segments($segments) {
		if($segments[0] != "") {
			return "tree/".join($segments,"/").".html";
		} else {
			return "tree/index.html";
		}
	}
	
	// Return a URL Safe Filename
	public static function filename($string) {
		$string = strtolower(trim($string));
		$string = preg_replace('/[^a-z0-9_.]/','',$string);
		return preg_replace('/_+/',"_",$string);
	}
	
}