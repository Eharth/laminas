<?php
error_reporting(E_ALL);
//BIBLIOTECA DE IMAGEM
require_once("Wideimage/WideImage.inc.php");
error_reporting(E_ALL);
//CABECALHO
function img_header($format) {
  @header('Pragma: no-cache');
  @header('Content-type: uploads/' . $format);
}
try {
  //DADOS ENVIADOS
  if(isset($_SERVER['REQUEST_URI'])) {
    $info = explode("*", $_SERVER['REQUEST_URI']);
  } else {
    $info = explode("*", $_SERVER['PATH_INFO']);
  }
  // nome do arquivo atual
  $this_file_name = basename(__FILE__);

  // get the start position for substring
  $ini_position = strpos($info[0], $this_file_name."/")+strlen($this_file_name);

  // get only folder and image file
  $file_path = substr($info[0], $ini_position, strlen($info[0]));
//  die($file_path);
  /*
  USO: image.php/arquivo*largura*altura*tipo*qualidade
  0 arquivo = caminho do arquivo
  1 largura = numerico
  2 altura = numerico
  3 tipo = C | I | M (padrao I)
  4 qualidade = numerico (padrao 80)
  5 grayscale  = escala de cinza, padrao 0 (false)
  */
  
  define("TIPO", @isset($info[3]) ? strtoupper($info[3]) : "I", true); //TIPO DE MINIATURA
  define("QUALIDADE", @isset($info[4]) ? $info[4] : 80, true); //QUALIDADE DA IMAGEM
  define("GO_GRAYSCALE", @isset($info[5]) ? (int)$info[5] === 0 : FALSE);
  define("IMG_FOLDER", ".");		//DIRETORIO BASE (SEM BARRAS)
  define("CACHE_FOLDER", "cache/");	//SUBDIRETORIO DE CACHE (COM BARRAS)
  //DADOS DO ARQUIVO
  $image_file = basename($file_path);				//NOME DO ARQUIVO COMPLETO, 10023255.jpg
  $__foo = explode(".", $image_file);
  $image_name = $__foo[0];							//APENAS NOME , 10023255
  $image_type = $__foo[1];							//APENAS EXTENSAO, jpg
  @$image_date = filemtime(IMG_FOLDER.$file_path);	//DATA DE MODIFICACAO DO ARQUIVO

  //CARREGA A IMAGEM
  if(is_file(IMG_FOLDER.$file_path)) {
    $imagem = wiImage::load(IMG_FOLDER.$file_path);
  } else {
    die("Erro no arquivo: ".IMG_FOLDER.$file_path);
  }

  //OBTEM EXTENSAO DO ARQUIVO
  $extensao = array_reverse(explode(".", $file_path));
  $format = $extensao[0];

  //TAMANHO DA MINIATURA
  $width = (@isset($info[1])) ? $info[1] : null;
  $height = (@isset($info[2])) ? $info[2] : null;

  //DIRETORIO DE CACHE
  $image_dir = dirname(IMG_FOLDER.$file_path);
  $image_cache_dir = $image_dir."/".CACHE_FOLDER;

  //NOME DO ARQUIVO GERADO
  $imagem_gerada = $image_cache_dir.$image_name."_".$image_date."_".TIPO."_".$width."_".$height.(GO_GRAYSCALE ? "_gray": "").".".$extensao[0];
  //VALIDA SE A IMAGEM EXISTE E A DATA MODIFICADA E A MESMA
  if (file_exists($imagem_gerada)) {
    if ($fp = fopen($imagem_gerada, 'rb')){
      header ("Location:/".$imagem_gerada);
      exit;
    }
  }

  //VALIDA O TIPO DA MINIATURA
  switch(TIPO) {
    case "I":
      if(!is_null($width) || !is_null($height)) {
        $imagem = $imagem->resize($width, $height);
      } else {
        die("Paramentros invalidos");
      }
      break;
    case "M":

      if(!is_null($width) && !is_null($height)) {
        $imagem = $imagem->resize($width, $height, 'outside');
      } else {
        die("Paramentros invalidos");
      }
      break;
    case "C":
    //NOME DO ARQUIVO TMP
      $imgTmp = IMG_FOLDER.$extensao[1].'_tmp.'.$extensao[0];

      if(!is_null($width) && !is_null($height)) {
        $imagem = $imagem->resize($width, $height, 'outside')->saveToFile($imgTmp);
      } else {
        die("Paramentros invalidos");
      }

      //PEGA O TAMANHO DA IMAGEM
      $imageSize = getimagesize($imgTmp);

      //CROPA
      $x = ((int)$imageSize[0]/2)-((int)$info[1]/2);
      $y = ((int)$imageSize[1]/2)-((int)$info[2]/2);

      $imagem = wiImage::load($imgTmp);
      $imagem = $imagem->crop($x, $y, $info[1], $info[2]);

      unlink($imgTmp);
      break;
    default:
      die("Tipo invalido");
      break;
  }

  //VALIDA SE EXISTE O DIRETORIO CACHE, TENTA CRIA-LO
  if(!is_dir($image_cache_dir)) {
    mkdir($image_cache_dir);
  }

  // CHECA ESCALA DE CINZA
  if(GO_GRAYSCALE){
    $imagem = $imagem->asGrayscale();
  }
  //SALVA A IMAGEM
  $imagem->saveToFile($imagem_gerada, null, QUALIDADE);

  header ("Location:/".$imagem_gerada);
  // echo $imagem->asString($format, QUALIDADE);
} catch (Exception $e) {

}

?> 