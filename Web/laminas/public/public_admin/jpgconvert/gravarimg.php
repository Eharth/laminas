<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Arquivo JPG</title>	
	<script src="https://www.lamina.bwtoperadora.com.br/laminas/public/public_admin/js/jquery-1.11.1.min.js"></script> 
	<script type="text/javascript" src="https://www.lamina.bwtoperadora.com.br/laminas/public/public_admin/js/html2canvas.js"></script>
	<link rel="stylesheet" href="https://www.lamina.bwtoperadora.com.br/laminas/public/public_admin/js/jquery-ui-1.11.4.custom/jquery-ui.min.css">
	<script src="https://www.lamina.bwtoperadora.com.br/laminas/public/public_admin/js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>	
	<style>
		#contatobwt{display:none !important}
		.btn {
			display: inline-block;
			padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: 400;line-height: 1.42857143;text-align: center;	white-space: nowrap;
			vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;	cursor: pointer;-webkit-user-select: none;	-moz-user-select: none;-ms-user-select: none;	user-select: none;background-image: none;	border: 1px solid transparent;
			border-radius: 4px;height:55px !important;
		}
		.btn-danger {color: #fff;  background-color: #d9534f; border-color: #d43f3a;}
	</style>
</head>

<?php
/*
	//@@ Robson - Jun/2017
	//include: html2canvas lib by: Niklas von Hertzen
	//Licença: free, mantenha os creditos @robson and @niklas	
*/

error_reporting(0); // REMOVE ALERTa de ERROS

function gravarIMG($url,$folder,$fname){
	$content = file_get_contents($url);
	$fp = fopen($folder.$fname, "w");
	fwrite($fp, $content);
	fclose($fp);
}
function my_file_get_contents( $site_url ){
	$ch = curl_init();
	$timeout = 0; // set to zero for no timeout
	curl_setopt ($ch, CURLOPT_URL, $site_url);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	ob_start();
	curl_exec($ch);
	curl_close($ch);
	$file_contents = ob_get_contents();
	ob_end_clean();
	return $file_contents;
}
$nweURL = $_SERVER['REQUEST_URI']; //"511&540"; //
$nweU = explode("?", $nweURL);
$nweURL0 = explode("&", $nweU[1]);

$uid = $nweURL0[1];
$nid = $nweURL0[0];
//echo 'http://www.bwtoperadora.com.br/laminas/index/html-lamina/nid/'.$nid.'/uid/'.$uid;

$userID = $uid;

$dtF = $userID;
mkdir("images/".$dtF, 0777, true);
chmod("images/".$dtF, 0777);

$bloco = nl2br(htmlentities($_POST['campo']));
//echo  html_entity_decode($bloco) ;

$html =  html_entity_decode($bloco) ;
$htmlE = str_replace("/laminas/public/","https://www.lamina.bwtoperadora.com.br/laminas/public/", str_replace("https://www.lamina.bwtoperadora.com.br/laminas/public/", "/laminas/public/", $html) );
$temp=($html);

preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i',$htmlE, $matches );

for($i=0;$i<50;$i++){
	if(isset($matches[ 1 ][ $i ])){
		if($matches[ 1 ][ $i ]){
			//echo $matches[ 1 ][ $i ]."<hr>";
			$nomeFileTemp = explode("/", $matches[ 1 ][ $i ]);		
			$nomeFile = $nomeFileTemp[count($nomeFileTemp)-1];							
			gravarIMG($matches[ 1 ][ $i ],"images/".$dtF."/", $nomeFile);
			/*
			echo "**************************************<p align='left'>";
			echo "nome original: ".$matches[ 1 ][ $i ]."<br>";
			echo "nome change  : "."images/".$dtF."/".$nomeFile;
			echo "**************************************</p>";			
			*/
			$temp = str_replace( $matches[ 1 ][ $i ],"images/".$dtF."/".$nomeFile, $temp );		
		}
	}
}
?>
<body bgcolor="#ffffff" align="center">

<script>	
function gerarJPG(){
	html2canvas($("#areax"), {
	  onrendered: function(canvas) {
		//document.body.appendChild(canvas);
		$("#img").attr("src", canvas.toDataURL("image/jpeg"));	
		  var a = document.createElement('a');			
			a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");			
			a.download = 'lamina.jpg';
			a.click();
			
			document.getElementById("dialog").style.display="block";
			$( "#dialog" ).dialog({
                position: { my: 'top', at: 'top+150' }                
            });
		  	setTimeout(resetDados, 2000);
	  }
	});	
}
function resetDados(){
	$( "#result" ).load( "limpfile.php?id=<?php echo $userID; ?>", function( response, status, xhr ) {
		if ( status == "error" ) {
			var msg = "Sorry but there was an error: ";
			console.log( msg + xhr.status + " " + xhr.statusText );
		}
	});	
}

setTimeout('gerarJPG()', 1500);
</script>

<div id="areax" style="width:820px;padding-top:10px;background-color:#fff;margin:0 auto"><?php echo str_replace("<br />","",$temp); ?></div>
<div id="result"></div>
<div id="dialog" title="Lamina JPG" style="display:none;top:40% !important;">
  <p><img src="default.png" border="0" width="200" id="img" ></p>
  <p style="font-size:13px;color:red;font-family:Arial;">Caso o <b>download automático</b> não aconteça, Clique com o botão direito sobre a imagem e selecione <b>"Salvar Imagem Como"</b></p>
  <p style="font-size:11px;font-family:Arial;">Recomendado: Chrome, Firefox</p>
</div>
<p align="center" style="margin-top:50px">
	<button type="button" onclick="gerarJPG()" id="buttonClick" class="btn btn-danger">clique aqui se o arquivo JPG não foi gerado</button>
</p>
</body>
</html>