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
		
		// Has no Update?
		if(HANYA_VERSION >= $installable_version) {
			echo HTML::paragraph(I18n::_("system.updater.no_update"));
			exit;
		}
			
		// Info
		echo HTML::paragraph(I18n::_("system.updater.load_update",array("url"=>Registry::get("system.update_url"))));
		
		// Load Actual Version
		$tempfile = Disk::clean_path(sys_get_temp_dir()."/hanya_update.zip");
		$tempdir = Disk::clean_path(sys_get_temp_dir()."/hanya_update/");
		$data = Url::load(Registry::get("system.update_url"));
		Disk::create_file($tempfile,$data);
		
		// Check Download
		if(!Disk::has_file($tempfile)) {
			echo HTML::paragraph(I18n::_("system.updater.load_update_error"),array("class"=>"error"));
			exit;
		}
		
		// Next Steps
		echo HTML::paragraph(I18n::_("system.updater.unpack_update",array("from"=>$tempfile,"to"=>$tempdir)));
		
		// Directories
		Disk::remove_directory($tempdir);
		Disk::create_directory($tempdir);
		
		// Check Permission
		if(!Disk::writeable($tempdir)) {
			HTML::paragraph(I18n::_("system.updater.temporary_directory_error"),array("class"=>"error"));
			exit;
		}
		
		// Unzip
		$output = Disk::unzip($tempfile,$tempdir);
		
		// Get Revision
		$folders = scandir($tempdir,1);
		$revision = $folders[0];
		echo HTML::paragraph(I18n::_("system.updater.install_revision",array("revision"=>$revision)));
		
		// Check Revision
		if($revision == "" || $revision == "." || $revision == ".." ) {
			echo HTML::paragraph(I18n::_("system.updater.revision_not_found",array("error"=>$output)),array("class"=>"error"));
			exit;
		}
		
		// Set Folders
		$tmp_system_dir = $tempdir.$revision."/system";
		$tmp_public_system_dir = $tempdir.$revision."/public/system";	
		$real_system_dir = Registry::get("system.path")."system";
		$real_public_system_dir = Registry::get("system.path")."public/system";
		
		// Check Permissions
		$dies1 = self::_check_directory($real_system_dir);
		$dies2 = self::_check_directory($real_public_system_dir);
		
		// Empty Directories
		Disk::empty_directory($real_system_dir);
		Disk::empty_directory($real_public_system_dir);
		
		// Copy New Files
		Disk::copy_directory($tmp_system_dir,$real_system_dir);
		Disk::copy_directory($tmp_public_system_dir,$real_public_system_dir);
		
		// Info
		echo HTML::paragraph(I18n::_("system.updater.complete"));
		
		// End
		exit;
	}
	
	// Check Directory for Write Permission recursively
	private static function _check_directory($dir) {
		$error = false;
		foreach(Disk::read_directory($dir) as $folder => $files) {
			if($folder == ".") {
				foreach($files as $file) {
					if(!Disk::writeable($dir."/".$file)) {
						echo HTML::paragraph(I18n::_("system.updater.set_permission",array("path"=>$dir."/".$file)),array("class"=>"error"));
						$error = true;
					}
				}
			} else {
				if(!Disk::writeable($dir."/".$folder)) {
					echo HTML::paragraph(I18n::_("system.updater.set_permission",array("path"=>$dir."/".$folder)),array("class"=>"error"));
					$error = true;
				}
			}
		}
		if($error) {
			exit;
		}
	}
	
}