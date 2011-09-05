<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class IfTag {
	
	public static function call($attributes) {
		$var = Registry::get("var.".$attributes[0]);
		if($var == $attributes[1]) {
			return $attributes[2];
		} else {
			return "";
		}
	}
	
}