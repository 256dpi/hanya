<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Image_Tag {
	
	// Store Data
	private static $_images = array();
	
	// Tag Call
	public static function call($attributes) {
		
		// Fetch Data from DB once
		if(!self::$_images) {
			foreach(ORM::for_table("image")->find_many() as $image) {
				self::$_images[$image->key] = $image;
			}
		}
		
		// Get Key
		$key = $attributes[0];
		
		// Check for existense
		if(!array_key_exists($key,self::$_images)) {
			
			// Get ORM
			$image = ORM::for_table("image")->create();
			
			// Set
			$image->key = $key;
			$image->alt = "";
			$image->path = ""; //../assets/system/images/dummy.jpg
			
			// Save
			$image->save();
			
			// Store new Data
			self::$_images[$key] = $image;
			
		}

		$img = self::$_images[$key];
		$src = $img->path==""?"assets/system/images/dummy.jpg":"uploads/images/".$img->path;
		$tag = '<img src="'.$src.'" alt="'.$img->alt.'" />';
		
		// Check for Edit mode
		if(Memory::get("edit_page")) {
			
			// Get ORM
			//$image = ORM::for_table("image")->where("key",$key)->find_one();
			
			// Return HTML
			return HTML::div(null,"hanya-editable",$tag,array(
				"data-id"=>$image->id,
				"data-definition"=>"image",
				"data-is-orderable"=>"false",
				"data-is-destroyable"=>"false"
			));
			
		} else {
			
			// Return Data
			return $tag;
			
		}
	}
	
}