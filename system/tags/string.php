<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class String_Tag {
	
	// Store Data
	private static $_strings = array();
	
	// Tag Call
	public static function call($attributes) {
		
		// Fetch Data from DB once
		if(!self::$_strings) {
			foreach(ORM::for_table("string")->find_many() as $string) {
				self::$_strings[$string->key] = $string->value;
			}
		}
		
		// Get Key
		$key = $attributes[0];
		
		// If not exists Create with lipsum
		if(!array_key_exists($key,self::$_strings)) {
			
			// Get ORM
			$string = ORM::for_table("string")->create();
			
			// Set
			$string->key = $key;
			$string->value = "Lorem Ipsum dolor sit amet";
			
			// Save
			$string->save();
			
			// Store new Data
			self::$_strings[$key] = $string->value;
		}
		
		// Check for page editing
		if(Memory::get("edit_page")) {
			
			// Get ORM
			$string = ORM::for_table("string")->where("key",$key)->find_one();
			
			// Return HTML
			return HTML::div(null,"hanya-editable",$string->value,array(
				"data-id"=>$string->id,
				"data-definition"=>"string",
				"data-is-orderable"=>"false",
				"data-is-destroyable"=>"false"
			));

		} else {
			
			// Return Data
			return self::$_strings[$key];
			
		}
	}
}