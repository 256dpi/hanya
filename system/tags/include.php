<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class IncludeTag {
	
	public static function call($attributes) {
		return Render::file("elements/".$attributes[0].".html");
	}
	
}