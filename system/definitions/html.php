<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Html_Definition extends Definition {
	
	public $blueprint = array(
		"key" => array("as"=>"string","hidden"=>true,"validation"=>array("not_empty"=>null,"match"=>"!^[a-z0-9-_]+$!")),
		"value" => array("as"=>"html","label"=>false)
	);
	
}