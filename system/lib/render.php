<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Render {
	
	// Render a Page with All Tags and Definitions
	public static function page($page) {
		
		//Check Existence
		if(Disk::has_file($page)) {
			
			// Process Content
			$content = self::file($page);
			
		} else {
			
			// Send Header
			HTTP::not_found();
			
			// Check Error File
			$error_file = "elements/errors/404.html";
			if(Disk::has_file($error_file)) {
				
				// Process Error File
				$content = self::file($error_file);
				
			} else {
				
				// Die with Error
				die("Error 404 - Page not found! Define 'elements/errors/404.html' to Render a Error Page");
				
			}
		}
		
		// Append to Content
		Registry::set("block.content",$content);
		
		// Get Template
		$template_file = "elements/templates/".Registry::get("meta.template").".html";
		
		// Process Template
		if(Disk::has_file($template_file)) {
			return self::file($template_file);	
		} else {
			die("Hanya: Template '".$template_file."' does not exists!");
		}
	}
	
	// Process a File
	public static function file($file,$variables=array()) {
		
		// Evaluate Fie
		$output = Disk::eval_file($file,$variables);
		
		// Process the Meta Block And Process Variables
		$output = self::_process_metablock($output);
		$output = self::process_variables("meta",Registry::get("meta"),$output);
		
		// Process "request" Variables
		$output = self::process_variables("request",Registry::get("request.variables"),$output);
		
		// Process Definitions
		$output = self::_process_definitions($output);
		
		// Process Tags
		$output = self::_process_tags($output);
		
		// End
		return $output;
	}
	
	// Process Metablock
	private static function _process_metablock($output) {
		preg_match_all('!^\/\/--(.*)--\/\/!Us',$output,$match);
		Registry::set("meta",array_merge(Registry::get("meta"),parse_ini_string($match[1][0])));
		$output = str_replace($match[0][0],"",$output);
		return $output;
	}
	
	// Process Variables
	public static function process_variables($name,$vars,$output) {
		if($vars) {
			preg_match_all('!\$'.$name.'\((.+)\)!Us',$output,$matches);
			foreach($matches[0] as $i => $var) {
				$attributes = explode("|",$matches[1][$i]);
				$output = str_replace($matches[0][$i],$vars[$attributes[0]],$output);
			}
		}
		return $output;
	}
	
	// Process Definitions
	private static function _process_definitions($output) {
		while(preg_match('!\[(-*)([a-z]+)\((.*)\)\](.*)\[/\1\2\]!Us',$output,$match)) {
			$attributes = explode("|",$match[3]);
			$output = str_replace($match[0],self::_execute_definition($match[2],$attributes,$match[4]),$output);
		}
		return $output;
	}
	
	// Process Tags
	private static function _process_tags($output) {
		preg_match_all('!\{(.+)\((.*)\)\}!Us',$output,$matches);
		foreach($matches[1] as $i => $tag) {
			$attributes = explode("|",$matches[2][$i]);
			$output = str_replace($matches[0][$i],self::_execute_tag($tag,$attributes),$output);
		}
		return $output;
	}
	
	// Execute a Tag
	private static function _execute_tag($tag,$attributes) {
		$classname = ucfirst($tag)."_Tag";
		if(method_exists($classname,"call")) {
			return $classname::call($attributes);
		} else {
			die("Hanya: Tag '".$tag."' is not defined!");
		}
	}
	
	// Execute Definition	
	private static function _execute_definition($definition,$attributes,$sub) {
		
		// Get Mode
		$mode = count($attributes);
		
		// Get ORM
		$items = ORM::for_table($definition);
		$class = ucfirst($definition)."_Definition";
		
		// Check for false Mode
		if($mode == 1 && $attributes[0] == "") {
			$attributes = array();
			$mode = 0;
		}
		
		// Add Conditions
		if($mode == 0) {
		} else if($mode == 1) {
			$items->where("id",$attributes[0]);
		} else if ($mode%2 == 0) {
			for($i=0; $i < $mode; $i = $i+2) {
				$items->where($attributes[$i],$attributes[$i+1]);
			}
		} else {
			die("Invalid Argument Count '".$mode."' for '".$definition."'");
		}
		
		// Set Output
		$output = "";
		
		// Process Items
		foreach($items->find_many() as $item) {
			
			// Get Array
			$array = $item->as_array();
			
			// Process Special Fields
			foreach($class::$blueprint as $field => $config) {
				switch($config["as"]) {
					case "boolean": {
						if($array[$field]) {
							$val = I18n::_("definition.".$definition.".".$field."_true");
						} else {
							$val = I18n::_("definition.".$definition.".".$field."_false");
						}	
						$array[$field."_text"] = $val;
						break;
					}
					case "selection": {
						$array[$field."_value"] = $config["options"][$array[$field]];
						break;
					}
					case "file": {
						$array[$field."_path"] = $config["folder"]."/".$array[$field];
						break;
					}
				}
			}
			
			// Process Variables
			$data = self::process_variables($definition,$array,$sub);
			
			// Check for Login
			if(Memory::get("edit_page")) {
				$output .= Helper::wrap_as_editable($data,$definition,$item->id);
			} else {
				$output .= $data;
			}
			
		}
		
		// End
		return $output;
	}
	
}