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
			$tables[] = $table["tbl_name"];
		}
		return $tables;
	}
	
	// Generate Creation Code
	public static function generate_create($table,$blueprint) {
		
		// Begin
		$sql = "CREATE TABLE ".$table." ( id INTEGER PRIMARY KEY AUTOINCREMENT";
		
		// Add Fields
		foreach($blueprint as $field => $def) {
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