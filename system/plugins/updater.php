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
	public static function on_updater_check() {
		
		// Get Revisions
		$revision = self::_revision();
		$remote_revision = self::_remote_revision();
		$has_update = ($revision != $remote_revision);
		
		// Render View
		echo Render::file("system/views/updater/check.html",array("revision"=>$revision,"remote_revision"=>$remote_revision,"has_update"=>$has_update));
		
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
		$revision = self::_revision();
		$remote_revision = self::_remote_revision();
		$has_update = ($revision != $remote_revision);
		
		// Has no Update?
		if(!$has_update) {
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
		$update = "256dpi-Hanya-".$remote_revision;
		echo HTML::paragraph(I18n::_("system.updater.install_revision",array("revision"=>$update)));
		
		// Check Revision
		if(Disk::has_directory($update)) {
			echo HTML::paragraph(I18n::_("system.updater.revision_not_found",array("error"=>$output)),array("class"=>"error"));
			exit;
		}
		
		// Set Folders
		$tmp_system_dir = $tempdir.$update."/system";
		$tmp_public_system_dir = $tempdir.$update."/public/system";	
		$real_system_dir = Registry::get("system.path")."system";
		$real_public_system_dir = Registry::get("system.path")."public/system";
		
		// Check Permissions
		$dies1 = self::_check_directory(".");
		
		// Empty Directories
		Disk::empty_directory($real_system_dir);
		Disk::empty_directory($real_public_system_dir);
		
		// Copy New Files
		Disk::copy_directory($tmp_system_dir,$real_system_dir);
		Disk::copy_directory($tmp_public_system_dir,$real_public_system_dir);
		
		// Write File
		Disk::remove_file("user/system.revision");
		Disk::create_file("user/system.revision",$remote_revision);
		
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
	
	// Get Installed version
	private static function _revision() {
		if(Disk::has_file("user/sytem.revision")) {
			return Disk::read_file("user/system.revision");
		} else {
			return "0";
		}
	}
	
	// Get Remote Revision
	private static function _remote_revision() {
		$data = json_decode(Url::load(Registry::get("system.review_url")));
		return substr($data->commits[0]->id,0,7);
	}
	
}