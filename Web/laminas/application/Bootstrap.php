<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {


    public function _initAppAutoload() {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => dirname(__FILE__)
        ));
        
        return $autoloader;
    }

    public function _initAcl() {
        $aclSetup = new App_Acl_Setup();
    }

    public function _initController() {
        $controller = $this->bootstrap('frontController');
        return $controller;
    }

    protected function _initSetupBaseUrl() {
        $this->bootstrap('frontcontroller');
        $controller = Zend_Controller_Front::getInstance();
        $controller->setBaseUrl('/laminas'); //the subfolder your app is in
    }

    protected function _initRouter() {
        $route = new App_Plugins_Route();
        
        $route->createRoute('cadastro/', 'cadastro', array(
            'module' => 'default',
            'controller' => 'index',
            'action' => 'cadastro'
        ));      
    }

}
