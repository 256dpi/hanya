<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class UpdatePlugin extends Plugin {
	
	// Perform a Update of the System
	public static function on_update() {
		
		// Get Data
		$current_version = Helper::read_file("VERSION");
		$installable_version = Helper::read_url(Registry::get("system.version_url"));
		
		//Check for new Version
		echo HTML::header(1,"System Information");
		echo HTML::paragraph("Current Version: ".$current_version);
		echo HTML::paragraph("Installable Version: ".$installable_version);
		
		
		
		
		
		die();
		
	}
	
}