<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler
 * @package Hanya
 **/

class Mailer_Plugin extends Plugin {
	
	// Perform a Update of the System
	public static function on_mailer() {
				
		// Get Data
		$config = Registry::get("mail.forms");
		$vars = Request::post("mail","array");
		$template = Request::post("form");
		
		// Check Form
		if(!array_key_exists($template,$config)) {
			die("Hanya Config: define mail");
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
		
		// Send Mail & Redirect
		Mail::send($reciever,$subject,$message);
		URL::redirect_to_referer();
		exit;
	}
	
}