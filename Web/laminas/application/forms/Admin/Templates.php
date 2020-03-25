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
class Application_Form_Admin_Templates extends Zend_Form {

    //put your code here

    public function init() {
        // Nome da Pessoa

        $input_decorator = new Application_Form_Decorator_Input();
        $input_radio = new Application_Form_Decorator_Radio();
        $input_upload = new Application_Form_Decorator_Upload();
        $submit_decorator = new Application_Form_Decorator_Submit();

        $this->setName('templates');
        $this->setAttrib('class', 'form-horizontal');
        $this->setMethod('POST');


        $nome = new Zend_Form_Element_Text('titulo_template');
        $nome->setRequired(true)->setLabel('Titulo do template');
        $nome->setAttribs(array('type' => 'text', 'placeholder' => 'Insira o título do seu template', 'class' => 'col-sm-10', 'required' => 'data-error="Preencha o campo de titulo" required'));
        $this->addElement($nome);
        $nome->addDecorator($input_decorator);

        $url = new Zend_Form_Element_Text('url_template');
        $url->setRequired(true)->setLabel('Url');
        $url->setAttribs(array('type' => 'site', 'placeholder' => 'http://', 'class' => 'col-sm-10', 'required' => 'data-error="Preencha o campo de url" required'));
        $url->addDecorator($input_decorator);
        $url->setOptions(
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
//                                    Zend_Validate_Callback::INVALID_VALUE => 'Digite um endereço de url válido com o http://, exp (http://endereco do site.com.br)',
//                                ),
//                            ),
//                        ),
                    ),
                )
        );
        $this->addElement($url);


        $datavalid = new Zend_Form_Element_Text('datavalid_template');
        $datavalid->setRequired()
                ->setLabel('Data de validade')
                ->setAttribs(array('type' => 'data', 'placeholder' => 'fim da exibição', 'class' => 'col-md-2', 'data-mask' => '99/99/9999', 'required' => 'data-error="Preencha o campo de data" required'))
                ->addDecorator($input_decorator);
        $this->addElement($datavalid);

        $this->setAttrib('enctype', 'multipart/form-data');

        $file = new Zend_Form_Element('imagem_template', array(
            "type" => "file"
        ));
        $file->setLabel('Imagem do Template');
        // ->setDestination(APPLICATION_PATH . '/../public/uploads') 
        $file->addDecorator($input_upload);
        $this->addElement($file);

        $html = new Zend_Form_Element_Text('html_template');
        $html->setLabel('Código HTML')
                ->setAttribs(array('type' => 'textarea', 'rows' => '25', 'placeholder' => 'Insira seu código html aqui', 'class' => 'col-sm-6'))
                ->addDecorator($input_decorator);

        $this->addElement($html);

        $Model_categoria = new Application_Model_DbTable_Admin_Categorias();
        foreach ($Model_categoria->fetchAll() as $categoria) {
            if($categoria->excluido!=1){//filtra excluidos
				$c[$categoria->id_categoria] = $categoria->nome_categoria;
			}
        }

        $perfil = new Zend_Form_Element('id_categoria', array(
            'label' => 'Categoria',
            'type' => 'radio',
            'elemName' => 'gender',
            'multiOptions' => $c,
            'decorators' => array($input_radio),
        ));

        $this->addElement($perfil);


        $status = new Zend_Form_Element('status_template', array(
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
