<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Setup
 *
 * @author imam
 */
class App_Acl_Setup {

   /**
    * @var Zend_Acl
    */
   protected $_acl;

   public function __construct() {
      $this->_acl = new Zend_Acl();
      $this->_initialize();
   }

   protected function _initialize() {
      $this->_setupRoles();
      $this->_setupResources();
      $this->_setupPrivileges();
      $this->_saveAcl();
   }

   protected function _setupRoles() {
      $this->_acl->addrole(new Zend_Acl_Role('adm'));
      $this->_acl->addrole(new Zend_Acl_Role('opr'));
      $this->_acl->addrole(new Zend_Acl_Role('agt'));
   }

   protected function _setupResources() {
      $this->_acl->addResource(new Zend_Acl_Resource('login'));
      $this->_acl->addResource(new Zend_Acl_Resource('error'));
      $this->_acl->addResource(new Zend_Acl_Resource('administradores'));
      $this->_acl->addResource(new Zend_Acl_Resource('templates'));
      $this->_acl->addResource(new Zend_Acl_Resource('perfis'));
      $this->_acl->addResource(new Zend_Acl_Resource('categorias'));
      $this->_acl->addResource(new Zend_Acl_Resource('index'));
   }

   protected function _setupPrivileges() {

      $this->_acl
              ->allow('adm', array("login", "error", "index", "administradores", "perfis", "templates", "categorias"))
              ->allow('opr', array("login", "error", "index"))
              ->allow('opr', 'templates', array('index'))
              ->allow('agt', array("login", "error", "index"))
              ->allow('agt', 'templates', array('index'));
   }

   protected function _saveAcl() {
      $registry = Zend_Registry::getInstance();
      $registry->set('acl', $this->_acl);
   }

}

?>
