<?php
class App_Bootstrap {

  protected $_request;

  public function init(Zend_Controller_Request_Abstract $request) {
    // atribui o request
    $this->_request = $request;

    // lista os metodos
    $methods = get_class_methods($this);

    foreach($methods as $method) {
      if($method != "init") {
        call_user_func(array($this, $method));
      }
    }
  }

}