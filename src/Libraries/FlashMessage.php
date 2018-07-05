<?php

namespace App\Libraries;

use \SlimSession\Helper;

class FlashMessage
{
   public static function set($messages){
      $session = new \SlimSession\Helper;
      $session->set(array('flash_message' => $messages));
   }   
   public static function get(){
      $session = new \SlimSession\Helper;
      return $session->cache['flash_message'];
   }   
   
}
