<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

// Log Script Start for Benchmarking
define("HANYA_SCRIPT_START",microtime(true));

// Register Autoloader
spl_autoload_register("Hanya::autoload");

class Hanya {
	
	// Expressions for Dynamic Points
	private static $_dynamic_points = array();
	
	// Static Class Loading Workaround
	public static function call_static($class,$function,$attributes=array()) {
		return call_user_func_array($class."::".$function,$attributes);
	}
	
	// Autoloader
	public static function autoload($class) {
		
		// Uncamelize
		$segments = explode("_",strtolower($class));
		$file = $segments[0].".php";
		
		// Check Parent Class
		if(count($segments) > 1) {
			if($segments[1] == "plugin") {
				if(Disk::has_file("user/plugins/".$file)) {
					require("user/plugins/".$file);
				} else {
					require("system/plugins/".$file);
				}
			} else if($segments[1] == "tag") {
				if(Disk::has_file("user/tags/".$file)) {
					require("user/tags/".$file);
				} else {
					require("system/tags/".$file);
				}
			} else if($segments[1] == "definition") {
				if(Disk::has_file("user/definitions/".$file)) {
					require("user/definitions/".$file);
				} else {
					require("system/definitions/".$file);
				}
			}
		} else {
			require("system/lib/".$segments[0].".php");
		}
	}
	
	// Add a Dynamic Point
	public static function dynamic_point($static,$optional) {
		
		// Code by Kohana Project
		$uri = $static.$optional;

		// The URI should be considered literal except for keys and optional parts
		// Escape everything preg_quote would escape except for : ( ) < >
		$expression = preg_replace('#[.\\+*?[^\\]${}=!|]#','\\\\$0',$uri);

		// Make optional parts of the URI non-capturing and optional
		$expression = str_replace(array('(',')'),array('(?:',')?'),$expression);

		// Insert default regex for keys
		$expression = str_replace(array('<','>'),array('(?P<','>[^/.,;?\n]++)'),$expression);

		// Add Expression to List
		self::$_dynamic_points[$static] = '!^'.$expression.'$!uD';
	}

	// Start the Main Hanya Process
  public static function run($config) {
	
		// Initialize Persistent Memory
		Memory::initialize();
		
		// Process Configuration
	  self::_initialize($config);
	
		// Dispatch First Event
		Plugin::dispatch("after_initialize");
		
		// Check for Command
		if(Request::has_get("command")) {
			
			// Dispatch Event
			Plugin::dispatch("on_".Request::get("command"));
		}
		
		// Get Request Path
		$path = Registry::get("request.path");
		
		// Check for Dynamic Point
		foreach(self::$_dynamic_points as $id => $dynamic_point) {
			if(preg_match($dynamic_point,Registry::get("request.path"),$match)) {
				
				// Override Path
				$path = $id;
				
				// Save Variables
				Registry::set("request.variables",$match);
			}
		}
		
		// Get Segments
		Registry::set("request.segments",Request::get_segments($path));

		// Dispatch Event
		Plugin::dispatch("before_execution");
		
		// Set Default Content Type
		HTTP::content_type();
		
		// Set Admin Meta Flag
		Registry::set("meta.is_admin",Memory::get("logged_in"));
		Registry::set("meta.is_editing",Memory::get("edit_page"));
		
		// Get Render
		$out = Render::page(Helper::tree_file_from_segments(Registry::get("request.segments")));
		
		// Replace Benchmark Info
		$time = round((microtime(true)-HANYA_SCRIPT_START)*1000)."ms";
		$peak = round(memory_get_peak_usage()/1024)."KB";
		echo str_replace(array("#{HANYA_GENERATION_TIME}","#{HANYA_MEMORY_PEAK}"),array($time,$peak),$out);
  }
	
	// Initialize the Hanya System
	private static function _initialize($config) {
		
		// Load Default System Settings
		Registry::load(array(
			"system.automatic_db_setup" => true,
			"system.update_url" => "https://github.com/256dpi/Hanya/zipball/master",
			"system.review_url" => "https://github.com/api/v2/json/commits/list/256dpi/Hanya/master",
			"meta.template" => "default",
		));
		
		// Load Config
		Registry::load($config);
		
		// Autmatic Base Path
		if(!Registry::has("base.path")) {
			Registry::set("base.path",str_replace("index.php","",$_SERVER["SCRIPT_NAME"]));
		}
		
		// Automatic Base URL
		if(!Registry::has("base.url")) {
			Registry::set("base.url","http://".$_SERVER["HTTP_HOST"].Registry::get("base.path"));
		}
		
		// Set System Path
		Registry::set("system.path",$_SERVER["DOCUMENT_ROOT"].Registry::get("base.path"));
		
		// Set Referer if exists 
		if(array_key_exists("HTTP_REFERER",$_SERVER)) {
			Registry::set("request.referer",$_SERVER["HTTP_REFERER"]);
		} else {
			Registry::set("request.referer",Registry::get("base.url"));
		}
		
		// Set Request Path & Remove Leading Slash
		$path = Request::path();
		if(substr($path,0,1) == "/") {
			$path = substr($path,1);
		}
		Registry::set("request.path",$path);
		
		// Set Language Settings
		I18n::initialize(Registry::get("i18n.languages"));
		
		// Configure Database
		ORM::configure(Registry::get("db.location"));
		ORM::configure("username",Registry::get("db.user"));
		ORM::configure("password",Registry::get("db.password"));
		
		// Get Plugins
		$system_plugins = Disk::read_directory("system/plugins");
		if(Disk::has_directory("user/plugins")) {
			$user_plugins = Disk::read_directory("user/plugins");
			$plugins = array_merge($system_plugins["."],$user_plugins["."]);
		} else {
			$plugins = $system_plugins["."];
		}
		Registry::set("available.plugins",str_replace(".php","",$plugins));
		
		// Load Definitions
		$system_definitions = Disk::read_directory("system/definitions");
		if(Disk::has_directory("user/definitions")) {
			$user_definitions = Disk::read_directory("user/definitions");
			$definitions = array_merge($system_definitions["."],$user_definitions["."]);
		} else {
			$definitions = $system_definitions["."];
		}
		Registry::set("available.definitions",str_replace(".php","",$definitions));
		
		// Check for Automatic DB Setup
		if(Registry::get("system.automatic_db_setup")) {
			
			// Check if Sqlite Db Exists
			if(Registry::get("db.driver") == "sqlite") {
				Sqlite::create_database(str_replace("sqlite:","",Registry::get("db.location")));
			}
			
			// Get Tables
			switch(Registry::get("db.driver")) {
				case "sql": $tables = Mysql::tables(); break;
				case "sqlite": $tables = Sqlite::tables(); break;
			}
		
			// Check each Definition
			foreach(Registry::get("available.definitions") as $table) {
				
				// Database has Table?
				if(!in_array($table,$tables)) {
					
					// Get Class
					$class = ucfirst($table)."_Definition";
					$obj = new $class();
					
					// Check for Managing Flag
					if($obj->managed) {
					
						// Get Creation Code
						switch(Registry::get("db.driver")) {
							case "sql": $sql = Mysql::generate_create($table,$obj); break;
							case "sqlite": $sql = Sqlite::generate_create($table,$obj); break;
						}
						
						// Execute Code
						ORM::get_db()->exec($sql);
					}
				}
			}
		}
	}
}