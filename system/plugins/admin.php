<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Admin_Plugin extends Plugin {
	
	// View Login Form
	public static function on_admin_form() {
		
		// Render View
		echo Render::file("system/views/admin/login.html");
		exit;
	}
	
	// Perform Login
	public static function on_admin_login() {
		
		// Has Entered Credentials?
		if(Request::has_post("username") && Request::has_post("password")) {
			
			// Get Credentials
			$username = Request::post("username");
			$password = Request::post("password");
			
			// Get Users
			$users = Registry::get("auth.users");

			// Iterate Users
			foreach($users as $name => $user) {

				// Check Credentials
				if($name == $username && $user["pass"] == $password) {

					// Set Memory
					Memory::set("logged_in",true);
					Memory::set("edit_page",false);
					Memory::set("logged_in_user",$name);
				
					// Redirect to Base
					Url::redirect_to_referer();
				}

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