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
			
			// Display Error
			$html .= HTML::div(null,"hanya-errorbar","&rarr; ".$error);
		}
		
		// Check for Admin
		if(Memory::get("logged_in")) {
			
			// Open Toolbar
			$html = HTML::div_open(null,"hanya-toolbar");
			
			// Left Buttons
			$html .= HTML::div_open(null,"hanya-toolbar-left");
			$html .= HTML::span("Hanya",array("class"=>"hanya-toolbar-info"));
			if(Registry::get("toolbar.alternate")) {
			  $html .= HTML::anchor(Registry::get("base.url"),I18n::_("system.admin.back_to_site"));
			} else if(Memory::get("edit_page")) {
				$html .= HTML::anchor(Registry::get("base.url")."?command=admin_show",I18n::_("system.admin.show"));
			} else {
				$html .= HTML::anchor(Registry::get("base.url")."?command=admin_edit",I18n::_("system.admin.edit"));
			}
			$html .= HTML::div_close();
			
			// Right Buttons
			$html .= HTML::div_open(null,"hanya-toolbar-right");
			if(!Registry::get("toolbar.alternate")) {
			  $html .= HTML::span("#{HANYA_GENERATION_TIME} - #{HANYA_MEMORY_PEAK}",array("class"=>"hanya-toolbar-info"));
			}
			
			// Commands
			if(Helper::user_has_privilege("filemanager_access"))
				$html .= HTML::anchor(Registry::get("base.url")."?command=filemanager",I18n::_("system.admin.filemanager"));
			if(Helper::user_has_privilege("database_access"))
				$html .= HTML::anchor(Registry::get("base.url")."?command=database",I18n::_("system.admin.database"));
			if(Helper::user_has_privilege("editor_access"))
				$html .= HTML::anchor(Registry::get("base.url")."?command=editor",I18n::_("system.admin.editor"));
			if(Helper::user_has_privilege("updater_access"))
				$html .= HTML::anchor(Registry::get("base.url")."?command=updater",I18n::_("system.admin.update"));
			$html .= HTML::anchor(Registry::get("base.url")."?command=admin_logout",I18n::_("system.admin.logout"));
			$html .= HTML::div_close();
			
			// Close Toolbar
			$html .= HTML::div_close();
			
		} else {
			
			// Add Login Link
			$html .= HTML::div(null,"hanya-admin-login",HTML::anchor(null,null,array("onclick"=>"Hanya.login()")));
			
		}
		
		// End
		return $html;
	}
	
}