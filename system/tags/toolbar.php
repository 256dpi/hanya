<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
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
				$html .= HTML::anchor(null,I18n::_("system.admin.show"),array("class"=>"hanya-has-command","data-command"=>"admin_show"));
			} else {
				$html .= HTML::anchor(null,I18n::_("system.admin.edit"),array("class"=>"hanya-has-command","data-command"=>"admin_edit"));
			}
			$html .= HTML::anchor(null,I18n::_("system.admin.filemanager"),array("class"=>"hanya-has-command","data-command"=>"filemanager"));
			$html .= HTML::div_close();
			
			// Right Buttons
			$html .= HTML::div_open(null,"hanya-toolbar-right");
			$html .= HTML::anchor(null,I18n::_("system.admin.database"),array("class"=>"hanya-has-command","data-command"=>"database"));
			$html .= HTML::anchor(null,I18n::_("system.admin.editor"),array("class"=>"hanya-has-command","data-command"=>"editor"));
			$html .= HTML::anchor(null,I18n::_("system.admin.update"),array("class"=>"hanya-has-command","data-command"=>"updater"));
			$html .= HTML::anchor(null,I18n::_("system.admin.logout"),array("class"=>"hanya-has-command","data-command"=>"admin_logout"));
			$html .= HTML::div_close();
			
			// Close Toolbar
			$html .= HTML::div_close();
			
		} else {
			
			// Add Login Link
			$html .= HTML::div(null,"hanya-admin-login",HTML::anchor(null,HTML::span(I18n::_("system.admin.login_link")),array("onclick"=>"Hanya.login()")));
			
		}
		
		// End
		return $html;
	}
	
}