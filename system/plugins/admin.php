<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Admin_Plugin extends Plugin {
	
	// Form
	public static function on_admin_form() {
		
		// Form Head
		echo HTML::div_open(null,"hanya-manager-head");
		echo HTML::span(I18n::_("system.admin.login"));
		echo HTML::anchor("javascript:HanyaWindow.remove()",I18n::_("system.manager.close"),array("class"=>"hanya-manager-head-close"));
		echo HTML::div_close();
		
		// Open Body
		echo HTML::div_open(null,"hanya-manager-body");
		echo HTML::form_open(Registry::get("request.referer")."?command=admin_login");
		
		// Open Row
		echo HTML::div_open(null,"hanya-manager-body-row");
		echo HTML::text("username",I18n::_("system.admin.username"),null,array("id"=>"hanya-input-username"));
		echo HTML::password("password",I18n::_("system.admin.password"),null,array("id"=>"hanya-input-password"));
		echo HTML::div_close();
		
		// Close Manager
		echo HTML::submit(I18n::_("system.admin.login"));
		echo HTML::form_close();
		echo HTML::div_close();
		
		// End
		exit;
	}
	
	// Login
	public static function on_admin_login() {
		
		// Has Entered Credentials?
		if(Request::has_post("username") && Request::has_post("password")) {
			
			// Get Credentials
			$username = Request::post("username");
			$password = Request::post("password");
			
			// Get Users
			$users = Registry::get("auth.users");
			
			// Check Credentials
			if(array_key_exists($username,$users) && $users[$username] == $password) {
				
				// Set Memory
				Memory::set("logged_in",true);
				Memory::set("edit_page",false);
				
				// Redirect to Base
				Url::redirect_to_referer();
				
			}
			
		}
		
		// Redirect to Base
		Memory::raise(I18n::_("system.admin.error"));
		Url::redirect_to_referer();
		
	}
	
	// Logout
	public static function on_admin_logout() {

		// Set Memory
		Memory::set("logged_in",false);
		Memory::set("edit_page",false);
		
		// Redirect
		Url::redirect_to_referer();
		
	}	
	
	// Edit Page
	public static function on_admin_edit() {
		
		// Set Memory
		if(Memory::get("logged_in")) {
			Memory::set("edit_page",true);
		}
		
		// Redidrect
		Url::redirect_to_referer();
	}
	
	// Show Page
	public static function on_admin_show() {
		
		// Set Memory
		if(Memory::get("logged_in")) {
			Memory::set("edit_page",false);
		}
		
		// Redirect
		Url::redirect_to_referer();
		
	}
	
}