<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class NewTag {
	
	public static function call($attributes) {
		if(Memory::get("admin.edit_page")) {
			return HTML::div(null,"hanya-createable",I18n::_("definition.".$attributes[0].".new"),array("data-definition"=>$attributes[0]));
		} else {
			return "";
		}
	}
	
}