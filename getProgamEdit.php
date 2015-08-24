<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include('importjoomla.php');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Editando Programação</title>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
</head>


<body>
<?php
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
		
		if ($db)
		{//conectado com o banco de dados
			
			$flag=0;
			$str="";
			for($k=0;$k < count($_POST['name']);$k++)
			{				
			
				$dthr =  convertZone($_POST['zone'],$_POST['data'][$k],$_POST['hora'][$k],"zoneToUtc");
				$dividir = explode(" ", $dthr);
				$parte1 = $dividir[0]; $parte2 = $dividir[1];
				$_POST['data'][$k]= $parte1;
				$_POST['hora'][$k]= $parte2;
				
				
	
				if(mysql_query("UPDATE iv30h_programacoes SET data='".$_POST['data'][$k]."',hora='".$_POST['hora'][$k]."',service='".$_POST['service'][$k]."',dispon='".$_POST['dispon'][$k]."' WHERE nome='".$_POST['name'][$k]."';"))
				{
					replaceProgXML($_POST['canal'][$k],$_POST['name'][$k],$_POST['data'][$k],$_POST['hora'][$k],$_POST['dispon'][$k],$_POST['service'][$k]);
					$flag++;
				}else { $str = $str.",".$_POST['name'][$k]; }
			}
			
			if($flag = $k)
			{
				echo"
					<label class=\"aviso\">Dados alterados com sucesso!</label><br/><br/>
					<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location.href='".$dirTVSYNC."program.php'\"/>
				";
			}else
				{
					echo"
						<label class=\"aviso\">Somente os dados de \"".$str."\" foram alterados com sucesso!</label><br/><br/>
						<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location.href='".$dirTVSYNC."program.php'\"/>
					";	
				}		
			
		}
		mysql_close($conexao);
	}
?>
</body>
</html>