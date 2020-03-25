<?php

class App_Plugins_Foomodule extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if ('foomodule' != $request->getModuleName()) {
            // If not in this module, return early
            return;
        }

        // Change layout
        Zend_Layout::getMvcInstance()->setLayout('foomodule');
        
        die(print "teste");
    }
}