<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class Text_Tag {
	
	// Store Data
	private static $_texts = array();
	
	// Tag Call
	public static function call($attributes) {
		
		// Fetch all texts from DB once
		if(!self::$_texts) {
			foreach(ORM::for_table("text")->find_many() as $text) {
				self::$_texts[$text->key] = $text->value;
			}
		}
		
		// Get Key
		$key = $attributes[0];
		
		// If not exists Create with lipsum
		if(!array_key_exists($key,self::$_texts)) {
			
			// Get ORM
			$text = ORM::for_table("text")->create();
			
			// Set
			$text->key = $key;
			$text->value = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
			
			// Save
			$text->save();
			
			// Add to Data
			self::$_texts[$key] = $text->value;
			
		}
		
		// Check for Edit Mode
		if(Memory::get("edit_page")) {
			
			// Get ORM
			$text = ORM::for_table("text")->where("key",$key)->find_one();
			
			// Return HTML
			return HTML::div(null,"hanya-editable",$text->value,array(
				"data-id"=>$text->id,
				"data-definition"=>"text",
				"data-is-orderable"=>"false",
				"data-is-destroyable"=>"false"
			));
				
		} else {
			
			// Return Data
			return self::$_texts[$key];
		}
	}
}