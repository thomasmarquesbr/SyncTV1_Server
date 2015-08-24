<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include('importjoomla.php');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Excluindo Canal</title>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
</head>

<body>
<?php 	
	$nmCanal= $_POST['canal'];
	
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
		
		if ($db)
		{//conectado com o banco de dados
			$result = mysql_query("SELECT * FROM iv30h_programacoes WHERE canal='".$nmCanal."';");
			while($array = mysql_fetch_array($result)){//removendo pastas prog referentes ao canal
					removeTree("uploads/".$array['nome']);
				}
			$sqlInsert = mysql_query("DELETE FROM `iv30h_canais` WHERE nome=\"$nmCanal\";");
			removeChannelXML($nmCanal);
		}
	}
	
	if ($sqlInsert)
	{//Canal excluido com sucesso
		echo "
			<label class=\"aviso\">O canal \"".$nmCanal."\" foi excluido com sucesso!</label><br/><br/>
			<input class=\"botao\" name=\"voltar\" type=\"button\" value=\"<< Voltar\" onclick=\"location. href= '".$dirTVSYNC."canais.php' \"/>
			";
		
	}else
		{
			echo "<label class=\"aviso\">Erro ao excluir</label>";
		}

?>


<?php mysql_close($conexao); ?>
</body>
</html>