<?php

class AdminBootstrap extends App_Bootstrap {

    

    public function _initView() {


        //die (print_r($this->_options));
        $view = new Zend_View();
        $view->headTitle()->setSeparator(' - ')->append('BWT - Lâminas');


        Zend_Layout::startMvc(
                array(
                    'layoutPath' => APPLICATION_PATH . '/modules/default/layouts/scripts/',
                    'layout' => 'layout', // default deve ser o nome do arquivo. Ex: default.phtml
                    'pluginClass' => 'App_Plugins_SetLayout' // é aqui onde acontece a mágica
                )
        );


        $view->headMeta()
                ->appendHttpEquiv('X-UA-Compatible', 'IE=edge,chrome=1')
                ->setName('description', '')
                ->setName('viewport', 'width=device-width');

        $view->headLink()                
                ->appendStylesheet('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css')
                ->appendStylesheet('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css')
                ->appendStylesheet('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css')
                ->appendStylesheet(BASE_URL . 'public/public_admin/stylesheets/screen.css')
                ->appendStylesheet(BASE_URL . 'public/public_admin/stylesheets/jasny-bootstrap.min.css');


        $view->headScript()
                ->appendFile(BASE_URL . 'public/public_admin/scripts/4776dee8.vendor.js', 'text/javascript')
                ->appendFile(BASE_URL . 'public/public_admin/scripts/jasny-bootstrap.min.js', 'text/javascript')
                ->appendFile(BASE_URL . 'public/public_admin/scripts/6b5da0e8.plugins.js', 'text/javascript')
                ->appendFile(BASE_URL . 'public/public_admin/scripts/b6c3df09.main.js', 'text/javascript');
    }

    public function _initMail() {

        $smtpHost = 'smtp.gmail.com';
        $smtpConf = array(
            'auth' => 'login',
            'port' => 587,
            'ssl' => 'tls',
            'username' => 'contato@bwtoperadora.com.br',
            'password' => 'contatobwtmail'
        );

        $tr = new Zend_Mail_Transport_Smtp($smtpHost, $smtpConf);
        Zend_Mail::setDefaultTransport($tr);
    }

}
