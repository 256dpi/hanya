<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class Html_Tag {
	
	// Store Data
	private static $_htmls = array();
	
	// Tag Call
	public static function call($attributes) {
		
		// Fetch Data from DB once
		if(!self::$_htmls) {
			foreach(ORM::for_table("html")->find_many() as $html) {
				self::$_htmls[$html->key] = $html->value;
			}
		}
		
		// Get Key
		$key = $attributes[0];
		
		// Check for existense
		if(!array_key_exists($key,self::$_htmls)) {
			
			// Get ORM
			$html = ORM::for_table("html")->create();
			
			// Set
			$html->key = $key;
			$html->value = "<p>Lorem ipsum dolor sit amet, <strong>consetetur sadipscing elitr</strong>, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. <em>Stet clita kasd gubergren</em>, no sea takimata sanctus est Lorem ipsum dolor sit amet.<p>";
			
			// Save
			$html->save();
			
			// Store new Data
			self::$_htmls[$key] = $html->value;
			
		}
		
		// Check for Edit mode
		if(Memory::get("edit_page")) {
			
			// Get ORM
			$html = ORM::for_table("html")->where("key",$key)->find_one();
			
			// Return HTML
			return HTML::div(null,"hanya-editable",$html->value,array(
				"data-id"=>$html->id,
				"data-definition"=>"html",
				"data-is-orderable"=>"false",
				"data-is-destroyable"=>"false"
			));
			
		} else {
			
			// Return Data
			return self::$_htmls[$key];
			
		}
	}
	
}