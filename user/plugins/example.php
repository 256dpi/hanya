<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Example_Plugin extends Plugin {
	
	// View Login Form
	public static function on_example() {
		
		// Render View
		echo "Example_Plugin";
		exit;
	}
	
}