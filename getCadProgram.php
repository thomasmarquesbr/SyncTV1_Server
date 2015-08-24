<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include('importJoomla.php');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cadastrando Programação</title>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
</head>

<body>
<?php 
	$nmCanal = $_POST['nmCanal'];
	$nmProgram= $_POST['nmProgram'];
	$service= $_POST['service'];
	//$dtProgram=$_POST['data'];
	//$hrProgram=$_POST['hora'];
	//$utcTime =  convertZone($_POST['zone'],$_POST['hora'],"utcToZone");
	$dthr =  convertZone($_POST['zone'],$_POST['data'],$_POST['hora'],"zoneToUtc");
	//echo $hrProgram."  ".$_POST['zone'];
	$dividir = explode(" ", $dthr);
	$parte1 = $dividir[0]; $parte2 = $dividir[1];
	$dtProgram=$parte1;
	$hrProgram=$parte2;
	
	
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
		
		
		if ($db)
		{//conectado com o banco de dados
			
			$sqlInsert = mysql_query("INSERT INTO iv30h_programacoes (nome,url,data,hora,canal,service,dispon,holder,type) VALUES ('$nmProgram','".$dirTVSYNC."uploads/".$nmProgram."','$dtProgram','$hrProgram','$nmCanal','$service','".$_POST['dispon']."','$username','synctv');");
		}
		mysql_close($conexao);
	}
	
	if ($sqlInsert)
	{//programação cadastrada com sucesso
		
		
		echo "<label class=\"aviso\">A programação \"$nmProgram\" foi cadastrada com sucesso!</label><br/><br/>
		<input class=\"botao\" name=\"voltar\" type=\"button\" value=\"<< Voltar\" onclick=\"location.href= '".$dirTVSYNC."program.php'\"/>
		<input class=\"botao\" name=\"Enviar\" type=\"button\" value=\"Enviar Mídias\" onclick=\"location.href= '".$dirTVSYNC."EnviarArq.php'\"/>
		<input class=\"botao\" name=\"Enviar\" type=\"button\" value=\"Adicionar WebPages\" onclick=\"location.href= '".$dirTVSYNC."addWebPages.php'\"/>";
		
	//CRIANDO PASTA ---------
	
	mkdir("uploads/".$nmProgram,0777);
	addProgrammingXML($nmCanal,$nmProgram, $dtProgram,$hrProgram, $dirTVSYNC."uploads/".$nmProgram,$_POST['dispon'],"synctv",$service);
	
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