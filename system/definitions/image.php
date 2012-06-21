<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Image_Definition extends Definition {
  
  public $orderable = false;
	
	public $blueprint = array(
		"key" => array("as"=>"string","hidden"=>true,"validation"=>array("not_empty"=>null,"match"=>"!^[a-z0-9-_]+$!")),
		"alt" => array("as"=>"string"),
		"path" => array("as"=>"file","folder"=>"images","blank"=>false,"upload"=>true)
	);
}