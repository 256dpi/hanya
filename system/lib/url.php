<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class URL {
	
	// Magic URL Function
	public static function _($add="") {
		return Registry::get("base.url").$add;
	}
	
	// Get Contents of URL
	public static function load($url) {
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
			die("Url::load: Curl process failed to load ".$url." with".curl_error($curl));
		}

		curl_close($curl);
		return $data;
	}

	// Redirect to Referer
	public static function redirect_to_referer() {
		if(Registry::get("request.referer") != "") {
			HTTP::location(Registry::get("request.referer"));
		} else {
			HTTP::location(Registry::get("base.url"));
		}
		exit;
	}

	// Normal Redirect
	public static function redirect($add="") {
		HTTP::location(self::url($add));
		exit;
	}
	
}