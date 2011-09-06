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
		$html = HTML::stylesheet(Url::_("public/system/hanya.css"));
		$html .= HTML::stylesheet(Url::_("public/system/jquery.cleditor.css"));
		$html .= HTML::script(Url::_("public/system/jquery.js"));
		$html .= HTML::script(Url::_("public/system/hanya.js"));
		$html .= HTML::script(Url::_("public/system/jquery.cleditor.js"));
		$html .= HTML::script(Url::_("public/system/jquery.treeview.js"));
		return $html;
	}

}