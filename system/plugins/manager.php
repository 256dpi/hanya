<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Manager_Plugin extends Plugin {
	
	// Send Back HTML to Display Form
	public static function on_manager_form() {
		
		// Get Data
		$definition = Request::post("definition");
		$class = ucfirst($definition)."_Definition";
		$id = Request::post("id","int");
		$entry = ORM::for_table($definition);
		
		// Load Entry if ist is a update
		if($id > 0) {
			$entry = $entry->find_one($id);
		}
		
		// Open Manager
		echo HTML::div_open(null,"hanya-manager-head");
		echo HTML::span(I18n::_("definition.".$definition.".edit_entry"));
		echo HTML::anchor("javascript:Hanya.removeManager()",I18n::_("system.manager.close"),array("class"=>"hanya-manager-head-close"));
		if($entry->id) {
			echo HTML::anchor("javascript:Hanya.deleteEntry()",I18n::_("system.manager.delete"),array("class"=>"hanya-manager-head-delete"));
		}
		echo HTML::div_close();
		
		// Open Body
		echo HTML::div_open(null,"hanya-manager-body");
		echo HTML::form_open(Registry::get("request.referer")."?command=manager_update");
		echo HTML::hidden("definition",$definition,array("id"=>"hanya-input-definition"));
		echo HTML::hidden("id",$id,array("id"=>"hanya-input-id"));
		
		// Print Form Elements
		foreach($class::$blueprint as $field => $config) {
			
			// Open Row
			echo HTML::div_open(null,"hanya-manager-body-row");
			
			// Merge Config with defaults
			$config = array_merge($class::$default_config,$config);
			
			// Check for Visibility
			if(!$config["hidden"]) {
				
				// Get Label and Name
				$label = ($config["label"])?I18n::_("definition.".$definition.".field_".$field):null;
				$name = $definition."[".$field."]";
				
				// Switch Field Type
				switch($config["as"]) {
					
					// Boolean
					case "boolean" : {
						echo HTML::label($name,$label);
						echo HTML::div_open(null,"radiogroup");
						echo HTML::radio($name,1,($entry->$field==1)?array("checked"=>"checked"):array()).I18n::_("definition.".$definition.".field_".$field."_true");
						echo HTML::radio($name,0,($entry->$field==0)?array("checked"=>"checked"):array()).I18n::_("definition.".$definition.".field_".$field."_false");
						echo HTML::div_close();
						break;
					}
					
					// Text Inputs
					case "number":
					case "string": echo HTML::text($name,$label,$entry->$field).HTML::br(); break;
					
					// Textareas
					case "html":
					case "textile":
					case "text": echo HTML::textarea($name,$label,$entry->$field).HTML::br(); break;
					
					// Special
					case "time": echo HTML::text($name,$label,$entry->$field,array("class"=>"hanya-timepicker")).HTML::br(); break;
					case "date": echo HTML::text($name,$label,$entry->$field,array("class"=>"hanya-datepicker")).HTML::br(); break;
					case "selection": echo HTML::select($name,$label,HTML::options($config["options"],$entry->$field)).HTML::br(); break;
					
					// Reference Select
					case "reference": {
						$data = array();
						foreach(ORM::for_table($config["definition"])->find_many() as $item) {
							$data[$item->id] = $item->$config["field"];
						}
						echo HTML::select($name,$label,HTML::options($data,$entry->$field)).HTML::br();
						break;
					}
					
					// File Select
					case "file": {
						$data = array();
						$files = Disk::read_directory("public/".$config["folder"]);
						foreach($files["."] as $file) {
							$data[$file] = $file;
						}
						echo HTML::select($name,$label,HTML::options($data,$entry->$field)).HTML::br();
						break;
					}
				}
			}
			
			// Close Row
			echo HTML::div_close();
		}
		
		// Close Manager
		echo HTML::submit(I18n::_("system.manager.save"));
		echo HTML::form_close();
		echo HTML::div_close();
		
		// End
		exit;
	}
	
	// Perform a Creationt or Update
	public static function on_manager_update() {
		
		// Get Data
		$definition = Request::post("definition");
		$id = Request::post("id","int");
		$data = Request::post($definition,"array");
		$class = $class= ucfirst($definition)."_Definition";
		$entry = ORM::for_table($definition);
		
		// Check for new Entry
		if($id > 0) {
			$entry = $entry->find_one($id);
		} else {
			$entry = $entry->create();
		}
		
		// Append Data
		foreach($data as $field => $value) {
			$entry->$field = $value;
		}
		
		// Validate and Save
		if(self::_validate($entry,$class::$blueprint,$class::$default_config)) {
			$entry->save();
		} else {
			die("Validation failed");
		}
		
		// Dispatch before_update Event
		$entry = $class::before_update($entry);
		
		// Redirect
		Helper::redirect_to_referer();
	}
	
	// Delete an Entry
	public static function on_manager_delete() {

		// Get Data
		$definition = Request::post("definition");
		$class = ucfirst($definition)."_Definition";
		$id = Request::post("id","int");
		$entry = ORM::for_table($definition)->find_one($id);
		
		// Check Entry
		if($entry->id) {
			$entry->delete();
			echo "ok";
		} else {
			echo "Entry not found!";
		}
		
		// End
		exit;
	}
	
	// Validate Data
	private static function _validate($entry,$blueprint,$default_config) {
		
		// Load Validation for each field
		foreach($blueprint as $field => $config) {
			
			// Get Config
			$config = array_merge($default_config,$config);
			
			// Switch Validation Tyoe
			foreach($config["validation"] as $type => $parameter) {
				switch($type) {
					
					// Not Empty
					case "not_empty": if(strlen($entry->$field) < 1) { return false; } break;
					
					// Match Rege
					case "match": if(!preg_match($parameter,$entry->$field)) { return false; } break;
					
				}
			}
		}
		
		// End
		return true;
	}
	
}