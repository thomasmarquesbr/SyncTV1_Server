<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
include('importJoomla.php');
?>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Canal Cadastrado</title>
</head>

<body>
<?php 
	$nmCanal= $_POST['nmCanal'];
	
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
		
		if ($db)
		{//conectado com o banco de dados
			$sqlInsert = mysql_query("INSERT INTO iv30h_canais (nome,url,holder) VALUES ('$nmCanal','$dirTVSYNC$nmCanal','$username');");
			
			
			//insere valor no xml
			addChannelXML($nmCanal);
		}
		mysql_close($conexao);
	}
	
	if ($sqlInsert)
	{
		echo "
			<label class=\"aviso\">O canal $nmCanal foi cadastrado com sucesso!</label><br/><br/>
			<input class=\"botao\" name=\"novoCanal\" type=\"button\" value=\"Novo canal\" onclick=\"location. href= '".$dirTVSYNC."CadCanal.php'\"/>
			<input class=\"botao\" name=\"novaProgram\" type=\"button\" value=\"Nova Programação\" onclick=\"location. href= '".$dirTVSYNC."cadProgram.php'\"/>";
			
	}else
		{
			echo "
				<label class=\"aviso\">O canal $nmCanal já existe!</label><br/><br/>
				<input class=\"botao\" name=\"btnVoltar\" type=\"button\" value=\"Tentar Novamente\" onclick=\"history.go(-1)\"/>
				";
		}
?>




</body>
</html>