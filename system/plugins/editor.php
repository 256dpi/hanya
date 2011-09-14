<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler
 * @package Hanya
 **/

class Editor_Plugin extends Plugin {
	
	// Perform a Update of the System
	public static function on_editor() {
		
		// Check Admin
		self::_check_admin();
		
		// Get Current File
		$current_file = Request::get("file");
		
		if(!$current_file) {
			$current_file = "tree/index.html";
		}
		
		// Render View
		echo Render::file("system/views/editor/main.html",array("current_file"=>$current_file));
		
		// End
		exit;
	}
	
	// Get Sourcecode of a File
	public static function on_editor_source() {
		
		// Get File
		$current_file = Request::get("file");
		
		// Read Content
		echo Disk::read_file($current_file);
		
		// End
		exit;
	}
	
	// Save File
	public static function on_editor_save() {
		
		// Get Data
		$file = Request::get("file");
		$source = str_replace(array('\"',"\'"),array('"',"'"),Request::post("source","raw"));
		
		// Check
		if(Disk::has_file($file)) {
			Disk::remove_file($file);
			Disk::create_file($file,$source);
			echo "ok";
		} else {
			echo "error";
		}
		
		// End
		exit;
	}
	
	// Display Tree
	public static function tree($current_file,$directory="tree") {
		
		// Get Data
		$data = Disk::read_directory($directory);
		
		// Begin
		$return = "<ul>";
		
		// Render Folders
		foreach($data as $dir => $files) {
			if($dir != ".") {
				$return .= '<li class="directory"><span>'.$dir.'</span>'.self::tree($current_file,$directory."/".$dir)."</li>";
			}
		}
		
		// Render Files
		foreach($data["."] as $file) {
			$id = ($current_file==$directory."/".$file)?"open":"";
			$return .= '<li id="'.$id.'" class="file"><span data-path="'.$directory."/".$file.'">'.$file.'</span></li>';
		}
		
		// End
		return $return."</ul>";
	}
		
}