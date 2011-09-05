<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class TextDefinition extends Definition {
	
	static $blueprint = array(
		"key" => array("as"=>"string","hidden"=>true,"validation"=>array("not_empty"=>null,"match"=>"!^[a-z0-9-_]+$!")),
		"value" => array("as"=>"text","hidden"=>false,"label"=>false)
	);
	
}