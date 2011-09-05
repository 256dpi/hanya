<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Include_Tag {
	
	public static function call($attributes) {
		return Render::file("elements/".$attributes[0].".html");
	}
	
}