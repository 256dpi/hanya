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
		$current_version = Helper::read_file("system/VERSION");
		$installable_version = Helper::read_url(Registry::get("system.version_url"));
		
		// Begin Update Script
		echo HTML::header(1,"Update Hanya");
		
		// Check for new Version
		echo HTML::header(2,"System Information");
		echo HTML::paragraph("Current Version: <strong>".$current_version."</strong>");
		echo HTML::paragraph("Installable Version: <strong>".$installable_version."</strong>");
		
		// Has no Update?
		if($current_version >= $installable_version) {
			echo HTML::header(2,"Nothing to Update!");
		}
		
		// Has Update?
		if($current_version < $installable_version) {
			echo HTML::header(2,"Install new Version from GitHub");
			echo HTML::header(3,"Check Filesystem Permissions");
		}
		
		
		
		
		
		// End
		echo HTML::paragraph(HTML::anchor(Helper::url(),"Return to your Homepage"));
		exit;
	}
	
}