<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Sitemap_Plugin extends Plugin {
	
	// Check for ../sitemap.xml
	public static function before_execution() {
		
		// Get Segments
		$segments = Registry::get("request.segments");
		
		// Check for sitemap.xml
		if($segments[0] == "sitemap.xml") {
			
			// Set Header
			HTTP::content_type("text/xml");
			
			// Echo Header
			echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
			
			// Read Tree
			$tree = Disk::read_directory("tree");
			
			// Genrate XML Entries
		  foreach(str_replace(".html","",self::_get_files_from_tree($tree)) as $url) {
				echo ('<url><loc>'.Url::_($url).'</loc></url>');
			}
			
			// End
			echo('</urlset>');
			exit;
			
		}
	}
	
	// Get Files From Tree Directory
	private static function _get_files_from_tree($tree,$path="") {
		$files = array();
		foreach($tree["."] as $file) {
			if($path=="" && $file == "index.html") {
				$file = "";
			}
			$files[] = $path.$file;
		}
		foreach($tree as $node => $content) {
			if($node != ".") {
				$files = array_merge($files,self::_get_files_from_tree($content,$path.$node."/"));
			}
		}
		return $files;
	}
	
}