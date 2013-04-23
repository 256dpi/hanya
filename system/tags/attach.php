<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class Attach_Tag {
	
	public static function call($attributes) {
		switch($attributes[1]) {
			case "file" : $addon = Render::file("elements/partials/".$attributes[2].".html"); break;
			case "html" : $addon = $attributes[2]; break;
		}
		Registry::append("block.".$attributes[0],$addon);
	}
	
}