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
		
		// Get Versions
		$local_version = self::_local_version();
		$remote_version = self::_remote_version();
		$has_update = ($local_version < $remote_version);
		
		// Render View
		Registry::set("toolbar.alternate",true);
		echo Render::file("system/views/updater/main.html",compact("local_version","remote_version","has_update"));
		
		// End
		exit;
	}
	
	// Get Latest Changes
	public static function on_updater_review() {
	  
		// Check Admin
		self::_check_admin();
		
		// Get Data
		$markdown = new Markdown();
		$changelog = $markdown->transform(URL::load("https://raw.github.com/256dpi/hanya/master/CHANGELOG.md"));
		
		// Render View
		echo HTML::header(2,I18n::_("system.updater.changelog")).$changelog;
		
		// End
		exit;
	}
	
	// Update Hanya
	public static function on_updater_update() {
		
		// Check Admin
		self::_check_admin();
				
		// Get Versions
		$local_version = self::_local_version();
		$remote_version = self::_remote_version();
		$has_update = ($local_version < $remote_version);
		
		// Has no Update?
		if(!$has_update) {
			echo HTML::paragraph(I18n::_("system.updater.no_update"));
			exit;
		}
			
		// Info
		echo HTML::paragraph(I18n::_("system.updater.load_update",compact("remote_version")));
		
		// Load Actual Version
		$tempfile = Disk::clean_path(sys_get_temp_dir()."/hanya_update.zip");
		$tempdir = Disk::clean_path(sys_get_temp_dir()."/hanya_update/");
		$tags = json_decode(Url::load("https://api.github.com/repos/256dpi/hanya/tags"));
		$data = Url::load($tags[0]->zipball_url);
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
		$update = Disk::get_all_directories(Disk::read_directory($tempdir));
		$update = $update[0];
		echo HTML::paragraph(I18n::_("system.updater.install_update",compact("update")));
		
		// Set Folders
		$tmp_system_dir = $tempdir.$update."/system";
		$tmp_public_system_dir = $tempdir.$update."/assets/system";	
		$real_system_dir = Registry::get("system.path")."system";
		$real_public_system_dir = Registry::get("system.path")."assets/system";
		
		// Check Permissions
		self::_check_directory($real_system_dir);
		self::_check_directory($real_public_system_dir);
		
		// Empty Directories
		Disk::empty_directory($real_system_dir);
		Disk::empty_directory($real_public_system_dir);
		
		// Copy New Files
		Disk::copy($tmp_system_dir,$real_system_dir);
		Disk::copy($tmp_public_system_dir,$real_public_system_dir);
		
		// Write File
		Disk::remove_file("user/system.version");
		Disk::create_file("user/system.version",$remote_version);
		
		// Info
		echo HTML::paragraph(I18n::_("system.updater.complete"));
		
		// End
		exit;
	}
	
	// Check Directory for Write Permission recursively
	private static function _check_directory($dir) {
		$error = false;
		if(!Disk::writeable($dir)) {
			echo HTML::paragraph(I18n::_("system.updater.set_permission",array("path"=>$dir)),array("class"=>"error"));
			$error = true;
		} else {
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
		}
		if($error) {
			exit;
		}
	}
	
	// Get Installed version
	private static function _local_version() {
		if(Disk::has_file("user/system.version")) {
			return Disk::read_file("user/system.version");
		} else {
			return "0";
		}
	}
	
	// Get Remote Version
	private static function _remote_version() {
		$data = json_decode(Url::load("https://api.github.com/repos/256dpi/hanya/tags"));
		return substr($data[0]->name,1);
	}
	
}