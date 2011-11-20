<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Anchor_Tag {
	
	public static function call($attributes) {
		
		// Get Data
		$link_path = $attributes[1];
		
		// Check for Root
		if($link_path == "/") {
			$link_path = "";
		}
		
		// Get other Paths
		$url = Registry::get("base.path").$link_path;
		$actual_path = Registry::get("request.path");
		
		// Check for Root
		if($actual_path == "/") {
			$actual_path = "";
		}
		
		// Remove trailling Slash
		if(substr($actual_path,0,1) == "/") {
			$actual_path = substr($actual_path,1);
		}
		
		// Check 
		$options = array();
		if($actual_path == $link_path) {
			$options["class"] = "link-current";
		} else if ($link_path != "" && strpos($actual_path,$link_path) === 0) {
			$options["class"] = "link-active";
		}
		
		// Return Anchor
		return HTML::anchor($url,$attributes[0],$options);
	}
	
}