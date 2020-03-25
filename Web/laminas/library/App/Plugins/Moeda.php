<?php

class Zend_View_Helper_Moeda{

  public function moeda($valor){
    $valor_ini = explode(".", $valor);
    $this->moeda_real = $valor_ini[0];
    $this->moeda_centavo = $valor_ini[1];
    $this->moeda_tudo = $valor_ini[0].",".$valor_ini[1];
    return $this;
  }

}