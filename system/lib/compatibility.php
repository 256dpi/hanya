<?php
  if(!function_exists('parse_ini_string')){
    function parse_ini_string($str, $process_sections=false){
      $lines  = explode("\n", $str);
      $return = array();
      $inSect = false;
      foreach($lines as $line){
        $line = trim($line);
        if(!$line || $line[0] == "#" || $line[0] == ";") {
          continue;
        }
        if($line[0] == "[" && $endIdx = strpos($line, "]")){
          $inSect = substr($line, 1, $endIdx-1);
          continue;
        }
        if(!strpos($line, '=')) {
          continue;
        }
        $tmp = explode("=", $line, 2);
        if($process_sections && $inSect) {
          $return[$inSect][trim($tmp[0])] = ltrim($tmp[1]);
        } else {
          $return[trim($tmp[0])] = ltrim($tmp[1]);
        }
      }
      return $return;
    }
  }
?>