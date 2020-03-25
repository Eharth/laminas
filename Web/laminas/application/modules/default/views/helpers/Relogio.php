<?

class Zend_View_Helper_Relogio{

    public function relogio($data=NULL){
        $data_ini = explode("/", $data);
        $this->data_dia = $data_ini[0];
        $this->data_mes = $data_ini[1];
        $this->data_ano = $data_ano[2];
        return $this;
    }
}