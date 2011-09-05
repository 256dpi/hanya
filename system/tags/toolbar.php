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
		if(Memory::get("admin.logged_in")) {
			$html = HTML::div_open("hanya-toolbar");
			if(Memory::get("admin.edit_page")) {
				$html .= HTML::anchor(Helper::url("admin/show"),I18n::_("system.admin.show"),array("id"=>"hanya-button-left"));
			} else {
				$html .= HTML::anchor(Helper::url("admin/edit"),I18n::_("system.admin.edit"),array("id"=>"hanya-button-left"));
			}
			$html .= I18n::_("system.admin.title");
			$html .= HTML::anchor(Helper::url("admin/logout"),I18n::_("system.admin.logout"),array("id"=>"hanya-button-right"));
			$html .= HTML::div_close();
			return $html;
		} else {
			return "";
		}
	}
	
}