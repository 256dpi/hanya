<?php
/**
 * Hanya - A rapid Website Engine
 *
 * @author Joël Gähwiler <joel.gaehwiler@bluewin.ch>
 * @copyright (c) 2013 Joël Gähwiler 
 * @package Hanya
 **/

class Captcha_Tag {
  
  public static function call($attributes) {
    return Recaptcha::html();
  }
  
}