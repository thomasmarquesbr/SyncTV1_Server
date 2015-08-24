<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include('importJoomla.php');
?>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alterar Canal</title>
</head>

<body>
<?php
	
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
		
		if ($db)
		{
			$flag=0;
			for($i=0;$i < count($_POST['newName']);$i++){
			
				if(mysql_query("UPDATE iv30h_canais SET nome=\"".$_POST['newName'][$i]."\",url=\"".$dirTVSYNC.$_POST['newName'][$i]."\" WHERE nome=\"".$_POST['oldName'][$i]."\";"))
				{
					replaceChannelXML($_POST['oldName'][$i],$_POST['newName'][$i]);
					$flag++;	
				}
			
			}
			if($flag == count($_POST['newName']))
			{
				echo "
					<label class=\"aviso\">O nome dos canais foram alterados com sucesso!</label><br/><br/>
					<input class=\"botao\" type=\"button\" value=\"voltar\" onclick=\"location.href='".$dirTVSYNC."canais.php'\"/>
					<input class=\"botao\" name=\"Novo\" value=\"Novo\" type=\"button\" onclick=\"location.href='".$dirTVSYNC."CadCanal.php'\"/>
				";
			}
		}
		mysql_close($conexao);
	}
?>
</body>
</html>