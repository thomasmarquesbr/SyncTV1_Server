<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include('importjoomla.php');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
</head>
<body>
<?php 	
	$nmProgram= $_POST['program'];
	
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
		
		if ($db)
		{//conectado com o banco de dados
			$array = mysql_fetch_array(mysql_query("SELECT * FROM iv30h_programacoes WHERE nome='$nmProgram';"));
			$sqlInsert = mysql_query("DELETE FROM iv30h_programacoes WHERE nome=\"$nmProgram\";");
			
		}
	}
	mysql_close($conexao);
	if ($sqlInsert)
	{//Canal excluido com sucesso
		removeProgXML($array['canal'],$nmProgram);
		
		echo "
			<label class=\"aviso\">A programação \"".$nmProgram."\" foi excluida com sucesso!</label><br/><br/>
			<input class=\"botao\" name=\"voltar\" type=\"button\" value=\"<< Voltar\" onclick=\"location. href= '".$dirTVSYNC."program.php' \"/>
			";
		//apagando diretorio
		removeTree("uploads\\".$nmProgram);
	}else
		{
			echo "<label class=\"aviso\">Erro ao excluir!</label><br/><br/>
				  <input class=\"botao\" name=\"voltar\" type=\"button\" value=\"<< Voltar\" onclick=\"location. href= '".$dirTVSYNC."program.php' \"/>
			";
		}

?>
</body>
</html>