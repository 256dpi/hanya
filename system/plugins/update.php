<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler
 * @package Hanya
 **/

class UpdatePlugin extends Plugin {
	
	// Perform a Update of the System
	public static function on_update() {
		
		// Get Data
		$current_version = Disk::read_file("system/VERSION");
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
			echo HTML::paragraph("Load: <strong>".Registry::get("system.update_url")."</strong>");
			flush();
			
			// Load Actual Version
			$tempfile = sys_get_temp_dir()."hanya_update.zip";
			$tempdir = sys_get_temp_dir()."hanya_update/";
			if(!copy(Registry::get("system.update_url"),$tempfile)) {
				echo HTML::paragraph("Failed to load Update!");
			}
			
			// Next Steps
			echo HTML::paragraph("Unpack Update from <strong>".$tempfile."</strong> to <strong>".$tempdir."</strong>");
			flush();
			
			// Unpack Update
			Disk::remove_directory($tempdir);
			Disk::create_directory($tempdir);
			Disk::unzip($tempfile,$tempdir);
			
			// Get Revision
			$folders = scandir($tempdir,1);
			$revision = $folders[0];
			echo HTML::paragraph("Install Revision: <strong>".$revision."</strong>");
			flush();
			
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
			Disk::empty_directory($teal_public_system_dir);
			
			// Copy New Files
			Disk::copy_directory($tmp_system_dir,$real_system_dir);
			Disk::copy_directory($tmp_public_system_dir,$real_public_system_dir);
			
			// Info
			echo HTML::paragraph("<strong>Update completed</strong>");
		}
		
		// End
		echo HTML::paragraph(HTML::anchor(Helper::url(),"Return to your Homepage"));
		exit;
	}
	
	private static function _check_directory($dir) {
		$return = array();
		foreach(Disk::read_directory($dir) as $folder => $files) {
			if($folder == ".") {
				foreach($files as $file) {
					if(Disk::permission($dir."/".$file) < 777) {
						$return[$dir."/".$file] = "Set Permission to 777 for: <strong>".$dir."/".$file."</strong>";
					}
				}
			} else {
				if(Disk::permission($dir."/".$folder) < 777) {
					$return[$dir."/".$folder] = "Set Permission to 777 for: <strong>".$dir."/".$folder."</strong>";
				}
				$return = array_merge($return,self::_check_directory($dir."/".$folder));
			}
		}
		return $return;
	}
	
}