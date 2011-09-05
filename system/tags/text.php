<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class TextTag {
	
	private static $_texts = array();
	
	public static function call($attributes) {
		if(!self::$_texts) {
			foreach(ORM::for_table("text")->find_many() as $text) {
				self::$_texts[$text->key] = $text->value;
			}
		}
		
		$key = $attributes[0];
		if(!array_key_exists($key,self::$_texts)) {
			$text = ORM::for_table("text")->create();
			$text->key = $key;
			$text->value = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
			$text->save();
			self::$_texts[$key] = $text->value;
		}
		
		if(Memory::get("admin.edit_page")) {
			$text = ORM::for_table("text")->where("key",$key)->find_one();
			return Helper::wrap_as_editable($text->value,"text",$text->id);
		} else {
			return self::$_texts[$key];
		}
	}
	
}