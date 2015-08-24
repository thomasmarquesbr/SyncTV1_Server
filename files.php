<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('importJoomla.php'); ?>
<script language="JavaScript"> 
function checkAll(o){
	var boxes = document.getElementsByTagName("input");
	for(var x=0; x<boxes.length;x++)
	{
		var obj = boxes[x];
		if(obj.type == "checkbox")
		{
			if(obj.name!= "chkAll")obj.checked = o.checked;
		}	
	}	
}
</script>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
</head>

<body>
<?php 	
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
		
		if ($db)
		{//conectado com o banco de dados
			$sqlSelect = mysql_query("SELECT * FROM iv30h_programacoes WHERE holder=\"$username\";")or die(mysql_error());
			//retorna canais do usuario logado
			if($array=mysql_fetch_array($sqlSelect))
			{
				echo "
					<form name=\"form1\" action=\"files.php\" method=\"get\" >
					<div class=\"table2CSS\">
					<table>
					  <tr>
						<td colspan=\"2\"><label class=\"enunciado\">Escolha a programação:</label></td>
					  </tr>
					  <tr>
						<td><label class=\"nmCampo\">Programação: </label></td>
						<td><select name=\"prog\" id=\"prog\" onchange=\"this.form.submit();\" \">
				";
				if(!isset($_GET['prog'])){
					echo "<option></option>";
					}
		
				do //faz um looping e cria um array com os campos da consulta
				{//mostra na tela o nome e a data de nascimento			 
				  if((isset($_GET['prog']))&&($_GET['prog']==$array['nome']))
				  {//canal setado e selecionado
				  		$type = $array['type'];
						echo "<option selected=\"selected\">".$array['nome']."</option>";
					}else
						{//canal não selecionado
							echo "<option>".$array['nome']."</option>";
						}
				  
				  
				  
				}while($array = mysql_fetch_array($sqlSelect));
				//echo " </select><input type=\"submit\" name=\"btnEnviar\" value=\"Selecionar\"/></form>";
				//echo"</select></form>";
				
				echo"
															</select></td>
							</tr>
						</table></div>
					</form>
				";
				
				if(isset($_GET['prog']))
				{
					$sqlSelect2 = mysql_query("SELECT * FROM iv30h_arquivos WHERE programacao = '".$_GET['prog']."';");
					
					echo "
						<form action=\"getFiles.php\" method=\"post\" name=\"form2\">
						<div class=\"tableCSS\">
						<table>
						  <tr>
							<td><input type=\"checkbox\" name=\"chkAll\" onclick=\"checkAll(this)\"/></td>
							<td>Nome</td>";
							if ($type == "synctv")
								echo"<td>Offset</td>
									<td>Duração</td>";
							echo"
							<td>Tipo</td>
						  </tr>";
						  while($array2 = mysql_fetch_array($sqlSelect2))
						  {
							  echo"
							  <tr>
								<td><input name=\"name[]\" type=\"checkbox\" value=\"".$array2['nome']."\" /></td>
								<td>".$array2['nome']."</td>";
								if ($type == "synctv")
									echo"<td>".$array2['offset']."<input type=\"text\" name=\"offset[]\" value=\"".$array2['offset']."\" hidden=\"true\"/></td>								
										<td>".$array2['duracao']."<input type=\"text\" name=\"duracao[]\" value=\"".$array2['duracao']."\" hidden=\"true\"/></td>";
								echo"
								<td>".$array2['tipo']."</td>
							  </tr>";
						  }
					echo"	  
						</table></div>
						<input type=\"text\" name=\"prog\" value=\"".$_GET['prog']."\" hidden=\"true\"/>
						<input type=\"text\" name=\"type\" value=\"".$type."\" hidden=\"true\"/>
						<br/>";
						if ($type == "synctv")
							echo "<input class=\"botao\" type=\"submit\" name=\"action\" value=\"Editar\"/>";
						echo"
						<input class=\"botao\" type=\"submit\" name=\"action\" value=\"Excluir\"/>
						<input class=\"botao\" name=\"enviarArquivos\" type=\"button\" value=\"Enviar Arquivos\" onclick=\"location. href= '".$dirTVSYNC."enviarArq.php' \"/>
						</form>
					";
		
					
					
				}
				
				}else
					{//não existe canal cadastrado
						echo "<label class=\"aviso\">Não existe nenhuma programação cadastrada!<br/>
								Por favor, cadastre uma programação!</label><br/><br/>
								<input class=\"botao\" name=\"cadastrar\" type=\"button\" value=\"Cadastrar\" onclick=\"location. href= '".$dirTVSYNC."cadProgram.php'\"/>
								";
					}
		}
	}
	mysql_close($conexao);

?>


</body>
</html>