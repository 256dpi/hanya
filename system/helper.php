<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Helper {
	
	/* CLASS LOADING */
	
	// Import Class
	public static function import() {
		foreach(func_get_args() as $file) {
			require($file.".php");
		}
	}
	
	// Import Classes in Directory
	public static function import_folder($folder) {
		$files = self::read_directory($folder);
		foreach($files["."] as $file) {
			require($folder."/".$file);
		}
	}
	
	/* URL HANDLING */
	
	// Get Contents of URL
	public static function read_url($url) {
		return file_get_contents($url);
	}
	
	/* LOCATION HANDLING */
	
	public static function redirect_to_referer() {
		if(Registry::get("request.referer") != "") {
			HTTP::location(Registry::get("request.referer"));
		} else {
			HTTP::location(Registry::get("base.url"));
		}
		exit;
	}
	
	public static function url($add="") {
		return Registry::get("base.url").$add;
	}
	
	public static function redirect($add="") {
		HTTP::location(self::url($add));
		exit;
	}
	
	/* SPECIAL FUNCTIONS */
	
	// Wrap HTML as Editable
	public static function wrap_as_editable($html,$definition,$id) {
		return HTML::div(null,"hanya-editable",$html,array("data-id"=>$id,"data-definition"=>$definition));
	}
	
	// Get a Tree File from Segments
	public static function tree_file_from_segments($segments) {
		if($segments[0] != "") {
			return "tree/".join($segments,"/").".html";
		} else {
			return "tree/index.html";
		}
	}

}