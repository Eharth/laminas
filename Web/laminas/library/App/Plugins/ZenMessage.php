<?php

class App_Plugins_ZenMessage {

   public static function createMessage($for, $type, $message) {
      $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
      $flashMessenger->setNamespace($for)->addMessage(array($type => $message));
   }

   public static function viewMessage($for) {
      if ($this->_helper->FlashMessenger->setNamespace($for)->hasMessages()):
         return $this->_helper->FlashMessenger->getMessages();
      endif;
   }

}