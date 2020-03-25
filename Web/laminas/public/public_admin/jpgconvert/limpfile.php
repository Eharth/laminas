<?php

if(isset($_GET["id"])){
	if($_GET["id"]!=""){		
		$pasta = 'images/'.$_GET["id"]."/";

		if(is_dir($pasta)){
			$diretorio = dir($pasta);
			while(($arquivo = $diretorio->read()) !== false){
				if(is_file($pasta.$arquivo)){					
					unlink($pasta.$arquivo);
				}
			}
			$diretorio->close();
		}	
		rmdir("images/".$_GET["id"]);	
	}	
}

?>