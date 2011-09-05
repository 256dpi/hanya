<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class HTML {
	
	/* HEAD */
	
	// Include Javascript
	public static function script($url,$attributes=array()) {
		$attributes = array_merge(array("src"=>$url,"type"=>"text/javascript"),$attributes);
		return self::_tag("script","both",$attributes);
	}
	
	// Include a Stylesheet
	public static function stylesheet($url,$mode="screen",$attributes=array()) {
		$attributes = array_merge(array("href"=>$url,"rel"=>"stylesheet","type"=>"text/css","mode"=>$mode),$attributes);
		return self::_tag("link","semi",$attributes);
	}
	
	/* FORM */
	
	// Open a Form
	public static function form_open($action,$method="post",$attributes=array()) {
		$attributes = array_merge(array("action"=>$action,"method"=>$method),$attributes);
		return self::_tag("form","open",$attributes);
	}
	
	// Close Form
	public static function form_close() {
		return self::_tag("form","close");
	}
	
	/* CONTROLS */
	
	// Hidden Input
	public static function hidden($name,$value=null,$attributes=array()) {
		$attributes = array_merge(array("type"=>"hidden","name"=>$name,"value"=>$value),$attributes);
		return self::_tag("input","semi",$attributes);
	}
	
	// Text Input
	public static function text($name,$label=null,$value=null,$attributes=array()) {
		$attributes = array_merge(array("type"=>"text","name"=>$name,"value"=>$value),$attributes);
		$ret = self::_tag("input","semi",$attributes);
		if($label) {
			$ret = self::label($name,$label).$ret;
		}
		return $ret;
	}
	
	// Textarara
	public static function textarea($name,$label=null,$value=null,$attributes=array()) {
		$attributes = array_merge(array("name"=>$name),$attributes);
		$ret = self::_tag("textarea","both",$attributes,$value);
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
		$ret = self::_tag("input","semi",$attributes);
		if($label) {
			$ret = self::label($name,$label).$ret;
		}
		return $ret;
	}
	
	// Radio Button
	public static function radio($name,$value=null,$attributes=array()) {
		$attributes = array_merge(array("type"=>"radio","name"=>$name,"value"=>$value),$attributes);
		$ret = self::_tag("input","semi",$attributes);
		if($label) {
			$ret = self::label($name,$label).$ret;
		}
		return $ret;
	}
	
	// Submit Button
	public static function submit($text,$attributes=array()) {
		$attributes = array_merge(array("type"=>"submit","value"=>$text),$attributes);
		return self::_tag("input","semi",$attributes);
	}
	
	// Label
	public static function label($for,$text,$attributes=array()) {
		$attributes = array_merge(array("for"=>$for),$attributes);
		return self::_tag("label","both",$attributes,$text);
	}
	
	// Button
	public static function button($text,$onclick,$attributes=array()) {
		$attributes = array_merge(array("onclick"=>$onclick),$attributes);
		return self::_tag("button","both",$attributes,$text);
	}
	
	// Select
	public static function select($name,$label=null,$options=null,$attributes=array()) {
		$attributes = array_merge(array("name"=>$name),$attributes);
		$ret = self::_tag("select","both",$attributes,$options);
		if($label) {
			return self::label($name,$label).$ret;
		}
		return $ret;
	}
	
	// Options for Select
	public static function options($collection,$selected=null) {
		$html = "";
		foreach($collection as $id => $value) {
			$html .= self::_tag("option","both",($id==$selected)?array("value"=>$id,"selected"=>"selected"):array("value"=>$id),$value);
		}
		return $html;
	}
	
	/* TEXT */
	
	// Header
	public static function header($i,$text,$attributes=array()) {
		return self::_tag("h".$i,"both",$attributes,$text);
	}

	// Paragraph
	public static function paragraph($text,$attributes=array()) {
		return self::_tag("p","both",$attributes,$text);
	}

	// Linebreak
	public static function br() {
		return self::_tag("br","semi");
	}
	
	// Span
	public static function span($text,$attributes=array()) {
		return self::_tag("span","both",$attributes,$text);
	}
	
	// Anchor
	public static function anchor($url,$text,$attributes=array()) {
		$attributes = array_merge(array("href"=>$url),$attributes);
		return self::_tag("a","both",$attributes,$text);
	}
	
	/* LAYOUT */
	
	// Open Div
	public static function div_open($id=null,$class=null,$attributes=array()) {
		$attributes = array_merge(array("id"=>$id,"class"=>$class),$attributes);
		return self::_tag("div","open",$attributes);
	}
	
	// Close Div
	public static function div_close() {
		return self::_tag("div","close");
	}
	
	// Wrap with a Div
	public static function div($id=null,$class=null,$inner=null,$attributes=array()) {
		$attributes = array_merge(array("id"=>$id,"class"=>$class),$attributes);
		return self::_tag("div","both",$attributes,$inner);
	}
	
	/* SYSTEM FUNCTIONS */
	
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
	
	// Generate Tag
	private static function _tag($name,$type,$attributes=array(),$inner=null) {
		switch($type) {
			case "open": return '<'.$name.' '.self::_attributes($attributes).'>'; break;
			case "close": return '</'.$name.'>'; break;
			case "both": return '<'.$name.' '.self::_attributes($attributes).'>'.$inner.'</'.$name.'>'; break;
			case "semi": return '<'.$name.' '.self::_attributes($attributes).'/>'; break;
		}
	}		

}