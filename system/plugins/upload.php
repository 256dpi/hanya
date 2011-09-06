<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Upload_Plugin extends Plugin {
	
	// Show Manager
	public static function on_upload_manager() {
		
		// Check Admin
		if(!Memory::get("logged_in")) {
			die(Render::page("elements/errors/403.html"));
		}
		
		// Form Head
		echo HTML::div_open(null,"hanya-manager-head");
		echo HTML::span(I18n::_("system.upload.title"));
		echo HTML::anchor("javascript:Manager.remove()",I18n::_("system.manager.close"),array("class"=>"hanya-manager-head-close"));
		echo HTML::div_close();
		
		// Open Body
		echo HTML::div_open(null,"hanya-manager-body");
		echo HTML::form_open(Registry::get("request.referer")."?command=upload_manager");
		
		// Open Row
		echo HTML::div_open(null,"hanya-manager-body-row");

		// Render Browser
		echo self::_browser("uploads");
		
		// Files
		echo self::_files("uploads");

		echo HTML::div_close();
		
		// Close Manager
		echo HTML::form_close();
		echo HTML::div_close();
		
		// End
		exit;
	
	}
	
	// Show File List
	public static function on_upload_files() {
		
		
		
		
	}
	
	// Render a Table with Files
	private static function _files($folder) {
		
		// Begin
		$return = HTML::div_open(null,"hanya-files-list");
		
		// Get Files
		$data = Disk::read_directory($folder);
		
		// Check Files
		if(count($data["."]) < 1) {
			
			// Message
			$return .= I18n::_("system.files.no_files");
			
		} else {
			
			// Begin Table
			$return .= "<table>";

			// Get Files
			foreach($data["."] as $file) {

				// Row
				$return .= "<tr>";
				$return .= "<td>".HTML::image(Url::_("public/system/filetypes/".Disk::extension($file).".png"))."</td>";
				$return .= "<td>".$file."</td>";
				$return .= "<td>".HTML::anchor(Url::_($folder."/".$file),I18n::_("system.upload.copy_link"))."</td>";
				$return .= "<td>".Disk::filesize($folder."/".$file)."</td>";
				$return .= "<td>".HTML::image(Url::_("public/system/images/cross.png"), array("onclick"=>"Hanya.deleteFile('".$folder."/".$file."')","class"=>"hanya-delete-button"))."</td>";
				$return .= "</tr>";

			}

			// End Table
			$return .= "</table>";
			
		}
		
		// Add Upload Form
		
		// Add Folder Creation Form
		
		// End
		return $return.HTML::div_close();
		
	}
	
	// Render a List from a Directory
	private static function _browser($folder) {
		
		// Return
		$return = '<ul id="hanya-files-browser" class="hanya-files-treeview">';
		$return .= '<li>'.HTML::span($folder,array("class"=>"folder","onclick"=>"Hanya.loadFiles('".$folder."')")).'</li>';
		
		// Get Directories
		$return .= self::_level(Disk::read_directory($folder));
		
		// End
		return $return."</ul>";
		
	}
	
	// Render a Level
	private static function _level($array) {
		
		// Check for Subfolders
		if(count($array) > 1) {
		
			// Begin
			$return = "<ul>";

			// Get Folders
			foreach($array as $folder => $files) {
				if($folder != ".") {

					// Add Current Directory
					$return .= '<li>'.HTML::span($folder,array("class"=>"folder","onclick"=>"Hanya.loadFiles('".$folder."')")).'</li>';

					// Add Subdirectories
					$return .= self::_level($files);
				}
			}

			// End
			return $return."</ul>";
			
		}
		
		// End
		return "";
		
	}
	
}