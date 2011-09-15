<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler
 * @package Hanya
 **/

class Filemanager_Plugin extends Plugin {
	
	/* EVENTS */
	
	// Main View
	public static function on_filemanager() {
		
		// Check Admin
		self::_check_admin();
		
		// Get Data
		$current_directory = Request::get("directory");
		if (!$current_directory) {
			$current_directory = "uploads";
		}
		
		// Read Content
		$content = Disk::read_directory($current_directory);
		
		// Render View
		echo Render::file("system/views/filemanager/main.html",array("current_directory"=>$current_directory,"content"=>$content));
		
		// End
		exit;
	}
	
	// Add a Directory
	public static function on_filemanager_create_directory() {
		
		// Check Admin
		self::_check_admin();
		
		// Get Data
		$current_directory = Request::post("current_directory");
		$new_directory = Request::post("new_directory");
		
		// Create Directory
		if($current_directory && $new_directory) {
			$dir = $current_directory."/".$new_directory;
			if(!Disk::has_directory($dir)) {
				Disk::create_directory($dir);
			}
		}
		
		// Redirect
		URL::redirect_to_referer();
		
		// End
		exit;
	} 
	
	// Show Details of an Item
	public static function on_filemanager_item() {
		
		// Check Admin
		self::_check_admin();
		
		// Get File
		$file = Request::get("file");
		$ext = Disk::extension($file);
		
		// Variables
		$vars = array("file"=>$file);
		
		// Render View
		switch($ext) {
			case "jpeg":
			case "jpg":
			case "png":
			case "gif":
			case "bmp":
			case "tiff": {
				echo Render::file("system/views/filemanager/image.html",$vars);
				break;
			}
			default : {
				echo Render::file("system/views/filemanager/file.html",$vars);
				break;
			}
		}
		
		// End
		exit;
	}
	
	// Delete a File
	public static function on_filemanager_delete_file() {
		
		// Check Admin
		self::_check_admin();
		
		// Get File
		$file = Request::post("file");
		
		//Try to Remove the File
		if(Disk::remove_file($file)) {
			echo "ok";
		} else {
			echo "error";
		}
		
		// End
		exit;
	}
	
	// Upload a File
	public static function on_filemanager_upload_file() {
		
		// Check Admin
		self::_check_admin();
		
		// Get Data
		$current_directory = Request::post("current_directory");
		$file = Request::file("new_file");
		
		// Check
		if($current_directory && $file) {
			
			// Copy File
			Disk::copy($file["tmp_name"],$current_directory."/".Helper::filename($file["name"]));
			
		}
		
		//var_dump($current_directory,$file);
		
		// End
		URL::redirect_to_referer();
	}
	
	/* METHODS */
	
	// Render a List from a Directory
	public static function directorys($current_directory) {
		
		// Return
		$return = '<ul>';
		$id = ($current_directory=="uploads")?"open":"";
		$return .= '<li id="'.$id.'">'.HTML::anchor(Url::_("?command=filemanager&directory=uploads"),"uploads");
		
		// Get Directories
		$return .= self::_level(Disk::read_directory("uploads"),"uploads",$current_directory);		
		
		// End
		return $return."</li></ul>";
		
	}
	
	// Render a Level
	private static function _level($array,$path,$current_directory) {
		
		// Check for Subdirectorys
		if(count($array) > 1) {
		
			// Begin
			$return = "<ul>";

			// Get directorys
			foreach($array as $directory => $files) {
				if($directory != ".") {

					// Add Current Directory
					$id = ($current_directory==$path."/".$directory)?"open":"";
					$return .= '<li id="'.$id.'">'.HTML::anchor(Url::_("?command=filemanager&directory=".$path."/".$directory),$directory);

					// Add Subdirectories
					$return .= self::_level($files,$path."/".$directory,$current_directory);
				}
			}

			// End
			return $return."</li></ul>";
			
		}
		
		// End
		return "";
		
	}
	
}