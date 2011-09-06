<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Toolbar_Tag {
	
	public static function call($attributes) {
		
		// Return
		$html = "";
		
		// Get Errors
		$error = Memory::errors();
		
		// Check
		if($error) {
			
			// Open Errors Bar
			$html .= HTML::div(null,"hanya-errorbar",$error);
		}
		
		// Check for Admin
		if(Memory::get("logged_in")) {
			
			// Open Toolbar
			$html = HTML::div_open(null,"hanya-toolbar");
			
			// Left Buttons
			$html .= HTML::div_open(null,"hanya-toolbar-left");
			if(Memory::get("edit_page")) {
				$html .= HTML::anchor(null,I18n::_("system.admin.show"),array("onclick"=>"Hanya.command('admin_show')"));
			} else {
				$html .= HTML::anchor(null,I18n::_("system.admin.edit"),array("onclick"=>"Hanya.command('admin_edit')"));
			}
			$html .= HTML::div_close();
			
			// Middle
			$html .= HTML::div(null,"hanya-toolbar-middle",I18n::_("system.admin.title"));
			
			// Right Buttons
			$html .= HTML::div_open(null,"hanya-toolbar-right");
			$html .= HTML::anchor(null,I18n::_("system.admin.update"),array("onclick"=>"Hanya.command('update')"));
			$html .= HTML::anchor(null,I18n::_("system.admin.logout"),array("onclick"=>"Hanya.command('admin_logout')"));
			$html .= HTML::div_close();
			
			// Close Toolbar
			$html .= HTML::div_close();
			
		} else {
			
			// Add Login Link
			$html .= HTML::div(null,"hanya-admin-login",'<a onclick="Hanya.login()">'.I18n::_("system.admin.login_link").'</a>');
			
		}
		
		// End
		return $html;
	}
	
}