<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Helper {
	
	/* URL HANDLING */
	
	// Get Contents of URL
	public static function read_url($url) {
		$curl = curl_init();
	  $timeout = 5;
	  curl_setopt($curl,CURLOPT_URL,$url);
	  curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	  curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,$timeout);
		curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0); 
	  $data = curl_exec($curl);
	  
		if(!$data) {
			die("Helper::read_url: Curl process failed to load ".$url." with".curl_error($curl));
		}
		
		curl_close($curl);
		return $data;
	}
	
	/* LOCATION HANDLING */
	
	public static function redirect_to_referer() {
		if(Registry::get("request.referer") != "") {
			HTTP::location(Registry::get("request.referer"));
		} else {
			HTTP::location(Registry::get("base.url"));
		}
		exit;
	}
	
	public static function url($add="") {
		return Registry::get("base.url").$add;
	}
	
	public static function redirect($add="") {
		HTTP::location(self::url($add));
		exit;
	}
	
	/* SPECIAL FUNCTIONS */
	
	// Wrap HTML as Editable
	public static function wrap_as_editable($html,$definition,$id) {
		return HTML::div(null,"hanya-editable",$html,array("data-id"=>$id,"data-definition"=>$definition));
	}
	
	// Get a Tree File from Segments
	public static function tree_file_from_segments($segments) {
		if($segments[0] != "") {
			return "tree/".join($segments,"/").".html";
		} else {
			return "tree/index.html";
		}
	}

}