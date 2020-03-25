<?php

class App_Plugins_FilePermissions {

   public static function permissions($path, $caminho) {

      $file = fopen(APPLICATION_PATH . $caminho . "/permissions.bat", 'w');
      fwrite($file, "echo y| cacls " . APPLICATION_PATH . $caminho . "\*.* /g Everyone:C Administrator:F");
      fclose($file);
      exec($path . 'permissions.bat');
      unlink(APPLICATION_PATH . $caminho . "/permissions.bat");
   }

}