<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class String_Tag {
	
	private static $_strings = array();
	
	public static function call($attributes) {
		if(!self::$_strings) {
			foreach(ORM::for_table("string")->find_many() as $string) {
				self::$_strings[$string->key] = $string->value;
			}
		}
		
		$key = $attributes[0];
		if(!array_key_exists($key,self::$_strings)) {
			$string = ORM::for_table("string")->create();
			$string->key = $key;
			$string->value = "Lorem Ipsum dolor sit amet";
			$string->save();
			self::$_strings[$key] = $string->value;
		}
		
		if(Memory::get("edit_page")) {
			$string = ORM::for_table("string")->where("key",$key)->find_one();
			return Helper::wrap_as_editable($string->value,"string",$string->id);
		} else {
			return self::$_strings[$key];
		}
	}
	
}