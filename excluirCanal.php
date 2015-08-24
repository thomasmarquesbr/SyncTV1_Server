<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('importJoomla.php'); ?>
<script language="JavaScript"> 
function confirma(){ 
   if (confirm('Tem certeza que deseja excluir o canal?')){ 
      document.seuformulario.submit() 
   } 
   return false;
} 
</script>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Excluir Canal</title>
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
			$sqlSelect = mysql_query("SELECT nome FROM iv30h_canais WHERE holder=\"$username\";")or die(mysql_error());//retorna canais do usuario logado
			if($array=mysql_fetch_array($sqlSelect))
			{
			//	echo "<form action=\"recebeExcluirCanal.php\" method=\"post\" onsubmit=\"return confirma();\"><label class=\"enunciado\">Escolha o canal que deseja excluir:</label><label>Canal: </label><select name=\"canal\">";
				echo"
					<form action=\"getExcluirCanal.php\" method=\"post\" onsubmit=\"return confirma();\">
					<div class=\"table2CSS\">
					<table>
					  <tr>
						<td colspan=\"2\">Escolha o canal que deseja excluir:</td>
					  </tr>
					  <tr>
						<td>Canal:</td>
						<td><select name=\"canal\">
				";
		
				do //faz um looping e cria um array com os campos da consulta
				{//mostra na tela o nome e a data de nascimento			  
				  echo "<option>".$array['nome']."</option>";
				}while($array = mysql_fetch_array($sqlSelect));
				//echo " </select><input type=\"submit\" name=\"btnEnviar\" value=\"Selecionar\"/></form>";
				
				echo "
					</select></td>
				  </tr>
				</table></div><br/>
				<input class=\"botao\" name=\"Voltar\" type=\"button\" value=\"<< Voltar\" onclick=\"location. href= '".$dirTVSYNC."canais.php'\"/>
				<input class=\"botao\" type=\"submit\" name=\"excluir\" value=\"Excluir\"/>
				
				</form>
				";
				}else
					{//não existe canal cadastrado
						echo "
							<label class=\"aviso\">Não Existe nenhum canal cadastrado!</label><br/><br/>
							<input class=\"botao\" name=\"voltar\" type=\"button\" value=\"Voltar\" onclick=\"location. href= '".$dirTVSYNC."canais.php'\"/>
							<input class=\"botao\" name=\"novo\" type=\"button\" value=\"Novo\" onclick=\"location. href= '".$dirTVSYNC."CadCanal.php'\"/>
						";
					}
		}
	mysql_close($conexao);
	}
?>

</body>
</html>