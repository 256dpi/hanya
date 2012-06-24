<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class System_Tag {
	
	public static function call($attributes) {
	  switch($attributes[0]) {
	    case "reset-mail-sent": Memory::set("mail.sent",false); break;
	  }
	  return "";
	}
	
}