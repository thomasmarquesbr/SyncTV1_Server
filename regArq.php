<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('importjoomla.php')?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
</head>

<body>
<?php

$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
	}

if($db)
{
		$flag=0;
		for ($k = 0; $k < count($_POST['file']); $k++)
		{
			$nome = $_POST['file'][$k];
			$offset= $_POST['offset'][$k];
			$dur= $_POST['dur'][$k];
			
			$sql= mysql_query("UPDATE iv30h_arquivos SET offset=\"$offset\",duracao=\"$dur\" WHERE nome='$nome';");
			
			if($sql){ 
				replaceAtribMediaXMLProg($_POST['prog'],$nome,$offset,$dur,$_POST['type'][$k]);
				$flag++;		
				}
			
		}
		
		if ($flag == $k)
		{
			echo "
				<label class=\"aviso\">Valores registrados com sucesso!</label><br/><br/>
				<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location. href= '".$dirTVSYNC."files.php'\"/>
			";	
		}else{
			echo "
				<label class=\"aviso\">Não foi possível enviar os valores!</label><br/><br/>
				<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location. href= '".$dirTVSYNC."files.php'\"/>
			";
			}
		
}

mysql_close($conexao);


?>
</body>
</html>