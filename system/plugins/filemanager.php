<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler
 * @package Hanya
 **/

class Filemanager_Plugin extends Plugin {
	
	/* EVENTS */
	
	// Main View
	public static function on_filemanager($new=false) {
		
		// Check Admin
		self::_check_admin("filemanager_access");
		
		// Get Data
		$current_directory = Request::get("directory");
		if (!$current_directory || !Disk::has_directory($current_directory)) {
			$current_directory = "uploads";
		}
		
		// Read Content
		$content = Disk::read_directory($current_directory);
		
		// Render View
		Registry::set("toolbar.alternate",true);
		echo Render::file("system/views/filemanager/main.html",array("current_directory"=>$current_directory,"content"=>$content));
		
		// End
		exit;
	}
	
	// Add a Directory
	public static function on_filemanager_create_directory() {
		
		// Check Admin
		self::_check_admin("filemanager_access");
		
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
		self::_check_admin("filemanager_access");
		
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
		self::_check_admin("filemanager_access");
		
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
	
	// Delete directory
	public static function on_filemanager_delete_directory() {
	  
	  // Check Admin
	  self::_check_admin("filemanager_access");
	  
	  // Get Dir
	  $dir = Request::get("directory");
	  
	  // Try to Remove Directory
	  Disk::remove_directory_secure($dir);
	  	  
	  // End
	  URL::redirect_to_referer();
	}
	
	// Upload a File
	public static function on_filemanager_upload_file() {
		
		// Check Admin
		self::_check_admin("filemanager_access");
		
		// Get Data
		$current_directory = Request::post("current_directory");
		$file = Request::file("new_file");
		
		// Check
		if($current_directory && $file) {
			
			// Copy File
			Disk::copy($file["tmp_name"],$current_directory."/".Helper::filename($file["name"]));	
		}
		
		// End
		URL::redirect_to_referer();
	}
	
	/* METHODS */
	
	// Display Tree
	public static function tree($current_directory,$directory="uploads") {
	  
	  // Begin
	  $return = "<ul>";
	  $id = ($current_directory == $directory)?"open":"";
	  $return .= '<li id="'.$id.'"class="directory-icon"><span class="entry" data-path="'.$directory.'">'.$directory.'</span>'.self::_tree($current_directory,$directory)."</li>";
	  $return .= "</ul>";
	  return $return;
	  
	}
	
	// Display Tree
	protected static function _tree($current_directory,$directory="uploads") {
		
		// Get Data
		$data = Disk::read_directory($directory);
		
		// Begin
		$return = "<ul>";
		
		// Render Folders
		foreach($data as $dir => $files) {
			if($dir != ".") {
			  $id = ($current_directory == $directory."/".$dir)?"open":"";
				$return .= '<li data-path="'.$directory."/".$dir.'" id="'.$id.'"class="directory-icon"><span class="delete"></span><span class="entry">'.$dir.'</span>'.self::_tree($current_directory,$directory."/".$dir)."</li>";
			}
		}
		
		// End
		return $return."</ul>";
	}
	
}