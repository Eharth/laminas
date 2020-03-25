<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuarios
 *
 * @author Allan.Carvalho
 */
class Application_Form_Admin_Usuarios extends Zend_Form {

    //put your code here

    public function init() {
        // Nome da Pessoa

        $input_decorator = new Application_Form_Decorator_Input();
        $input_radio = new Application_Form_Decorator_Radio();
        $input_upload = new Application_Form_Decorator_Upload();
        $submit_decorator = new Application_Form_Decorator_Submit();

        $this->setName('usuarios');
        $this->setAttrib('class', 'form-horizontal');
        $this->setMethod('POST');


        $nome = new Zend_Form_Element_Text('nome_usuario');
        $nome->setRequired(true)->setLabel('Nome');
        $nome->setAttribs(array('type' => 'text', 'placeholder' => 'Insira seu nome', 'class' => 'col-sm-6', 'required' => 'data-error="Preencha o campo de nome" required'));
        $this->addElement($nome);
        $nome->addDecorator($input_decorator);

        $email = new Zend_Form_Element_Text('email_usuario');
        $email->setLabel('E-mail');
        $email->setAttribs(array('type' => 'email', 'placeholder' => 'Insira seu e-mail', 'class' => 'col-sm-6', 'required' => 'data-error="Preencha o campo de email" required'));
        $this->addElement($email);
        $email->addDecorator($input_decorator);

        $operadora = new Zend_Form_Element_Text('operadora_usuario');
        $operadora->setLabel('Agência')
                ->setAttribs(array('type' => 'text', 'placeholder' => 'Insira a agência', 'class' => 'col-sm-6', 'required' => 'data-error="Preencha o campo de Agência" required'))
                ->addDecorator($input_decorator);
        $this->addElement($operadora);


        $senha = new Zend_Form_Element_Password('senha_usuario');
        $senha->setRequired(true)->setLabel('Senha');
        $senha->setAttribs(array('type' => 'password', 'placeholder' => 'Insira sua senha', 'class' => 'col-xs-4'));
        $senha->setRenderPassword('sha1');
        $this->addElement($senha);
        $senha->addDecorator($input_decorator);

        $this->setAttrib('enctype', 'multipart/form-data');

        $file = new Zend_Form_Element('imagem_rodape_usuario', array(
            "type" => "file"
        ));
        $file->setLabel('Logo');

        $file->addDecorator($input_upload);

        $this->addElement($file);

        $site = new Zend_Form_Element_Text('site_usuario');
        $site->setLabel('Site')
                ->setAttribs(array('type' => 'site', 'placeholder' => 'http://www.seusite.com.br', 'class' => 'col-sm-6'))
                ->addDecorator($input_decorator);
        $site->setOptions(
                array(
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    ),
                    'validators' => array(
                        'NotEmpty',
//                        array(
//                            'Callback',
//                            true,
//                            array(
//                                'callback' => function($value) {
//                            return Zend_Uri::check($value);
//                        },
//                                'messages' => array(
//                                    Zend_Validate_Callback::INVALID_VALUE => 'Digite um endereço para o site válido, exp (http://endereco do site.com.br)',
//                                ),
//                            ),
//                        ),
                    ),
                )
        );

        $this->addElement($site);

        $telefone = new Zend_Form_Element_Text('telefone_usuario');
        $telefone->setLabel('Telefone')
                ->setAttribs(array('type' => 'text', 'placeholder' => 'Insira seu telefone', 'class' => 'col-sm-4', 'data-mask' => '(99)9999-9999?9', 'required' => 'data-error="Preencha o campo de telefone" required'))
                ->addDecorator($input_decorator);
        $this->addElement($telefone);

        $endereco = new Zend_Form_Element_Text('endereco_usuario');
        $endereco->setLabel('Endereço')
                ->setAttribs(array('type' => 'textarea', 'placeholder' => 'Insira seu endereço', 'class' => 'col-sm-6'))
                ->addDecorator($input_decorator);
        $this->addElement($endereco);

