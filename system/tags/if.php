<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class If_Tag {
	
	public static function call($attributes) {
	  if($attributes[1] === "true") {
	    $attributes[1] = true;
	  }
		if($attributes[0] == $attributes[1]) {
			return $attributes[2];
		} else {
			if(isset($attributes[3])) {
				return $attributes[3];
			} else {
				return "";
			}
		}
	}
	
}