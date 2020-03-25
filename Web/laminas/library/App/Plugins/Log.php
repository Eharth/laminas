<?php

class App_Plugins_Log {

   public static function gravar($message = NULL, $ip = false) {
     
      $auth = Zend_Auth::getInstance()->getIdentity();
      $log = new Application_Model_DbTable_Admin_Log();
      $data_log['id_usuario'] = $auth->getUserID();
      $data_log['desc_log'] = $message;
      $data_log['ip_usuario'] = $ip;
      $log->insert($data_log);
   }

}