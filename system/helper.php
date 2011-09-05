<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Helper {
	
	/* FILE HANDLING */
	
	// Import Class
	public static function import() {
		foreach(func_get_args() as $file) {
			require($file.".php");
		}
	}
	
	// Import Classes in Directory
	public static function import_folder($folder) {
		$files = self::read_directory($folder);
		foreach($files["."] as $file) {
			require($folder."/".$file);
		}
	}
	
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
			//$data = "";
			//$handle = fopen($file, "r");
			//while(!feof($handle)) {
				//$data .= fgets($handle, 4096);
			//}	
			//fclose($handle);
			//return $data;
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
	
	// Get Contents of URL
	public static function read_url($url) {
		return file_get_contents($url);
	}
	
	// Get Permissions
	public static function permission($file,$octal=false) {
		if(!file_exists($file)) return false;
		$perms = fileperms($file);
		$cut = $octal ? 1 : 2;
		return substr(decoct($perms), $cut);
	}
	
	/* PLUGIN HANDLING */
	
	// Dispatch an Event to Plugins
	public static function dispatch($event,$options=null) {
		foreach(Registry::get("loaded.plugins") as $plugin) {
			$classname = ucfirst($plugin)."Plugin";
			if(class_exists($classname)) {
				if(method_exists($classname,$event)) {
					$classname::$event($options);
				}
			} else {
				die("Hanya: Plugin '".$plugin."' defines no Class '".$classname."!");
			}
		}
	}
	
	/* LOCATION HANDLING */
	
	public static function redirect_to_referer() {
		if(Registry::get("request.referer") != "") {
			HTTP::location(Registry::get("request.referer"));
		} else {
			HTTP::location(Registry::get("base.url"));
		}
		exit;
	}
	
	public static function url($add="") {
		return Registry::get("base.url").$add;
	}
	
	public static function redirect($add="") {
		HTTP::location(self::url($add));
		exit;
	}
	
	/* SPECIAL FUNCTIONS */
	
	// Wrap HTML as Editable
	public static function wrap_as_editable($html,$definition,$id) {
		return HTML::div(null,"hanya-editable",$html,array("data-id"=>$id,"data-definition"=>$definition));
	}
	
	// Get a Tree File from Segments
	public static function tree_file_from_segments($segments) {
		if($segments[0] != "") {
			return "tree/".join($segments,"/").".html";
		} else {
			return "tree/index.html";
		}
	}

}