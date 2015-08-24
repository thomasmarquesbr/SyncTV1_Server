<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('importjoomla.php');?>
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
		
		if ($db)
		{//conectado com o banco de dados
			if ($_POST['action'] == 'Editar')
			{
				echo"
					<form action=\"getFilesEdit.php\" method=\"post\">
					<div class=\"table2CSS\">
					<table>
					<tr>
					 <td>Nome</td>
					 <td>Offset</td>
					 <td>Duração</td>
					</tr>
				";
				for($k=0;$k < count($_POST['name']);$k++)
				{
					echo"
						<tr>
						<td>".$_POST['name'][$k]."<input type=\"text\" name=\"name[]\" value=\"".$_POST['name'][$k]."\" hidden=\"true\"/></td>
						<td><input type=\"text\" name=\"offset[]\" value=\"".$_POST['offset'][$k]."\"/ onkeypress=\"return onlyDigit(event)\" ></td>
						<td><input type=\"text\" name=\"duracao[]\" value=\"".$_POST['duracao'][$k]."\" onkeypress=\"return onlyDigit(event)\" /></td>
						</tr>
					";	
				}
				echo"
					</table></div><br/>
					<input type=\"text\" name=\"prog\" value=\"".$_POST['prog']."\" hidden=\"true\"/>
					<input class=\"botao\" type=\"button\" name=\"btnVoltar\" value=\"<< Voltar\"/>
					<input class=\"botao\" type=\"submit\" name=\"action\" value=\"Avançar >>\"/>
					</form>
				";
				
				
			}else
				if ($_POST['action'] == 'Excluir')
				{
					$flag=0;
					$str="";
					for($k=0;$k < count($_POST['name']);$k++)
					{
						if(mysql_query("DELETE FROM iv30h_arquivos WHERE nome='".$_POST['name'][$k]."' AND programacao='".$_POST['prog']."';"))
						{
							if($_POST['type'] == "synctv")
								removeMediaXMLProg($_POST['prog'],$_POST['name'][$k]);
							$flag++;
							removeTree("uploads/".$_POST['prog']."/".substr($_POST['name'][$k],0,-4));
						}else { $str = $str.",".$_POST['name'][$k]; }
					}
					
					if($flag = $k)
					{
						echo"
							<label class=\"aviso\">Todos os arquivos selecionados foram excluidos com sucesso!</label><br/><br/>
							<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location.href='".$dirTVSYNC."files.php'\"/>
						";
					}else
						{
							echo"
								<label class=\"aviso\">Somente os arquivos \"".$str."\" foram excluidos com sucesso!</label><br/><br/>
								<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location.href='".$dirTVSYNC."files.php'\"/>
							";	
						}
				}
			
		}
		mysql_close($conexao);
	}
?>

</body>
</html>