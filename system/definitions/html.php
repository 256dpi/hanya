<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 0.2
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Html_Definition extends Definition {
	
	static $blueprint = array(
		"key" => array("as"=>"string","hidden"=>true,"validation"=>array("not_empty"=>null,"match"=>"!^[a-z0-9-_]+$!")),
		"value" => array("as"=>"html","hidden"=>false,"label"=>false)
	);
	
}