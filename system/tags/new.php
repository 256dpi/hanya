<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class New_Tag {
	
	public static function call($attributes) {
		if(Memory::get("edit_page")) {
			return HTML::div(null,"hanya-createable",I18n::_("definition.".$attributes[0].".new_entry"),array("data-definition"=>$attributes[0]));
		} else {
			return "";
		}
	}
	
}