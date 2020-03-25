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
class Application_Form_Admin_Perfis extends Zend_Form {

    //put your code here

    public function init() {
        // Nome da Pessoa

        $input_decorator = new Application_Form_Decorator_Input();
        $input_radio = new Application_Form_Decorator_Radio();
        $submit_decorator = new Application_Form_Decorator_Submit();

        $this->setName('perfis');
        $this->setAttrib('class', 'form-horizontal');
        $this->setMethod('POST');


        $nome = new Zend_Form_Element_Text('nome_perfil');
        $nome->setRequired(true)->setLabel('Nome');
        $nome->setAttribs(array('type' => 'text' ,'placeholder' => 'Insira o nome do perfil', 'class' => 'col-sm-10'));
        $this->addElement($nome);
        $nome->addDecorator($input_decorator);

        $sigla = new Zend_Form_Element_Text('sigla_perfil');
        $sigla->setRequired(true)->setLabel('Sigla')
                ->setAttribs(array('type' => 'text', 'placeholder' => 'Insira uma sigla para o perfil', 'class' => 'col-sm-3'))
                ->addDecorator($input_decorator);
        $this->addElement($sigla);


        
        $status = new Zend_Form_Element('status_perfil', array(
            'label' => 'Status',
            'type' => 'radio',
            'elemName' => 'gender',
            'multiOptions' => array('0' => 'Desativado', '1' => 'Ativado'),
            'decorators' => array($input_radio),
        ));
        
        $this->addElement($status);

        // Botão de Envio
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setIgnore(true)->setLabel(' Salvar');
        $this->addElement($submit);
        $submit->addDecorator($submit_decorator);


        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'div')),
            'Form'));






        // Configurações do Formulário
    }

}
