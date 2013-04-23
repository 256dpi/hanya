<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class Mail {
	
	// Send a Mail
	public static function send($to,$subject,$body) {
		
		// Check for Sender Address
		if(!Registry::get("mail.sender")) {
			die("Hanya Config: Define 'mail.sender'");
		} else {
			$sender = Registry::get("mail.sender");
		}

		// Set Header
		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$header .= 'From: Hanya <'.$sender.'>' . "\r\n";

		// verschicke die E-Mail
		return mail($to,$subject,$body,$header);		
	}
	
}