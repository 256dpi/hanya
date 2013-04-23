<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright Joël Gähwiler 
 * @package Hanya
 **/

class HTML {
	
	/* HEAD */
	
	// Include Javascript
	public static function script($url,$attributes=array()) {
		$attributes = array_merge(array("src"=>$url,"type"=>"text/javascript"),$attributes);
		return self::tag("script","both",$attributes);
	}
	
	// Include a Stylesheet
	public static function stylesheet($url,$media="screen",$attributes=array()) {
		$attributes = array_merge(array("href"=>$url,"rel"=>"stylesheet","type"=>"text/css","media"=>$media),$attributes);
		return self::tag("link","semi",$attributes);
	}
	
	/* FORM */
	
	// Open a Form
	public static function form_open($action,$method="post",$attributes=array()) {
		$attributes = array_merge(array("action"=>$action,"method"=>$method),$attributes);
		return self::tag("form","open",$attributes);
	}
	
	// Close Form
	public static function form_close() {
		return self::tag("form","close");
	}
	
	/* CONTROLS */
	
	// Hidden Input
	public static function hidden($name,$value=null,$attributes=array()) {
		$attributes = array_merge(array("type"=>"hidden","name"=>$name,"value"=>$value),$attributes);
		return self::tag("input","semi",$attributes);
	}
	
	// Text Input
	public static function text($name,$label=null,$value=null,$attributes=array()) {
		$attributes = array_merge(array("type"=>"text","name"=>$name,"value"=>$value),$attributes);
		$ret = self::tag("input","semi",$attributes);
		if($label) {
			$ret = self::label($name,$label).$ret;
		}
		return $ret;
	}
	
	// Text Password
	public static function password($name,$label=null,$value=null,$attributes=array()) {
		$attributes = array_merge(array("type"=>"password","name"=>$name,"value"=>$value),$attributes);
		$ret = self::tag("input","semi",$attributes);
		if($label) {
			$ret = self::label($name,$label).$ret;
		}
		return $ret;
	}
	
	// Textarara
	public static function textarea($name,$label=null,$value=null,$attributes=array()) {
		$attributes = array_merge(array("name"=>$name),$attributes);
		$ret = self::tag("textarea","both",$attributes,$value);
		if($label) {
			return self::label($name,$label).$ret;
		}
		return $ret;
	}
	
	// Checkbox
	public static function checkbox($name,$label=null,$value=null,$attributes=array()) {
		$attributes = array_merge(array("type"=>"checkbox","name"=>$name,"value"=>$value),$attributes);
		if($value) {
			$attributes = array_merge(array("checked"=>"checked"),$attributes);
		}
		$ret = self::tag("input","semi",$attributes);
		if($label) {
			$ret = self::label($name,$label).$ret;
		}
		return $ret;
	}
	
	// Radio Button
	public static function radio($name,$value=null,$label=null,$attributes=array()) {
		$attributes = array_merge(array("type"=>"radio","name"=>$name,"value"=>$value),$attributes);
		$ret = self::tag("input","semi",$attributes);
		if($label) {
			$ret = self::label($name,$ret.$label);
		}
		return $ret;
	}
	
	// Submit Button
	public static function submit($text,$attributes=array()) {
		$attributes = array_merge(array("type"=>"submit","value"=>$text),$attributes);
		return self::tag("input","semi",$attributes);
	}
	
	// Label
	public static function label($for,$text,$attributes=array()) {
		$attributes = array_merge(array("for"=>$for),$attributes);
		return self::tag("label","both",$attributes,$text);
	}
	
	// Button
	public static function button($text,$onclick,$attributes=array()) {
		$attributes = array_merge(array("onclick"=>$onclick),$attributes);
		return self::tag("button","both",$attributes,$text);
	}
	
	// Select
	public static function select($name,$label=null,$options=null,$attributes=array()) {
		$attributes = array_merge(array("name"=>$name),$attributes);
		$ret = self::tag("select","both",$attributes,$options);
		if($label) {
			return self::label($name,$label).$ret;
		}
		return $ret;
	}
	
	// Options for Select
	public static function options($collection,$selected=null) {
		$html = "";
		foreach($collection as $id => $value) {
			$html .= self::tag("option","both",($id==$selected)?array("value"=>$id,"selected"=>"selected"):array("value"=>$id),$value);
		}
		return $html;
	}
	
	// File Input
	public static function file($name,$label=null,$attributes=array()) {
		$attributes = array_merge(array("type"=>"file","name"=>$name),$attributes);
		$ret = self::tag("input","semi",$attributes);
		if($label) {
			$ret = self::label($name,$label).$ret;
		}
		return $ret;
	}
	
	/* TEXT */
	
	// Header
	public static function header($i,$text,$attributes=array()) {
		return self::tag("h".$i,"both",$attributes,$text);
	}

	// Paragraph
	public static function paragraph($text,$attributes=array()) {
		return self::tag("p","both",$attributes,$text);
	}

	// Linebreak
	public static function br() {
		return self::tag("br","semi");
	}
	
	// Span
	public static function span($text,$attributes=array()) {
		return self::tag("span","both",$attributes,$text);
	}
	
	// Anchor
	public static function anchor($url,$text,$attributes=array()) {
		$attributes = array_merge(array("href"=>$url),$attributes);
		return self::tag("a","both",$attributes,$text);
	}
	
	// Image
	public static function image($src,$attributes=array()) {
		$attributes = array_merge(array("src"=>$src),$attributes);
		return self::tag("img","semi",$attributes);
	}
	
	/* LAYOUT */
	
	// Open Div
	public static function div_open($id=null,$class=null,$attributes=array()) {
		$attributes = array_merge(array("id"=>$id,"class"=>$class),$attributes);
		return self::tag("div","open",$attributes);
	}
	
	// Close Div
	public static function div_close() {
		return self::tag("div","close");
	}
	
	// Wrap with a Div
	public static function div($id=null,$class=null,$inner=null,$attributes=array()) {
		$attributes = array_merge(array("id"=>$id,"class"=>$class),$attributes);
		return self::tag("div","both",$attributes,$inner);
	}
	
	/* SYSTEM FUNCTIONS */
	
	// Generate Tag
	public static function tag($name,$type,$attributes=array(),$inner=null) {
		switch($type) {
			case "open": return '<'.$name.' '.self::_attributes($attributes).'>'; break;
			case "close": return '</'.$name.'>'; break;
			case "both": return '<'.$name.' '.self::_attributes($attributes).'>'.$inner.'</'.$name.'>'; break;
			case "semi": return '<'.$name.' '.self::_attributes($attributes).'/>'; break;
		}
	}
	
	// Process Attributes
	private static function _attributes($attributes) {
		$ret = "";
		foreach($attributes as $key => $value) {
			if($value !== null) {
				$ret .= $key.'="'.$value.'" ';
			}
		}
		return $ret;
	}

}