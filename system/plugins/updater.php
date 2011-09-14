<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler
 * @package Hanya
 **/

class Updater_Plugin extends Plugin {
	
	// Perform a Update of the System
	public static function on_updater() {
		
		// Check Admin
		self::_check_admin();
		
		// Render View
		echo Render::file("system/views/updater/main.html",array());
		
		// End
		exit;
	}
	
	// Get Version
	public static function on_updater_version() {
		
		// Get Data
		$version = HANYA_VERSION;
		$installable_version = Url::load(Registry::get("system.version_url"));
		$has_update = ($version < $installable_version);
		
		// Render View
		echo Render::file("system/views/updater/version.html",array("version"=>$version,"installable_version"=>$installable_version,"has_update"=>$has_update));
		
		// End
		exit;
	}
	
}