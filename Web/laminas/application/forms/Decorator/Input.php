<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Label
 *
 * @author Allan.Carvalho
 */
class Application_Form_Decorator_Input extends Zend_Form_Decorator_Abstract {

    public function render($content) {
        $element = $this->getElement();
        $name = htmlentities($element->getFullyQualifiedName());
        $type = $element->getAttrib('type');
        $label = $element->getLabel();
        $id = htmlentities($element->getId());
        $placheholder = $element->getAttrib('placeholder');
        $rows = $element->getAttrib('rows');
        $class = $element->getAttrib('class');
        $values = $element->getValue();
        if ($type == 'data') {
            if ($values) {
                $value = App_Plugins_Data::data($values)->databr;
            } else {
                $value = $values;
            }
        } else {
            $value = $values;
        }
        $required = $element->getAttrib('required');
        $mask = $element->getAttrib('data-mask');

        if ($mask) {
            $mask_value = "data-mask = " . $mask;
        } else {
            $mask_value = false;
        }

        if ($required) {
            $validade = $required;
        } else {
            $validade = false;
        }

        if ($type == 'textarea') {
            $markup = '<div class="form-group">
                            <label class="col-sm-2 control-label" for="' . $name . '">' . $label . '</label>
                                <div class="' . $class . '">
                                    <textarea rows="'.$rows.'"  class="form-control" id="' . $id . '" name="' . $name . '" placeholder="' . $placheholder . '" ' . $validade . '>' . $value . '</textarea>
                                </div></div>';
        } else {
            $markup = '<div class="form-group">
                            <label class="col-sm-2 control-label" for="' . $name . '">' . $label . '</label>
                                <div class="' . $class . '">
                                    <input class="form-control" id="' . $id . '" name="' . $name . '" type="' . $type . '" placeholder="' . $placheholder . '" ' . $validade . ' ' . $mask_value . ' value="' . $value . '"/>                                  
                                </div></div>';
        }


        return $markup;
    }

}

class Application_Form_Decorator_Radio extends Zend_Form_Decorator_Abstract {

    public function render($content) {
        $element = $this->getElement();

        $label = htmlentities($element->getLabel());
        $name = htmlentities($element->getFullyQualifiedName());
        $id = htmlentities($element->getId());
        $checked = $element->getValue();
        $multiOptions = $element->multiOptions;



        //$markup = '';

        $markup = '<div class="form-group">
            		<label for="inputEmail3" class="col-sm-2 control-label">' . $label . '</label>
                            <div class="col-xs-4">';


        foreach ($multiOptions as $key => $value) {

            if ($checked == $key) {
                $enable = "checked";
            } else {
                $enable = "";
            }

            $markup .= '<label class="radio-inline">
                            <input type="radio" name="' . $name . '" ' . $enable . '  value="' . $key . '"> ' . $value . '
			</label>';
        }

        $markup .= '</div></div>';

        return $markup;
    }

}

class Application_Form_Decorator_Upload extends Zend_Form_Decorator_Abstract {

    public function render($content) {
        $element = $this->getElement();
        $name = htmlentities($element->getFullyQualifiedName());
        $label = $element->getLabel();
        $id = htmlentities($element->getId());
        $placheholder = $element->getAttrib('placeholder');
        $value = $element->getValue();


      

        $markup = '<div class="form-group">
            		<label for="' . $name . '" class="col-sm-2 control-label">' . $label . '</label>
                            <div class="col-sm-6"><div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">';

        if ($value) {
            $markup .= '<img src="' . BASE_URL . "/../public/uploads/" . $value . '" alt="teste">';
        }

        $markup .='</div>
                            <div>
                            <p class="help-block">Imagens somente no formato png, jpg e gif com o tamanho máximo de 400px.</p>
                                <span class="btn btn-default btn-file"><span class="fileinput-new">Selecione uma Imagem</span><span class="fileinput-exists">Escolher Imagem</span><input type="file" name="' . $name . '"></span>
                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                            </div>
                        </div></div>
                    </div>';




        return $markup;
    }

}
