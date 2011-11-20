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
		$url = Registry::get("base.path").$attributes[1];
		$actual_path = Registry::get("request.path");
		if($actual_path == "/") {
			$actual_path = "";
		}
		if(substr($actual_path,0,1) == "/") {
			$actual_path = substr($actual_path,1);
		}
		$options = array();
		if($actual_path == $attributes[1]) {
			$options["class"] = "link-current";
		} else if (strpos($actual_path,$attributes[1]) == 0) {
			$options["class"] = "link-active";
		}
		return HTML::anchor($url,$attributes[0],$options);
	}
	
}