        $facebook = new Zend_Form_Element_Text('facebook_usuario');
        $facebook->setLabel('Facebook')
                ->setAttribs(array('type' => 'site', 'placeholder' => 'http://www.facebook.com/seufacebook', 'class' => 'col-sm-6'))
                ->addDecorator($input_decorator);
        $facebook->setOptions(
                array(
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    ),
                    'validators' => array(
                        'NotEmpty',
//                        array(
//                            'Callback',
//                            true,
//                            array(
//                                'callback' => function($value) {
//                            return Zend_Uri::check($value);
//                        },
//                                'messages' => array(
//                                    Zend_Validate_Callback::INVALID_VALUE => 'Digite um endereço de facebook válido do seu Facebook, exp (http://endereco do site.com.br)',
//                                ),
//                            ),
//                        ),
                    ),
                )
        );
        $this->addElement($facebook);

        $twitter = new Zend_Form_Element_Text('twitter_usuario');
        $twitter->setLabel('Twitter')
                ->setAttribs(array('type' => 'site', 'placeholder' => 'https://twitter.com/seu twitter', 'class' => 'col-sm-6'))
                ->addDecorator($input_decorator);
        $twitter->setOptions(
                array(
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    ),
                    'validators' => array(
                        'NotEmpty',
//                        array(
//                            'Callback',
//                            true,
//                            array(
//                                'callback' => function($value) {
//                            return Zend_Uri::check($value);
//                        },
//                                'messages' => array(
//                                    Zend_Validate_Callback::INVALID_VALUE => 'Digite um endereço de url válido para o Twitter, exp (http://endereco do site.com.br)',
//                                ),
//                            ),
//                        ),
                    ),
                )
        );
        $this->addElement($twitter);

        $google = new Zend_Form_Element_Text('google_usuario');
        $google->setLabel('Google Plus')
                ->setAttribs(array('type' => 'site', 'placeholder' => 'https://plus.google.com/seu perfil google plus', 'class' => 'col-sm-6'))
                ->addDecorator($input_decorator);
        $google->setOptions(
                array(
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    ),
                    'validators' => array(
                        'NotEmpty',
//                        array(
//                            'Callback',
//                            true,
//                            array(
//                                'callback' => function($value) {
//                            return Zend_Uri::check($value);
//                        },
//                                'messages' => array(
//                                    Zend_Validate_Callback::INVALID_VALUE => 'Digite um endereço de url válido para Google Plus, exp (http://endereco do site.com.br)',
//                                ),
//                            ),
//                        ),
                    ),
                )
        );
        $this->addElement($google);

        $instagram = new Zend_Form_Element_Text('instagram_usuario');
        $instagram->setLabel('Instagram')
                ->setAttribs(array('type' => 'site', 'placeholder' => 'http://instagram.com/seuinstagram', 'class' => 'col-sm-6'))
                ->addDecorator($input_decorator);
        $instagram->setOptions(
                array(
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                    ),
                    'validators' => array(
                        'NotEmpty',
//                        array(
//                            'Callback',
//                            true,
//                            array(
//                                'callback' => function($value) {
//                            return Zend_Uri::check($value);
//                        },
//                                'messages' => array(
//                                    Zend_Validate_Callback::INVALID_VALUE => 'Digite um endereço de url válido para o Instagram, exp (http://endereco do site.com.br)',
//                                ),
//                            ),
//                        ),
                    ),
                )
        );
        $this->addElement($instagram);


        $Model_perfil = new Application_Model_DbTable_Admin_Perfis();
        foreach ($Model_perfil->fetchAll() as $perfis) {
            $p[$perfis->id_perfil] = $perfis->nome_perfil;
        }

        $perfil = new Zend_Form_Element('id_perfil', array(
            'label' => 'Perfil',
            'type' => 'radio',
            'elemName' => 'gender',
            'multiOptions' => $p,
            'decorators' => array($input_radio),
        ));

        $this->addElement($perfil);

        $status = new Zend_Form_Element('status_usuario', array(
            'label' => 'Status',
            'type' => 'radio',
            'elemName' => 'gender',
            'multiOptions' => array('0' => 'Desativado', '1' => 'Ativado'),
            'decorators' => array($input_radio),
        ));
        $status->setValue(1);

        $this->addElement($status);

        // Botão de Envio
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setIgnore(true)
                ->setLabel(' Salvar')
                ->addDecorator($submit_decorator);

        $this->addElement($submit);
        $this->addAttribs(array("data-toggle" => "validator"));


        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'div')),
            'Form'));






        // Configurações do Formulário
    }

}
