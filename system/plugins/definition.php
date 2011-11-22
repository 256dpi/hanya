<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Definition_Plugin extends Plugin {
	
	// Send Back HTML to Display Form
	public static function on_definition_manager() {
		
		// Check Admin
		self::_check_admin();
		
		// Get Data
		$definition = Request::post("definition");
		$class = ucfirst($definition)."_Definition";
		$id = Request::post("id","int");
		$entry = ORM::for_table($definition);
		
		// Load Entry if isnt is a update
		if($id > 0) {
			$entry = $entry->find_one($id);
		} else {
			// Call Definitions Constructor
			$entry = $class::create($entry->create(),Request::post("argument","string"));
		}
		
		// Open Manager
		echo HTML::div_open(null,"hanya-manager-head");
		echo HTML::span(I18n::_("definition.".$definition.".edit_entry"));
		echo HTML::anchor("javascript:HanyaWindow.remove()",I18n::_("system.manager.close"),array("class"=>"hanya-manager-head-close"));
		echo HTML::div_close();
		
		// Open Body
		echo HTML::div_open(null,"hanya-manager-body");
		echo HTML::form_open(Registry::get("request.referer")."?command=definition_update","post",array("enctype"=>"multipart/form-data"));
		echo HTML::hidden("definition",$definition,array("id"=>"hanya-input-definition"));
		echo HTML::hidden("id",$id,array("id"=>"hanya-input-id"));
		
		// Print Form Elements
		foreach($class::$blueprint as $field => $config) {
			
			// Merge Config with defaults
			$config = array_merge($class::$default_config,$config);
			
			// Get Field Name
			$name = $definition."[".$field."]";
			
			// Check for Visibility
			if(!$config["hidden"]) {
				
				// Open Row
				echo HTML::div_open(null,"hanya-manager-body-row hanya-row-".$config["as"]);
				
				// Get Label and Name
				$label = ($config["label"])?I18n::_("definition.".$definition.".field_".$field):null;
				
				// Switch Field Type
				switch($config["as"]) {
					
					// Boolean
					case "boolean" : {
						echo HTML::label($name,$label);
						echo HTML::div_open(null,"radiogroup");
						echo HTML::radio($name,1,I18n::_("definition.".$definition.".field_".$field."_true"),($entry->$field==1)?array("checked"=>"checked"):array());
						echo HTML::radio($name,0,I18n::_("definition.".$definition.".field_".$field."_false"),($entry->$field==0)?array("checked"=>"checked"):array());
						echo HTML::div_close();
						break;
					}
					
					// Text Inputs
					case "number":
					case "string": echo HTML::text($name,$label,$entry->$field).HTML::br(); break;
					
					// Textareas
					case "html": echo HTML::textarea($name,$label,$entry->$field,array("class"=>"hanya-editor-html")).HTML::br(); break;
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
						if($config["blank"]) {
							$data = array(""=>"---");
						}
						$content = Disk::read_directory("uploads/".$config["folder"]);
						$files = Disk::get_all_files($content);
						$directories = array("/"=>"/");
						foreach(Disk::get_all_directories($content) as $dir) {
							$directories["/".$dir] = "/".$dir;
						}
						foreach($files as $file) {
							$data[$file] = $file;
						}
						if($config["upload"]) {
							$upload = HTML::br().I18n::_("system.definition.upload_file").HTML::file($definition."[".$field."_upload]");
							$upload .= I18n::_("system.definition.upload_file_to").HTML::select($definition."[".$field."_upload_dir]",null,HTML::options($directories));
						} else {
							$upload = "";
						}
						echo HTML::label($name,$label).I18n::_("system.definition.select_file").HTML::select($name,null,HTML::options($data,$entry->$field)).$upload.HTML::br();
						break;
					}
				}
			} else {
				
				// Open Row
				echo HTML::div_open(null,"hanya-manager-hidden-row");
				
				// Render Hidden Field
				echo HTML::hidden($name,$entry->$field);
			}
			
			// Close Row
			echo HTML::div_close();
		}
		
		// Close Manager
		echo HTML::submit(I18n::_("system.definition.save"));
		echo HTML::form_close();
		echo HTML::div_close();
		
		// End
		exit;
	}
	
	// Perform a Creation or Update
	public static function on_definition_update() {
		
		// Check Admin
		self::_check_admin();
		
		// Get Data
		$definition = Request::post("definition");
		$id = Request::post("id","int");
		$data = Request::post($definition,"array");
		$class = $class= ucfirst($definition)."_Definition";
		$entry = ORM::for_table($definition);
		
		// Check for new Entry
		if($id > 0) {
			$is_new = false;
			$entry = $entry->find_one($id);
		} else {
			$is_new = true;
			$entry = $entry->create();
		}
		
		// Append Data
		foreach($data as $field => $value) {
			if(array_key_exists($field,$class::$blueprint)) {
				$entry->$field = stripslashes($value);
			}
		}
		
		// Check For Special Fields
		foreach($class::$blueprint as $field => $config) {
			switch($config["as"]) {
				case "file" : {
					$target_dir = Registry::get("system.path")."uploads/".$config["folder"].$data[$field."_upload_dir"];
					if($config["upload"] && $_FILES[$definition]["size"][$field."_upload"] > 0 && Disk::has_directory($target_dir)) {
						$filename = $_FILES[$definition]["name"][$field."_upload"];
						$tmpfile = $_FILES[$definition]["tmp_name"][$field."_upload"];
						$newfile = $target_dir."/".$filename;
						Disk::copy($tmpfile,$newfile);
						$entry->$field = $filename;
						break;
					}
					unset($data[$field."_upload"]);
				}
			}
		}
		
		// Do Ordering
		if($class::$orderable && $is_new) {
			$last_entry = ORM::for_table($definition)->select("ordering")->order_by_desc("ordering");
			foreach($class::$groups as $group) {
				if($entry->$group) {
					$last_entry = $last_entry->where($group,$entry->$group);
				}
			}
			$last_entry = $last_entry->limit(1)->find_one();
			if($last_entry) {
				$entry->ordering = $last_entry->ordering+1;
			} else {
				$entry->ordering = 1;
			}
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
		Url::redirect_to_referer();
	}
	
	// Delete an Entry
	public static function on_definition_remove() {
		
		// Check Admin
		self::_check_admin();

		// Get Data
		$definition = Request::post("definition");
		$class = ucfirst($definition)."_Definition";
		$id = Request::post("id","int");
		$entry = ORM::for_table($definition)->find_one($id);
		
		// Check Ordering
		if($class::$orderable) {
			
			// Get Affected Rows
			$rows = ORM::for_table($definition)->where_gt("ordering",$entry->ordering);
			foreach($class::$groups as $group) {
				if($entry->$group) {
					$rows = $rows->where($group,$entry->$group);
				}
			}
			
			// Order Up
			foreach($rows->find_many() as $row) {
				$row->ordering--;
				$row->save();
			}
			
		}
		
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
	
	// Delete an Entry
	public static function on_definition_orderup() {
		
		// Check Admin
		self::_check_admin();

		// Get Data
		$definition = Request::post("definition");
		$class = ucfirst($definition)."_Definition";
		$id = Request::post("id","int");
		$entry = ORM::for_table($definition)->find_one($id);
		
		// Check Entry
		if($entry && $class::$orderable) {
			if($entry->ordering > 1) {
				// Order Down Element on Position
				$upper = ORM::for_table($definition)->where("ordering",$entry->ordering-1);
				foreach($class::$groups as $group) {
					if($entry->$group) {
						$upper = $upper->where($group,$entry->$group);
					}
				}
				$upper = $upper->find_one();
				$upper->ordering = $entry->ordering;
				$upper->save();
				// Order Up Element
				$entry->ordering = $entry->ordering-1;
				$entry->save();
			}
			echo "ok";
		} else {
			echo "Entry not found!";
		}
		
		// End
		exit;
	}
	
	// Delete an Entry
	public static function on_definition_orderdown() {
		
		// Check Admin
		self::_check_admin();

		// Get Data
		$definition = Request::post("definition");
		$class = ucfirst($definition)."_Definition";
		$id = Request::post("id","int");
		$entry = ORM::for_table($definition)->find_one($id);
		
		// Check Entry
		if($entry && $class::$orderable) {
			if($entry->ordering < ORM::for_table($definition)->select("ordering")->order_by_desc("ordering")->find_one()->ordering) {
				// Order Down Element on Position
				$downer = ORM::for_table($definition)->where("ordering",$entry->ordering+1);
				foreach($class::$groups as $group) {
					if($entry->$group) {
						$downer = $downer->where($group,$entry->$group);
					}
				}
				$downer = $downer->find_one();
				$downer->ordering = $entry->ordering;
				$downer->save();
				// Order Up Element
				$entry->ordering = $entry->ordering+1;
				$entry->save();
			}
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