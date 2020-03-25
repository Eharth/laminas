<?php

class Pampa_View_Helper_Messenger extends Zend_View_Helper_Abstract {
  public $view;

  public function setView(Zend_View_Interface $view) {
    $this->view = $view;
  }

  protected $_messageKeys = array(
          'error_message',
          'info_message',
          'ok_message',
          'warn_message',
  );

  public function messenger() {
    foreach($this->_messageKeys as $messageKey) {
      if($messages = $this->_getMessages($messageKey)) {
        echo $this->_renderMessage($messages,$messageKey);
      }
    }
  }

  protected function _getMessages($messageKey) {
    $result = array();
    $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
    $flashMessenger->setNamespace($messageKey);

    if($flashMessenger->hasMessages()) {
      $result = $flashMessenger->getMessages();
    }

    // check view object
    if(isset($this->view->$messageKey)) {
      array_push($result, $this->view->$messageKey);
    }

    //add any messages from this request
    if ($flashMessenger->hasCurrentMessages()) {
      $result = array_merge($result,
              $flashMessenger->getCurrentMessages()
      );
      //we don’t need to display them twice.
      $flashMessenger->clearCurrentMessages();
    }
    return $result;
  }

  protected function _renderMessage($message, $name) {

    $return = "";

    $class = str_replace("_message", "", $name);

    if(!is_array($message)) {
      $return .= "<div class='msg msg-{$class}'><p>{$message}</p></div>";
    } else {
      foreach($message as $m){
        $return .= "<div class='msg msg-{$class}'><p>{$m}</p></div>";
      }
    }
    return $return;
  }
}