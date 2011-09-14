<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Helper {
	
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
	
	// Return a URL Safe Filename
	public static function filename($string) {
		$string = strtolower(trim($string));
		$string = preg_replace('/[^a-z0-9_.]/','',$string);
		return preg_replace('/_+/',"_",$string);
	}
	
}