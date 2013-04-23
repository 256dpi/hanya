<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class Redirect_Tag {
	
	public static function call($attributes) {
		if(!$attributes[1]) {
			HTTP::location(Registry::get("base.path").$attributes[0]);
		} else {
			HTTP::location($attributes[0]);
		}
		exit;
	}
	
}