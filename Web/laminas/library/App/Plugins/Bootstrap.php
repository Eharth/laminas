<?php
class App_Plugins_Bootstrap extends Zend_Controller_Plugin_Abstract {

  public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
    // caminho do arquivo
    $bootstrapPath = APPLICATION_PATH . '/modules/' . $request->getModuleName() . '/Bootstrap.php';

    // classe do arquivo
    $bootstrapClass = ucfirst($request->getModuleName())."Bootstrap";

    if(is_file($bootstrapPath)) {
      // inclui o arquivo
      include $bootstrapPath;

      // valida se existe a classe
      if(class_exists($bootstrapClass)) {
        $b = new $bootstrapClass;
        $b->init($request);
      }
    }
  }

}