<?php

class App_Plugins_Permission {

   public static function action($permission) {
      
      $idUsers_admin = Zend_Auth::getInstance()->getIdentity()->idUsers_admin;
      
      $modelProfile = new Application_Model_DbTable_Usuarios();
      $permission_value = $modelProfile->getPermissions($idUsers_admin, $permission);
      
      if(empty($permission_value)):
         $permission_return = 1;
         return $permission_return;
      endif;
     
      
      //die(print key($permission). $permission[key($permission)]);
      
   }
}