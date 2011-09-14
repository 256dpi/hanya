<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class If_Tag {
	
	public static function call($attributes) {
		$var = Registry::get("var.".$attributes[0]);
		if($var == $attributes[1]) {
			return $attributes[2];
		} else {
			return "";
		}
	}
	
}