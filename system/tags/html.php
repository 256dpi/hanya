<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Html_Tag {
	
	private static $_htmls = array();
	
	public static function call($attributes) {
		if(!self::$_htmls) {
			foreach(ORM::for_table("html")->find_many() as $html) {
				self::$_htmls[$html->key] = $html->value;
			}
		}
		
		$key = $attributes[0];
		if(!array_key_exists($key,self::$_htmls)) {
			$html = ORM::for_table("html")->create();
			$html->key = $key;
			$html->value = "<p>Lorem ipsum dolor sit amet, <strong>consetetur sadipscing elitr</strong>, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. <em>Stet clita kasd gubergren</em>, no sea takimata sanctus est Lorem ipsum dolor sit amet.<p>";
			$html->save();
			self::$_htmls[$key] = $html->value;
		}
		
		if(Memory::get("edit_page")) {
			$html = ORM::for_table("html")->where("key",$key)->find_one();
			return Helper::wrap_as_editable($html->value,"html",$html->id);
		} else {
			return self::$_htmls[$key];
		}
	}
	
}