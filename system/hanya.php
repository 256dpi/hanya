<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

// Log Script Start for Benchmarking
define("HANYA_SCRIPT_START",microtime(true));

// Require All Classes
require("helper.php");
Helper::import_folder("system/lib");
Helper::import_folder("system/tags");
Helper::import_folder("system/plugins");
Helper::import_folder("system/definitions");
Helper::import_folder("user/tags");
Helper::import_folder("user/plugins");
Helper::import_folder("user/definitions");
Helper::import_folder("system/vendor");

class Hanya {
	
	// Expressions for Dynamic Points
	private static $_dynamic_points = array();
	
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
		self::$_dynamic_points[$static] = '#^'.$expression.'$#uD';
	}

	// Start the Main Hanya Process
  public static function run($config) {
	
		// Initialize Persistent Memory
		Memory::initialize();
		
		// Process Configuration
	  self::_initialize($config);
	
		// Dispatch First Event
		Helper::dispatch("after_initialize");
		
		// Check for Command
		if(Request::has_get("command") && Memory::get("logged_in")) {
			
			// Dispatch Event
			Helper::dispatch("on_".Request::get("command"));
			
			// Redirect to Referer
			Helper::redirect_to_referer();
		}
		
		// Get Request Path
		$path = Registry::get("request.path");
		
		// Check for Dynamic Point
		foreach(self::$_dynamic_points as $id => $dynamic_point) {
			if(preg_match($dynamic_point,Registry::get("request.path"),$match)) {
				
				// Override Path
				$path = $id;
				
				// Save Varibales
				Registry::set("request.variables",$match);
			}
		}
		
		// Get Segments
		Registry::set("request.segments",explode("/",$path));
		
		// Dispatch Event
		Helper::dispatch("before_execution");
		
		// Set Default Content Type
		HTTP::content_type();
		
		// Render an Echo File
		echo Render::page(Helper::tree_file_from_segments(Registry::get("request.segments")));
  }
	
	// Initialize the Hanya System
	private static function _initialize($config) {
		
		// Load Default System Settings
		Registry::load(array(
			"system.automatic_db_setup" => true,
			"system.update_url" => "http://github.com/256dpi/Hanya/zipball/master",
			"system.version_url" => "https://raw.github.com/256dpi/Hanya/master/system/VERSION"
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
		
		// Set Request Path
		Registry::set("request.path",Request::path());
		
		// Set Language Settings
		I18n::initialize(Registry::get("i18n.languages"));
		
		// Configure Database
		ORM::configure(Registry::get("db.location"));
		ORM::configure("username",Registry::get("db.user"));
		ORM::configure("password",Registry::get("db.password"));
		
		// Load Plugins
		$system_plugins = Helper::read_directory("system/plugins");
		$user_plugins = Helper::read_directory("user/plugins");
		Registry::set("loaded.plugins",str_replace(".php","",array_merge($system_plugins["."],$user_plugins["."])));
		
		// Load Tags
		$system_tags = Helper::read_directory("system/tags");
		$user_tags = Helper::read_directory("user/tags");
		Registry::set("loaded.tags",str_replace(".php","",array_merge($system_tags["."],$user_tags["."])));
		
		// Load Definitions
		$system_definitions = Helper::read_directory("system/definitions");
		$user_definitions = Helper::read_directory("user/definitions");
		Registry::set("loaded.definitions",str_replace(".php","",array_merge($system_definitions["."],$user_definitions["."])));
		
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
			foreach(Registry::get("loaded.definitions") as $table) {
				
				// Database has Table?
				if(!in_array($table,$tables)) {
					
					// Get Class
					$class = ucfirst($table)."Definition";
					
					// Get Creation Code
					switch(Registry::get("db.driver")) {
						case "sql": $sql = Mysql::generate_create($table,$class::$blueprint); break;
						case "sqlite": $sql = Sqlite::generate_create($table,$class::$blueprint); break;
					}
					
					// Execute Code
					ORM::get_db()->exec($sql);
				}
			}
		}
	}
			
}