<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler
 * @package Hanya
 **/

class Database_Plugin extends Plugin {
	
	// Perform a Update of the System
	public static function on_database() {
		
		// Check Admin
		self::_check_admin();
		
		// Get Current Table
		$current_table = Request::get("table");
		if(!$current_table) {
			switch(Registry::get("db.driver")) {
				case "sql": $tables = Mysql::tables(); break;
				case "sqlite": $tables = Sqlite::tables(); break;
			}
			$current_table = $tables[0];
		}
		
		// Get Current Table Info
		switch(Registry::get("db.driver")) {
			case "sql": $current_structure = Mysql::table($current_table); break;
			case "sqlite": $current_structure = Sqlite::table($current_table); break;
		}
		
		// Render View
		echo Render::file("system/views/database/main.html",array("current_table"=>$current_table,"current_structure"=>$current_structure));
		
		// End
		exit;
	}
	
	public static function on_database_delete_table() {
		
		// Check Admin
		self::_check_admin();
		
		// Get Current Table
		$current_table = Request::get("table");
		
		// Check Table
		if($current_table) {
			
			// Drop Table
			ORM::get_db()->exec("DROP TABLE ".$current_table);
		}
		
		// Render Normal View
		Url::redirect_to_referer();
	}
	
	// Display Tables
	public static function tables($current_table) {
		
		// Get Tables
		switch(Registry::get("db.driver")) {
			case "sql": $tables = Mysql::tables(); break;
			case "sqlite": $tables = Sqlite::tables(); break;
		}
		
		// Begin
		$return = "<ul>";
		
		// Render Folders
		foreach($tables as $table) {
			if($table != "sqlite_sequence") {
				$return .= '<li class="table"><span data-table="'.$table.'">'.$table."</span></li>";
			}
		}
		
		// End
		return $return."</ul>";
	}
		
}