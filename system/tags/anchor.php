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
		if(substr($actual_path,1,1) == "/") {
			$actual_path = substr($actual_path,1);
		}
		$options = array();
		if($actual_path == $attributes[1]) {
			$options["class"] = $attributes[2];
		}
		return HTML::anchor($url,$attributes[0],$options);
	}
	
}