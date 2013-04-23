<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler
 * @package Hanya
 **/

class Mailer_Plugin extends Plugin {
	
	// Perform a Update of the System
	public static function on_mailer() {
				
		// Get Data
		$config = Registry::get("mail.forms");
		$vars = Request::post("mail","array");
		$template = Request::post("form");
		$token = Request::post("token");
		
		// Check Form
		if(!array_key_exists($template,$config)) {
			die("Hanya Config: define mail");
		}

		// Check Protection
		switch($config[$template]["protection"]) {
			case "captcha": {
				if(!Recaptcha::check($vars)) {
					Memory::raise("Form validation error occured");
					URL::redirect_to_referer();
				}
				break;
			}
			case "javascript": {
				if(Memory::get("token")."Hanya" != $token) {
					Memory::raise("Form validation error occured");
					URL::redirect_to_referer();
				}
				break;
			}
			case "token": {
				if(Memory::get("token") != $token) {
					Memory::raise("Form validation error occured");
					URL::redirect_to_referer();
				}
				break;
			}
			default: case "none": {
				break;
			}
		}
		
		// Convert LineFeeds
		foreach($vars as $var => $value) {
			$vars[$var] = nl2br($value);
		}
		
		// Get Config
		$reciever = $config[$template]["reciever"];
		$subject = $config[$template]["subject"];
		
		// Get Template
		$message = Disk::read_file("elements/mails/".$template.".html");
		$message = Render::process_variables("mail",$vars,$message);
		
		// Set Session
		Memory::set("mail.sent",true);
		
		// Send Mail & Redirect
		Mail::send($reciever,$subject,$message);
		URL::redirect_to_referer();
		exit;
	}
	
}