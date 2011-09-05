<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class I18n {
	
	// Loaded Translations
	static $_i18n = array();
	
	// Initialize the I18n System
	public static function initialize($languages) {
		
		// Get Current Language
		$lang = Registry::get("i18n.default");
		Registry::set("i18n.language",$lang);
		
		// User First Defined Language
		setlocale(LC_ALL,$languages[$lang]["locale"]);
		date_default_timezone_set($languages[$lang]["timezone"]);
		
		// Load System Translations
		$system_files = Disk::read_directory("system/i18n");
		foreach($system_files["."] as $file) {
			if(strpos($file,$lang)) {
				$meta = explode(".",str_replace(".ini","",$file));
				self::$_i18n[$meta[1]][$meta[0]] = parse_ini_file("system/i18n/".$file,true);
			}
		}
		
		// Load User Translations
		$user_files = Disk::read_directory("extensions/i18n");
		foreach($user_files["."] as $file) {
			if(strpos($file,$lang)) {
				$meta = explode(".",str_replace(".ini","",$file));
				self::$_i18n[$meta[1]][$meta[0]] = parse_ini_file("extensions/i18n/".$file,true);
			}
		}
	}
	
	// Get a Translation by Key
	public static function _($key,$variables=array()) {
		
		// Get Key Segments
		$meta = explode(".",$key);
		
		// Load String
		$string = self::$_i18n[Registry::get("i18n.language")][$meta[0]][$meta[1]][$meta[2]];
		
		// Check String
		if($string != null) {
			
			// Replace Variables
			foreach($variables as $key => $value) {
				$string = str_replace("#{".$key."}",$value,$string);
			}
			
			// Return Processed String
			return $string;
			
		} else {
			// Return Key to indicate missing Translation
			return Registry::get("i18n.language").".".$key;	
		}
		
	}
	
}