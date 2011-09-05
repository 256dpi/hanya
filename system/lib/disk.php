<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Disk {
	
	// Read a Directorys Content
	public static function read_directory($directory) {
		if(is_dir($directory)) {
			$return = array("." => array());
			$handler = opendir($directory);
			while ($node = readdir($handler)) {
				if($node[0] != ".") {
					if(is_dir($directory."/".$node)) {
						$return[$node] = self::read_directory($directory."/".$node);
					} else {
						$return["."][] = $node;
					}
				}
			}
			closedir($handler);
			return $return;
		} else {
			die("Disk::read_directory: Is not an Directory: ".$directory);
		}
	}
	
	// Get Content of a File
	public static function read_file($file) {
		if(file_exists($file)) {
			$time = filemtime($file);
			if(Registry::get("site.newest_file") < $time) {
				Registry::set("site.newest_file",$time);
			}
			return file_get_contents($file);
		} else {
			die("Disk::read_file: File '".$file."' does not exist!");
		}
	}
	
	// Eval and get Content of a File
	public static function eval_file($file) {
		if(file_exists($file)) {
			$time = filemtime($file);
			if(Registry::get("site.newest_file") < $time) {
				Registry::set("site.newest_file",$time);
			}
			ob_start();
			include($file);
			$data = ob_get_contents();
			ob_end_clean();
			return $data;
		} else {
			die("Disk::eval_file: File '".$file."' does not exist!");
		}
	}
	
	// Check for Write Right
	public static function writeable($path) {
		if(is_dir($path) || is_file($path)) {
			return is_writable($path);
		} else {
			die("Disk::writeable: Path is not a Directory or File: ".$path);
		}
	}
	
	// Unzip Archive to Directory
	public static function unzip($file,$folder) {
		
		// Open Zip
		$zip = zip_open($file);

		// Get Zip Elements
		while($item = zip_read($zip)) {

			// Get Item Path
			$path = zip_entry_name($item);

			// Check Filetype
			if(substr($path,-1) == "/") {

				// Create Directory
				self::create_directory($folder.$path);

			} else {

				// Create File with Content
				self::create_file($folder.$path,zip_entry_read($item,zip_entry_filesize($item)));

			}
			
			// Close Entry
			zip_entry_close($item);

		}

		// Close Zip
		zip_close($zip);
	}
	
	// Remove Directory
	public static function remove_directory($dir) {
		if(is_dir($dir)) {
			self::empty_directory($dir);
			return rmdir($dir);
		} else {
			die("Disk::remove_directory: Is not a Directory: ".$dir);
		}
	}
	
	// Create Directory
	public static function create_directory($dir) {
		if(!is_dir($dir) && !is_file($dir)) {
			return mkdir($dir);
		} else {
			die("Disk::create_directory: Is an Directory or File: ".$dir);
		}
	}
	
	// Empty Directory
	public static function empty_directory($dir) {
		if(is_dir($dir)) {
			$objects = scandir($dir); 
	    foreach ($objects as $object) {
				if ($object != "." && $object != "..") { 
	      	if (filetype($dir."/".$object) == "dir") {
						self::remove_directory($dir."/".$object);
					} else {
						unlink($dir."/".$object);
					}
				}
			}
			reset($objects);
			return true;
		} else {
			die("Disk::empty_directory: Is not an Directory: ".$dir);
		}
	}
	
	// Copy Directory
	public static function copy_directory($src, $dst) {
	  if (is_dir($src)) {
			if(!is_dir($dst)) {
				self::create_directory($dst);
			}
	    $files = scandir($src);
	    foreach ($files as $file) {
				if ($file != "." && $file != "..") {
					self::copy_directory("$src/$file", "$dst/$file");
				}
			}
	  } else if (file_exists($src)) { 
			copy($src, $dst);
		} else {
			return false;
		}
		return true;
	}
	
	// Has File
	public static function has_file($file) {
		return is_file($file);
	}
	
	// Has Direcotry
	public static function has_directory($dir) {
		return is_dir($dir);
	}

	// Create File
	public static function create_file($path,$data) {
		
		// Create new File
		if(!touch($path)) {
			die("Disk::create_file: Cant create File: ".$path);
		}
		
		// Open Empty File
		if(!$file = fopen($path,"r+")) {
			die("Disk::create_file: Cant open File: ".$path);
		}
		
		// Set Content
		if(!fwrite($file,$data)) {
			die("Disk::create_file: Cant write to File: ".$path);
		}
		
		// Close
		fclose($file);
	}

}