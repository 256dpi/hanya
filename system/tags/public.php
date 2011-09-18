<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Public_Tag {
	
	public static function call($attributes) {
		return Url::_("public/".$attributes[0]);
	}
	
}