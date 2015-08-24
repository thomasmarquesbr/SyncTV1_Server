<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
include('importjoomla.php');
?>
<script language="JavaScript"> 
function confirma(){ 
   if (confirm('Tem certeza que deseja excluir a programação?(Lembrando que todos os arquivos referentes à programação também serão deletados!)')){ 
      document.seuformulario.submit() 
   } 
   return false;
} 
</script>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Excluindo Programação</title>
</head>

<body>
<?php
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
		
		if ($db)
		{//conectado com o banco de dados
			$sqlSelect = mysql_query("SELECT nome,canal FROM iv30h_programacoes WHERE holder=\"$username\";")or die(mysql_error());//retorna canais do usuario logado
			if($array=mysql_fetch_array($sqlSelect))
			{
				echo"
					<form action=\"getExcluirProgram.php\" method=\"post\" onsubmit=\"return confirma();\">
					<div class=\"table2CSS\">
					<table>
					  <tr>
						<td colspan=\"2\"><label class=\"enunciado\">Escolha a programação que deseja excluir:</label></td>
					  </tr>
					  <tr>
						<td><label>Programação: </label></td>
						<td><select name=\"program\">
				";
		
				do //faz um looping e cria um array com os campos da consulta
				{//mostra na tela o nome e a data de nascimento			  
				  echo "<option>".$array['nome']."</option>";
				}while($array = mysql_fetch_array($sqlSelect));
				
				echo "
					</select></td>
				  </tr>
				</table></div><br/>
				<input class=\"botao\" name=\"voltar\" type=\"button\" value=\"Voltar\" onclick=\"location. href= '".$dirTVSYNC."program.php'\"/>
				<input class=\"botao\" type=\"submit\" name=\"excluir\" value=\"Excluir\"/>
				</form>
				";
				}else
					{//não existe canal cadastrado
						echo "
							<label class=\"aviso\">Não Existe nenhuma programação cadastrada!</label><br/><br/>
							<input class=\"botao\" name=\"cadastrar\" type=\"button\" value=\"Cadastrar\" onclick=\"location. href= '".$dirTVSYNC."cadProgram.php'\"/>
						";
					}
		}
	mysql_close($conexao);
	}
?>
</body>
</html>