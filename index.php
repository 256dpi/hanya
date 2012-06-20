<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

require("system/hanya.php");

Hanya::run(array(
	"db.driver" => "sqlite",
	"db.location" => "sqlite:user/db.sq3",
	"i18n.languages" => array("en"=>array("timezone"=>"Europe/Berlin","locale"=>"de_CH")),
	"i18n.default" => "en",
	"auth.users" => array(
		"admin" => array("pass"=>"admin","privileges"=>array("god"))
	),
	"mail.sender" => "sender@example.com",
	"mail.forms" => array(),
));