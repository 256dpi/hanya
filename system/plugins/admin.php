<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class AdminPlugin extends Plugin {
	
	// Check for ../admin/ in URL
	public static function before_execution() {
		
		// Get Segments
		$segments = Registry::get("request.segments");
		
		// Check for admin
		if($segments[0] == "admin") {
			
			// Get Action
			$action = $segments[1];
			
			if($action) {
				
				// Delegate Action
				self::_delegate("AdminPlugin",$action);
				
			} else {
				
				//Check Login State
				if(Memory::get("admin.logged_in")) {
					
					// Redirecto to Base
					Helper::redirect();
					
				} else {
					
					// Redirecto to Login
					Helper::redirect("admin/login");
					
				}
			}
			
			// End
			exit;
		}
	}
	
	// Login a User
	public static function action_login() {
		
		// Has Entered Credentials?
		if(isset($_SERVER["PHP_AUTH_USER"]) && isset($_SERVER["PHP_AUTH_PW"])) {
			
			// Get Credentials
			$username = $_SERVER["PHP_AUTH_USER"];
			$password = $_SERVER["PHP_AUTH_PW"];
			
			// Get Users
			$users = Registry::get("auth.users");
			
			// Check Credentials
			if(array_key_exists($username,$users) && $users[$username] == $password) {
				
				// Set Memory
				Memory::set("logged_in",true);
				Memory::set("edit_page",false);
				
				// Redirect to Base
				Helper::redirect();
			} else {
				
				// Request Login Again
				self::_request_login();
				
			}
		} else {
			
			// Request Login Again
			self::_request_login();
			
		}
	}
	
	// Logout User
	public static function action_logout() {
		
		// Set Memory
		Memory::set("logged_in",false);
		Memory::set("edit_page",false);
		
		// Redirect
		Helper::redirect_to_referer();
		
	}
	
	// Edit Page
	public static function action_edit() {
		
		// Set Memory
		if(Memory::get("logged_in")) {
			Memory::set("edit_page",true);
		}
		
		// Redidrect
		Helper::redirect_to_referer();
	}
	
	// Show Page
	public static function action_show() {
		
		// Set Memory
		if(Memory::get("logged_in")) {
			Memory::set("edit_page",false);
		}
		
		// Redirect
		Helper::redirect_to_referer();
		
	}
	
	// Request Login
	private static function _request_login() {
		
		// Set Memory
		Memory::set("logged_in",false);
		Memory::set("edit_page",false);
		
		// Send Header
		HTTP::authenticate("Hanya Admin","Sie haben keinen Zugriff auf den Administrationsbereich!");
		
		// End
		exit;
		
	}
	
}