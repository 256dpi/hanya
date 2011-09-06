<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2011 Joël Gähwiler 
 * @package Hanya
 **/

class Benchmark_Tag {
	
	public static function call($attributes) {
		$time = (microtime(true)-HANYA_SCRIPT_START)*1000;
		return "Execution Time: ".round($time)."ms, Memory Peak: ".round(memory_get_peak_usage()/1024)."KB, Filetime: ".strftime("%x %X",Registry::get("site.newest_file")).", Generated: ".strftime("%x, %X",time());
	}
	
}