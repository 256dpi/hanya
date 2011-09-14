<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Write_Tag {
	
	public static function call($attributes) {
		Registry::set("var.".$attributes[0],$attributes[1]);
	}
	
}