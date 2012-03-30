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
		
		// Check for Template
		if(Registry::get("meta.template")) {
		  
		  // Get Template
  		$template_file = "elements/templates/".Registry::get("meta.template").".html";

  		// Process Template
  		if(Disk::has_file($template_file)) {
  			return self::file($template_file);	
  		} else {
  			die("Hanya: Template '".$template_file."' does not exists!");
  		}
		  
		} else {
		  return $content;
		}
	}
	
	// Process a File
	public static function file($file,$variables=array()) {
		
		// Evaluate Fie
		if(Disk::extension($file) == "xml") {
		  $output = Disk::read_file($file);
		} else {
		  $output = Disk::eval_file($file,$variables);
		}
		
		// Process the Meta Block And Process Variables
		$output = self::_process_metablock($output);
		$output = self::process_variables("meta",Registry::get("meta"),$output);
		
		// Process "request" Variables
		$output = self::process_variables("request",Registry::get("request.variables"),$output);
		
		// Process "system" Variables
		$output = self::process_variables("system",array("mail-sent"=>Memory::get("mail.sent")),$output);
		
		// Process Definitions
		$output = self::_process_definitions($output);
		
		// Process Conditions
		$output = self::_process_conditions($output);
		
		// Process Tags
		$output = self::_process_tags($output);
		
		// End
		return $output;
	}
	
	// Process Metablock
	private static function _process_metablock($output) {
		preg_match_all('!^\/\/--(.*)--\/\/!Us',$output,$match);
		if(isset($match[1][0])) {
			Registry::set("meta",array_merge(Registry::get("meta"),parse_ini_string($match[1][0])));
			$output = str_replace($match[0][0]."\n","",$output);
		}
		return $output;
	}
	
	// Process Variables
	public static function process_variables($name,$vars,$output) {
		if($vars) {
			preg_match_all('!\$'.$name.'\((.+)\)!Us',$output,$matches);
			foreach($matches[0] as $i => $var) {
				$attributes = explode("|",$matches[1][$i]);
				if(isset($vars[$attributes[0]])) {
					$rep = $vars[$attributes[0]];
				} else {
					$rep = "";
				}
				$output = str_replace($matches[0][$i],$rep,$output);
			}
		}
		return $output;
	}
	
	// Process Conditions
	private static function _process_conditions($output) {
		while(preg_match('!\[(-*)\?\((.*)\)\](.*)\[/\1\?]!Us',$output,$match)) {
			$attributes = explode("|",$match[2]);
			if(strpos($match[3],"[".$match[1]."?:]")) {
				preg_match('!^(.*)\['.$match[1].'\?\:\](.*)$!Us',$match[3],$match2);
				$when_true = $match2[1];
				$when_false =$match2[2];
			} else {
				$when_true = $match[3];
				$when_false = "";
			}
			switch(count($attributes)) {
				case 1: $is = ($attributes[0] != ""); break;
				case 2: $is = ($attributes[0] == $attributes[1]); break;
				case 3: {
					switch($attributes[1]) {
						case ">": $is = ($attributes[0]>$attributes[2]); break;
						case "<": $is = ($attributes[0]<$attributes[2]); break;
						case "<=": $is = ($attributes[0]<=$attributes[2]); break;
						case ">=": $is = ($attributes[0]>=$attributes[2]); break;
					}
					break;
				}
			}
			if($is) {
				$output = str_replace($match[0],$when_true,$output);
			} else {
				$output = str_replace($match[0],$when_false,$output);
			}
		}
		return $output;
	}
	
	// Process Definitions
	private static function _process_definitions($output) {
		while(preg_match('!\[(\!*)(-*)([a-z]+)\((.*)\)\](.*)\[/\2\3\]!Us',$output,$match)) {
			$attributes = explode("|",$match[4]);
			$output = str_replace($match[0],self::_execute_definition($match[3],$attributes,$match[5],$match[2],$match[1]=="!"),$output);
		}
		return $output;
	}
	
	// Process Tags
	private static function _process_tags($output) {
		preg_match_all('!\{([a-z-_]+)\((.*)\)\}!Us',$output,$matches);
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
			return Hanya::call_static($classname,"call",array($attributes));
		} else {
			die("Hanya: Tag '".$tag."' is not defined!");
		}
	}
	
	// Execute Definition	
	private static function _execute_definition($definition,$attributes,$sub,$childness,$no_edit) {
		
		// Get Class
		$class = ucfirst($definition)."_Definition";
		
		// Invoke Definition's Load Method
		$obj = new $class();
		
		$items = $obj->load($definition,$attributes);
		
		// Set Output
		$output = "";
		
		// Process Items
		foreach($items as $item) {
			
			// If Managed Invovke Magic Functions
			if($obj->managed) {
				
				// Process Special Fields
				foreach($obj->blueprint as $field => $config) {
					switch($config["as"]) {
						case "boolean": {
							if($item[$field]) {
								$val = I18n::_("definition.".$definition.".".$field."_true");
							} else {
								$val = I18n::_("definition.".$definition.".".$field."_false");
							}	
							$item[$field."_text"] = $val;
							break;
						}
						case "selection": {
							$item[$field."_value"] = $config["options"][$item[$field]];
							break;
						}
						case "file": {
							$item[$field."_path"] = $config["folder"]."/".$item[$field];
							break;
						}
					}
				}
			}
			
			// Process Variables
			$data = self::process_variables($definition,$item,$sub);
			
			// Check for Login
			if(Memory::get("edit_page") && $obj->managed) {
				
				// Get Options
				$options = array("data-id"=>$item["id"],"data-definition"=>$definition);
				$options["data-is-orderable"] = $obj->orderable?"true":"false";
				$options["data-is-destroyable"] = $obj->destroyable?"true":"false";
				$options["data-level"] = strlen($childness);
				
				// Render HTML
				if($no_edit) {
					$output .= $data;
				} else {
					$output .= HTML::div(null,"hanya-editable",$data,$options);
				}
				
			} else {
				
				// Render Data
				$output .= $data;
				
			}	
		}
		
		// End
		return $output;
	}
	
}