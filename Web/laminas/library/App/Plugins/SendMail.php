<?php

class App_Plugins_SendMail {

   public static function sendmail($mail_for, $mail_name, $content) {

      $ModelConfig = new Application_Model_DbTable_RtConfig();

      $select = $ModelConfig->fetchRow($ModelConfig->select()->order("IdRtConfig DESC")->limit(1));

      $config = array('auth' => 'login',
                'username' => $select->RtUserEmail,
                'password' => $select->RtPassEmail);

      $your_name = 'Radar de Talentos';
      $your_email = $select->RtUserEmail;
      $send_to_name = $mail_name;
      $send_to_email = $mail_for;


      $transport = new Zend_Mail_Transport_Smtp($select->RtSmtpEmail, $config);


      $mail = new Zend_Mail('utf-8');
      $mail->setFrom($select->RtEmail, $your_name);
      $mail->addTo($send_to_email, $send_to_name);
      $mail->setSubject($content['assunto']);
      $mail->setBodyHtml($content['mensagem']);


      $sent = true;
      try {
         $mail->send($transport);
      } catch (Exception $e) {
         $sent = false;
      }


      return $sent;
   }

}
