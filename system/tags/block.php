<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Block_Tag {
	
	public static function call($attributes) {
		return Registry::get("block.".$attributes[0]);
	}
	
}