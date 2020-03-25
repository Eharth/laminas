<?php
class App_Plugins_String {

  public static function removeAcentos($str, $enc = 'UTF-8') {

    $acentos = array(
        'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
        'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
        'C' => '/&Ccedil;/',
        'c' => '/&ccedil;/',
        'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
        'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
        'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
        'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
        'N' => '/&Ntilde;/',
        'n' => '/&ntilde;/',
        'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
        'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
        'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
        'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
        'Y' => '/&Yacute;/',
        'y' => '/&yacute;|&yuml;/',
        'a.' => '/&ordf;/',
        'o.' => '/&ordm;/'
    );

    return preg_replace($acentos, array_keys($acentos), htmlentities($str,ENT_NOQUOTES, $enc));
  }

  public static function limpaMask($valor){
     return str_replace(array("(", ")", "-", " ", ".", ",", "#", "/"), "", $valor);
  }

  public static function limpaMoeda($valor){
     $valor = str_replace("R$ ", "", $valor);
     return str_replace(array(".", ","), array("","."), $valor);
  }

  public static function alternator() {
    static $i;

    if (func_num_args() == 0) {
      $i = 0;
      return '';
    }
    $args = func_get_args();
    return $args[($i++ % count($args))];
  }

  public static function url($string) {
    $string = preg_replace("`\[.*\]`U","",$string);
    $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
    $string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
    return strtolower(trim($string, '-'));

  }

  public static function crop($string, $length, $spacer = ""){
    if(strlen($string) > $length){
      return mb_substr($string, 0, $length, 'utf-8').$spacer;
    }
    return $string;
  }

  public static function sample($text, $length, $spacer = ""){
    $text = trim(strip_tags(stripslashes($text)));
    return self::crop($text, $length, $spacer);
  }
  public static function mymessage($text){

  }

  /**
   * Format uma string de acordo com a máscara desejada
   * @param  string $str     O valor que deseja ser formatado
   * @param  string $mascara O formato da máscara utilizando # ex. telefone '(##) ####-####'
   * @return string          A string formatada
   */
  public static function mask($str, $mascara) {
    $str = str_replace(" ","",$str);
    for($i = 0; $i < strlen($str); $i++)
    {
      $mascara[strpos($mascara,"#")] = $str[$i];
    }
    return $mascara;

  }

  /**
   * Máscara para telefone COM DDD
   * @param  string $str A string desejada
   * @return string      A string formatada
   */
  public static function maskPhoneDDD($str) {
    return self::mask($str, '(##) ####-####');
  }

  /**
   * Máscara para telefone SEM DDD
   * @param  string $str A string desejada
   * @return string      A string formatada
   */
  public static function maskPhone($str) {
    return self::mask($str, '####-####');
  }

  /**
   * Máscara para CNPJ
   * @param  string $str
   * @return string
   */
  public static function maskCnpj($str) {
    return self::mask($str, '##.###.###/####-##');
  }

  /**
   * Máscara para cep
   * @param  string $str
   * @return string
   */
  public static function maskCep($str) {
    return self::mask($str, '##.###-###');
  }


}