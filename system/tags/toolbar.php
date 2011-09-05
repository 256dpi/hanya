<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class ToolbarTag {
	
	public static function call($attributes) {
		
		// Check for Admin
		if(Memory::get("logged_in")) {
			
			// Open Toolbar
			$html = HTML::div_open(null,"hanya-toolbar");
			
			// Left Buttons
			$html .= HTML::div_open(null,"hanya-toolbar-left");
			if(Memory::get("edit_page")) {
				$html .= HTML::anchor(Helper::url("admin/show"),I18n::_("system.admin.show"));
			} else {
				$html .= HTML::anchor(Helper::url("admin/edit"),I18n::_("system.admin.edit"));
			}
			$html .= HTML::div_close();
			
			// Middle
			$html .= HTML::div(null,"hanya-toolbar-middle",I18n::_("system.admin.title"));
			
			// Right Buttons
			$html .= HTML::div_open(null,"hanya-toolbar-right");
			$html .= HTML::anchor(Helper::url()."?command=update",I18n::_("system.admin.update"));
			$html .= HTML::anchor(Helper::url("admin/logout"),I18n::_("system.admin.logout"));
			$html .= HTML::div_close();
			
			// Close Toolbar
			$html .= HTML::div_close();
			return $html;
			
		} else {
			return "";
		}
	}
	
}