<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class Mysql {
	
	// Get Tables
	public static function tables() {
		$tables = array();
		foreach(ORM::get_db()->query("SHOW TABLES")->fetchAll() as $table) {
			$tables[] = $table[0];
		}
		return $tables;
	}

	// Generate Creation Code
	public static function generate_create($table,$obj) {
		
		// Begin
		$sql = "CREATE TABLE `".$table."` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT";
		
		// Check for Orderable
		if($obj->orderable) {
			$sql .= ", `ordering` int(11) unsigned DEFAULT NULL";
		}
		
		// Add Fields
		foreach($obj->blueprint as $field => $def) {
			switch($def["as"]) {
				case "boolean": $sql .= ", `".$field."` boolean DEFAULT NULL"; break;
				case "reference":
				case "number": $sql .= ", `".$field."` int(11) unsigned DEFAULT NULL"; break;
				case "file":
				case "selection":
				case "string": $sql .= ", `".$field."` varchar(255) DEFAULT NULL"; break;
				case "html":
				case "textile":
				case "markdown":
				case "text": $sql .= ", `".$field."` text DEFAULT NULL"; break;
				case "date": $sql .= ", `".$field."` date DEFAULT NULL"; break;
				case "time": $sql .= ", `".$field."` time DEFAULT NULL"; break;
			}
		}
		
		// End
		return $sql.", PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	}
	
}