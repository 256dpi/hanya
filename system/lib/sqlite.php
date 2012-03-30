<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Sqlite {
	
	// Create Sqlite Database if no one exists
	public static function create_database($file) {
		
		// Check if Files not exists
		if(!Disk::has_file($file)) {
			
			// Create a Dummy Connection
			$db = sqlite_open($file,0777);
			
			// Close Connection
			sqlite_close($db);
		}
	}
	
	// Get Tables
	public static function tables() {
		$tables = array();
		foreach(ORM::get_db()->query("SELECT * FROM sqlite_master")->fetchAll() as $table) {
		  if($table["tbl_name"] != "sqlite_sequence") {
		    $tables[] = $table["tbl_name"];
		  }
		}
		return $tables;
	}
	
	// Get Table Info
	public static function table($table) {
		$data = ORM::get_db()->query("PRAGMA table_info(".$table.");")->fetchAll();
		$ret = array();
		foreach($data as $field) {
			$ret[$field["name"]] = $field["type"];
		}
		return $ret;
	}
	
	// Generate Creation Code
	public static function generate_create($table,$obj) {
		
		// Begin
		$sql = "CREATE TABLE ".$table." ( id INTEGER PRIMARY KEY AUTOINCREMENT";
		
		// Check for Orderable
		if($obj->orderable) {
			$sql .= ", ordering INTEGER";
		}
		
		// Add Fields
		foreach($obj->blueprint as $field => $def) {
			switch($def["as"]) {
				case "reference":
				case "number":
				case "boolean": $sql .= ", ".$field." INTEGER"; break;
				case "file":
				case "selection":
				case "string": 
				case "html":
				case "textile":
				case "text": 
				case "date":
				case "time": $sql .= ", ".$field." TEXT"; break;
			}
		}
		
		// End
		return $sql." );";
	}
	
}