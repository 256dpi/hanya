<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class BlockTag {
	
	public static function call($attributes) {
		return Registry::get("block.".$attributes[0]);
	}
	
}