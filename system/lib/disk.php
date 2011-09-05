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
		$return = array("." => array());
		if(is_dir($directory)) {
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
		}
		return $return;
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
			die("Hanya: File '".$file."' does not exist!");
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
			die("Hanya: File '".$file."' does not exist!");
		}
	}
	
	// Get Permissions
	public static function permission($file,$octal=false) {
		if(!file_exists($file)) return false;
		$perms = fileperms($file);
		$cut = $octal ? 1 : 2;
		return substr(decoct($perms), $cut);
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
				if(!self::create_directory($folder.$path)) {
					die("Failed to create Directory: '".$folder.$path."'!");
				}
				
			} else {
				
				// Create new File
				if(!touch($folder.$path)) {
					die("Failed to create File: '".$folder.$path."'!");
				}
				
				// Open Empty File
				$file = fopen($folder.$path,"r+");
				
				// Set Content
				fwrite($file,zip_entry_read($item,zip_entry_filesize($item)));
				
				// Close
				fclose($file);
			}
				
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
			return false;
		}
	}
	
	// Create Directory
	public static function create_directory($dir) {
		if(!is_dir($dir) && !is_file($dir)) {
			return mkdir($dir,0777);
		} else {
			return false;
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
			return false;
		}
	}
	
	// Copy Directory
	public static function copy_directory($src, $dst) {
	  if (is_dir($src)) {
	    self::create_directory($dst);
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
}