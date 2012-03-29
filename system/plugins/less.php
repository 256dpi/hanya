<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler
 * @package Hanya
 **/

class Less_Plugin extends Plugin {
	
	// Perform a Update of the System
	public static function on_less() {
	  
	  // Get Param with Protection
	  $file = str_replace("..","",Request::get("file"));
	  
	  // Check Existence
	  if(!Disk::has_file($file)) {
	    header("HTTP/1.0 404 Not Found");
	    echo("<h1>404 - Not Found</h1>");
	    exit();
	  }
	  
	  // Get File Time
	  $last_mod = filemtime($file);
	  
	  header("Content-Type: text/css");
	  header("Cache-Control: max-age=".(1*3600*24));
    header("Expires: ".gmdate("D, d M Y H:i:s",time()+1*3600*24));
	  header("Last-Modified: ".date("D, d M Y H:i:s",$last_mod));
	  
	  // Check time
	  if(Registry::get("request.if_modified_since") && Registry::get("request.if_modified_since") >= $last_mod) {
	    header("HTTP/1.1 304 Not Modified");
	  } else {
	    $less = new Less();
	    echo($less->parse(Disk::read_file($file)));
	  }
	  
	  // Exit
		exit();
	}
	
}