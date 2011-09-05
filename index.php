<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @version 1.0
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

require("system/hanya.php");

Hanya::dynamic_point("database-item","(/<id>)");

Hanya::run(array(
	"db.driver" => "sqlite",
	"db.location" => "sqlite:system/db.sq3",
	"db.user" => "",
	"db.password" => "",
	"i18n.languages" => array("en"=>array("timezone"=>"Europe/Berlin","locale"=>"de_CH")),
	"i18n.default" => "en",
	"auth.users" => array("user"=>"user"),
));

/*

	[Mysql Settings]
	"db.driver" => "mysql"
	"db.location" => "mysql:dbname=hanya;unix_socket=/tmp/mysql.sock",
	"db.user" => "root",
	"db.password" => "toor",
	
	[Overrideable Settings]
	"base.path" => "override",
	"base.url" => "override",
	
	[System Settings]
	"system.automatic_db_setup" => false,
	
*/