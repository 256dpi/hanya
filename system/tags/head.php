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
		$html .= HTML::stylesheet(Url::_("public/system/stylesheets/reset.css"));
		$html .= HTML::stylesheet(Url::_("public/system/stylesheets/hanya.css"));
		
		// Load jQuery and Hanya JS
		$html .= HTML::script(Url::_("public/system/javascripts/jquery.js"));
		$html .= HTML::script(Url::_("public/system/javascripts/hanya.js"));
			
		// Load jQuery Extensions
		$html .= HTML::script(Url::_("public/system/javascripts/jquery.cleditor.js"));
		//$html .= HTML::script(Url::_("public/system/javascripts/jquery.treeview.js"));
		//$html .= HTMl::script(Url::_("public/system/javascripts/jquery.form.js"));			
		$html .= HTML::stylesheet(Url::_("public/system/stylesheets/jquery.cleditor.css"));		
		
		// Return
		return $html;
	}

}