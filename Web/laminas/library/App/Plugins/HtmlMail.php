<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HtmlMail
 *
 * @author imam
 */
class App_Plugins_HtmlMail extends Zend_Mail {

   static $fromName = "BWT - Lâminas";
   static $fromEmail = "contato@bwtoperadora.com.br ";
   static $_defaultView;
   protected $_view;

   protected static function getDefaultView() {
      if (self::$_defaultView == null) {
         self::$_defaultView = new Zend_View();
         self::$_defaultView->setScriptPath(APPLICATION_PATH . '/modules/admin/views/scripts/mails');
      }
      return self::$_defaultView;
   }

   public function sendHtmlTemplate($template, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE) {

      $html = $this->_view->render($template);
      $this->setBodyHtml($html, $this->getCharset(), $encoding);
      $this->send();
   }

   public function setViewParam($property, $value){
      $this->_view->__set($property, $value);
      return $this;
   }

   public function __construct($charset = 'utf-8') {
      parent::__construct($charset);
      $this->setFrom(self::$fromEmail, self::$fromName);
      $this->_view = self::getDefaultView();
   }

}

?>
