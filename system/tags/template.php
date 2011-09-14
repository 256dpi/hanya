<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Template_Tag {
	
	public static function call($attributes) {
		Registry::set("site.template",$attributes[0]);
	}
	
}