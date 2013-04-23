<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class New_Tag {
	
	public static function call($attributes) {
		if(!isset($attributes[1])) {
			$attributes[1] = "false";
		}
		if(Memory::get("edit_page")) {
			return HTML::anchor(null,I18n::_("definition.".$attributes[0].".new_entry"),array("class"=>"hanya-createable","data-definition"=>$attributes[0],"data-argument"=>$attributes[1]));
		} else {
			return "";
		}
	}
	
}