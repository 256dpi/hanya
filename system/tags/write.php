<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class WriteTag {
	
	public static function call($attributes) {
		Registry::set("var.".$attributes[0],$attributes[1]);
	}
	
}