<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Submit
 *
 * @author Allan.Carvalho
 */
class Application_Form_Decorator_Submit extends Zend_Form_Decorator_Abstract {
    

    public function render($content) {
        $element = $this->getElement();
        $label = htmlentities($element->getLabel());
        $class = $element->getAttrib('class');

        $markup = '<div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success '.$class.'"><span class="glyphicon glyphicon-floppy-saved"></span>'.$label.'</button>
                                </div></div>';
        return $markup;
    }

}
