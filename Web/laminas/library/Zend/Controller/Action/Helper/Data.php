<?

class Zend_Controller_Action_Helper_Data extends Zend_Controller_Action_Helper_Abstract
{
  public function data($data=NULL){
    $date = new Zend_Date($data, Zend_Date::ISO_8601);
    $this->databr = $date->toString('F');
    return $date;
  }

  public function datadb($data=NULL){
    $dataini = $data;
    $dataex=explode("/", $dataini);
    $datafim = $dataex[2].$dataex[1].$dataex[0];
    return $datafim;
  }

}