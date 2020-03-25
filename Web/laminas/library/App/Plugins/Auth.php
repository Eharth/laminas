<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author imam
 */
class App_Plugins_Auth extends Zend_Controller_Plugin_Abstract {

   /**
    * @var Zend_Auth
    */
   protected $_auth = null;

   /**
    * @var Zend_Acl
    */
   protected $_acl = null;

   /**
    * @var array
    */
   protected $_notLoggedRoute = array(
       'module' => 'admin',
       'controller' => 'login',
       'action' => 'index'
   );

   protected $_clienteNotLoggedRout = array(
       'module' => 'admin',
       'controller' => 'login',
       'action' => 'index'
   );

   /**
    * @var array
    */
   protected $_forbiddenRoute = array(
       'module' => 'admin',
       'controller' => 'error',
       'action' => 'forbidden'
   );

   public function __construct() {
      $this->_auth = Zend_Auth::getInstance();
      $this->_acl = Zend_Registry::get('acl');
   }

   public function preDispatch(Zend_Controller_Request_Abstract $request) {
      $controller = "";
      $action = "";
      $module = "";
      if (($request->getModuleName() != 'default') && ($request->getControllerName() != 'login')) {
     // if (($request->getControllerName() != 'login')) {
         if (!$this->_auth->hasIdentity()) {
            $controller = $this->_notLoggedRoute['controller'];
            $action = $this->_notLoggedRoute['action'];
            $module = $this->_notLoggedRoute['module'];
         } elseif (!$this->_isAuthorized($request->getControllerName(), $request->getActionName())) {
            $controller = $this->_forbiddenRoute['controller'];
            $action = $this->_forbiddenRoute['action'];
            $module = $this->_forbiddenRoute['module'];
         } else {
            $controller = $request->getControllerName();
            $action = $request->getActionName();
            $module = $request->getModuleName();
         }
         $request->setControllerName($controller);
         $request->setActionName($action);
         $request->setModuleName($module);
      } 
   }

   protected function _isAuthorized($controller, $action) {
      $this->_acl = Zend_Registry::get('acl');
      $user = $this->_auth->getIdentity();
      if (!$this->_acl->has($controller) || !$this->_acl->isAllowed($user, $controller, $action))
         return false;
      return true;
   }

}

?>
