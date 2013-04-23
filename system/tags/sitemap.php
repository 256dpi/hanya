<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class Sitemap_Tag {
	
	public static function call($attributes) {
	  return Sitemap_Plugin::tree_locations();
	}
	
}