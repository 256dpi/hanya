<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Head_Tag {
	
	public static function call($attributes) {
		
		// Base URL
		$html = '<base href="'.Registry::get("base.url").'" />';
		
		// Stylesheets
		if(Memory::get("logged_in")) {
		  $html .= HTML::stylesheet(Url::_("assets/system/stylesheets/reset.css"));
		} else {
		  switch($attributes[0]) {
		    case "normalize": $html .= HTML::stylesheet(Url::_("assets/system/stylesheets/normalize.css"));
		    default:
		    case "reset": $html .= HTML::stylesheet(Url::_("assets/system/stylesheets/reset.css")); break;
		  }
		}
		
		// System CSS
		$html .= HTML::stylesheet(Url::_("assets/system/stylesheets/hanya.css"));
		
		// Load jQuery and Hanya JS
		$html .= HTML::script(Url::_("assets/system/javascripts/jquery.js"));
		$html .= HTML::script(Url::_("assets/system/javascripts/hanya.js"));
			
		// Load jQuery Extensions
		$html .= HTML::script(Url::_("assets/system/javascripts/jquery.cleditor.js"));		
		$html .= HTML::stylesheet(Url::_("assets/system/stylesheets/jquery.cleditor.css"));		
		
		// Return
		return $html;
	}

}