<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include('importJoomla.php');

function setString($string){
		$string =str_replace(" ","_",strtolower($string));
		return $string;
	}
	
function upload($arquivo,$caminho){
	if(!(empty($arquivo))){
		$arquivo1 = $arquivo;
		$arquivo_minusculo = strtolower($arquivo1['name']);
		$caracteres = array("ç","~","^","]","[","{","}",";",":","´",",",">","<","-","/","|","@","$","%","ã","â","á","à","é","è","ó","ò","+","=","*","&","(",")","!","#","?","`","ã"," ","©");
		$arquivo_tratado = str_replace($caracteres,"",$arquivo_minusculo);
		$destino = $caminho.$arquivo_tratado;
		if(move_uploaded_file($arquivo1['tmp_name'],$destino)){
			/*echo "<script>window.alert('Arquivo enviado com sucesso.');</script>";*/
		}else{
			echo "<script>window.alert('Erro ao enviar o arquivo');</script>";
		}
	}
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
<title>Cadastro Programação NCL</title>
</head>

<body>
<?php 
	$nmCanal = $_POST['nmCanal'];
	$nmProgram= $_POST['nmProgram'];
	$service= $_POST['service'];
	
	$dthr =  convertZone($_POST['zone'],$_POST['data'],$_POST['hora'],"zoneToUtc");
	$dividir = explode(" ", $dthr);
	$parte1 = $dividir[0]; $parte2 = $dividir[1];
	$dtProgram=$parte1;
	$hrProgram=$parte2;
	
	
	
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
		$flag = 0;
			
		if ($db)
		{//conectado com o banco de dados
			
			//$sqlInsert = mysql_query("INSERT INTO iv30h_programacoes (nome,url,data,hora,canal,demanda,dispon,holder,type) VALUES ('$nmProgram','".$dirTVSYNC."uploads/".$nmProgram."/".$_FILES['arquivo']['name']."','$dtProgram','$hrProgram','$nmCanal','".$_POST['dispon']."','$username','ncl');");
			$sqlInsert = mysql_query("INSERT INTO iv30h_programacoes (nome,url,data,hora,canal,service,dispon,holder,type) VALUES ('$nmProgram','".$dirTVSYNC."uploads/".$nmProgram."','$dtProgram','$hrProgram','$nmCanal','$service','".$_POST['dispon']."','$username','synctv');");
			
						
		}
		mysql_close($conexao);
	}
	
	if ($sqlInsert)
	{//programação cadastrada com sucesso
		
		
		echo "<label class=\"aviso\">A programação \"$nmProgram\" foi cadastrada com sucesso!</label><br/><br/>
		<input class=\"botao\" name=\"voltar\" type=\"button\" value=\"<< Voltar\" onclick=\"location.href= '".$dirTVSYNC."program.php'\"/>
		<input class=\"botao\" name=\"enviar\" type=\"button\" value=\"Enviar mídias\" onclick=\"location.href= '".$dirTVSYNC."enviarArq.php'\"/>";
		
		
	//CRIANDO PASTA ---------
	
	mkdir("uploads/".$nmProgram,0777);
	
	upload($_FILES['arquivo'],'uploads/'.$nmProgram."/");
	
	addProgrammingXML($nmCanal,$nmProgram, $dtProgram,$hrProgram, $dirTVSYNC."uploads/".$nmProgram."/".$_FILES['arquivo']['name'],$_POST['dispon'],"ncl",$service);
	
	//=========================	
		
	}else
		{//programação ja cadastrada
			echo "<label class=\"aviso\">A programação $nmProgram já existe!</label><br/><br/>
				  <input class=\"botao\" name=\"voltar\" type=\"button\" value=\"<< Voltar\" onclick=\"location.href= '".$dirTVSYNC."program.php'\"/>
				  <input class=\"botao\" name=\"tentar novamente\" type=\"button\" value=\"Tentar Novamente\" onclick=\"history.go(-1)\"/>";
		}
?>

</body>
</html>