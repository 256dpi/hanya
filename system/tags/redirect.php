<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Redirect_Tag {
	
	public static function call($attributes) {
		HTTP::location(Registry::get("base.path").$attributes[0]);
		exit;
	}
	
}