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
		switch($attributes[0]) {
			case "normalize": $html .= HTML::stylesheet(Url::_("assets/system/stylesheets/normalize.css")); break;
			case "reset":
			default: $html .= HTML::stylesheet(Url::_("assets/system/stylesheets/reset.css")); break;
		}
		
		// System CSS
		$html .= HTML::stylesheet(Url::_("assets/system/stylesheets/hanya.css"));
		
		// Load jQuery and Hanya JS
		$html .= HTML::script(Url::_("assets/system/javascripts/jquery.js"));
		$html .= HTML::script(Url::_("assets/system/javascripts/hanya.js"));
			
		// Load jQuery Extensions
		if(Memory::get("edit_page") || Memory::get("logged_in")) {
  			//$html .= HTML::script(Url::_("assets/system/javascripts/jquery.tipsy.js"));		
  			//$html .= HTML::stylesheet(Url::_("assets/system/stylesheets/jquery.tipsy.css"));
		}

		// FancyBox
  		$html .= HTML::script(Url::_("assets/system/fancybox/jquery.fancybox.js"));
  		$html .= HTML::stylesheet(Url::_("assets/system/fancybox/jquery.fancybox.css"));
		
		// Return
		return $html;
	}

}