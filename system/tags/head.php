<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Head_Tag {
	
	public static function call($attributes) {
		$html = HTML::stylesheet(Helper::url("public/system/hanya.css"));
		$html .= HTML::script(Helper::url("public/system/jquery.js"));
		$html .= HTML::script(Helper::url("public/system/hanya.js"));
		return $html;
	}

}