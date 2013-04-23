<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class Markdown_Tag {
	
	// Store Data
	private static $_markdowns = array();
	
	// Tag Call
	public static function call($attributes) {
		
		// Fetch Data from DB once
		if(!self::$_markdowns) {
			$m = new Markdown();
			foreach(ORM::for_table("markdown")->find_many() as $markdown) {
				self::$_markdowns[$markdown->key] = $m->transform($markdown->value);
			}
		}
		
		// Get Key
		$key = $attributes[0];
		
		// Check for existense
		if(!array_key_exists($key,self::$_markdowns)) {
			
			// Get ORM
			$markdown = ORM::for_table("markdown")->create();
			
			// Set
			$markdown->key = $key;
			$markdown->value = "# Hello from Markdown\n\nLorem ipsum dolor sit amet, *consetetur sadipscing elitr*, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. __Stet clita kasd gubergren__, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
			
			// Save
			$markdown->save();
			
			// Store new Data
			$m = new Markdown();
			self::$_markdowns[$key] = $m->transform($markdown->value);
			
		}
		
		// Check for Edit mode
		if(Memory::get("edit_page")) {
			
			// Get ORM
			$markdown = ORM::for_table("markdown")->where("key",$key)->find_one();
			
			// Return markdown
			$m = new Markdown();
			return HTML::div(null,"hanya-editable",$m->transform($markdown->value),array(
				"data-id"=>$markdown->id,
				"data-definition"=>"markdown",
				"data-is-orderable"=>"false",
				"data-is-destroyable"=>"false"
			));
			
		} else {
			
			// Return Data
			return self::$_markdowns[$key];
			
		}
	}
	
}