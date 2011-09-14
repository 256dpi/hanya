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
	
	// Get Latest Changes
	public static function on_updater_review() {
		
		// Get Data
		$data = json_decode(URL::load(Registry::get("system.review_url")));
		
		// Render View
		echo render::file("system/views/updater/review.html",array("data"=>$data));
		
		// End
		exit;
	}
	
	// Update Hanya
	public static function on_updater_update() {
		
		// Check Admin
		self::_check_admin();
				
		// Get Data
		$installable_version = Url::load(Registry::get("system.version_url"));
		
		// Header
		echo HTML::header(1,"Update Hanya");
		echo HTML::paragraph(HTML::anchor(Url::_(),"Return to Website"));
		
		// Has no Update?
		if(HANYA_VERSION >= $installable_version) {
			echo HTML::header(2,"Nothing to Update!");
		}
		
		// Has Update?
		if(HANYA_VERSION < $installable_version) {
			echo HTML::header(2,"Install new Version from GitHub");
			echo HTML::paragraph("Load: <strong>".Registry::get("system.update_url")."</strong>");
			
			// Load Actual Version
			$tempfile = sys_get_temp_dir()."/hanya_update.zip";
			$tempdir = sys_get_temp_dir()."/hanya_update/";
			$data = Url::load(Registry::get("system.update_url"));
			Disk::create_file($tempfile,$data);
			
			// Check Download
			if(!is_file($tempfile)) {
				die("Error while Loading Update from GitHub!");
			}
			
			// Next Steps
			echo HTML::paragraph("Unpack Update from <strong>".$tempfile."</strong> to <strong>".$tempdir."</strong>");
			
			// Directories
			Disk::remove_directory($tempdir);
			Disk::create_directory($tempdir);
			
			// Check Permission
			if(!Disk::writeable($tempdir)) {
				die("Temporary Directory ist not writeable");
			}
			
			// Unzip
			$output = Disk::unzip($tempfile,$tempdir);
			
			// Get Revision
			$folders = scandir($tempdir,1);
			$revision = $folders[0];
			echo HTML::paragraph("Install Revision: <strong>".$revision."</strong>");
			
			// Check Revision
			if($revision == "" || $revision == "." || $revision == ".." ) {
				echo HTML::paragraph($output);
				die("Revision not found!");
			}
			
			// Set Folders
			$tmp_system_dir = $tempdir.$revision."/system";
			$tmp_public_system_dir = $tempdir.$revision."/public/system";	
			$real_system_dir = Registry::get("system.path")."system";
			$real_public_system_dir = Registry::get("system.path")."public/system";
			
			// Check Permissions
			$dies1 = self::_check_directory($real_system_dir);
			$dies2 = self::_check_directory($real_public_system_dir);
			
			// Display Errors
			if(count($dies1) > 0 || count($dies2) > 0) {
				foreach($dies1 as $error) {
					echo($error.HTML::br());
				}
				foreach($dies2 as $error) {
					echo($error.HTML::br());
				}
				die();
			}
			
			// Empty Directories
			Disk::empty_directory($real_system_dir);
			Disk::empty_directory($real_public_system_dir);
			
			// Copy New Files
			Disk::copy_directory($tmp_system_dir,$real_system_dir);
			Disk::copy_directory($tmp_public_system_dir,$real_public_system_dir);
			
			// Info
			echo HTML::paragraph("<strong>Update completed</strong>");
		}
		
		// Changelog
		echo HTML::header(2,"Changelog");
		echo "<pre>".Disk::read_file("CHANGELOG.md")."</pre>";
		
		// End
		exit;
		
	}
	
}