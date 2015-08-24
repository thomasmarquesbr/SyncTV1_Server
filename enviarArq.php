<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('importjoomla.php'); ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Enviar Arquivos</title>
<script language="javascript">
function habilitaForm(){
	document.getElementById("prog").disabled = false;
	document.getElementById("file").disabled = false;
	document.getElementById("btnEnviar").disabled = false;
	document.getElementById("btnVoltar").disabled = false;
	}
</script>
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
			$sqlSelect = mysql_query("SELECT * FROM iv30h_canais WHERE holder=\"$username\";")or die(mysql_error());//retorna canais do usuario logado
			if($array=mysql_fetch_array($sqlSelect))
			{
				echo "
					<form name=\"form1\" action=\"enviarArq.php\" method=\"get\" >
					<div class=\"table2CSS\"/>
					<table>
					  <tr>
						<td colspan=\"2\">Escolha o canal e programação para envio dos arquivos:</td>
					  </tr>
					  <tr>
						<td>Canal: </td>
						<td><select name=\"canal\" id=\"canal\" onchange=\"this.form.submit();\" \">
				";
				if(!isset($_GET['canal'])){
					echo "<option></option>";
					}
		
				do //faz um looping e cria um array com os campos da consulta
				{//mostra na tela o nome e a data de nascimento			 
				  if((isset($_GET['canal']))&&($_GET['canal']==$array['nome']))
				  {//canal setado e selecionado
						echo "<option selected=\"selected\">".$array['nome']."</option>";
					}else
						{//canal não selecionado
							echo "<option>".$array['nome']."</option>";
						}
				  
				  
				  
				}while($array = mysql_fetch_array($sqlSelect));
				
				echo"
															</select></td>
							</tr>
						
					</form>
				";
				
				echo "
						<form name=\"form\" action=\"getEnviarArq.php\" method=\"post\" enctype=\"multipart/form-data\">
					  
						  <tr>
							<td>Programação: </td>
							<td><select name=\"prog\" id=\"prog\" disabled=\"true\">
					";
					
					
				if(isset($_GET['canal']))
				{	
					$sqlSelect2 = mysql_query("SELECT nome FROM iv30h_programacoes WHERE canal='".$_GET['canal']."'; ");
					while($array2 = mysql_fetch_array($sqlSelect2)) 
					{		 
					  echo "<option>".$array2['nome']."</option>";
					  
					    
					};	
				}
					
					echo"
							</select></td>
						  </tr>
						  <tr>
						  	<td colspan=\"2\">
								<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"104857600\">
								<input id=\"file\" name=\"arquivo[]\" type=\"file\" multiple=\"multiple\" disabled=\"true\"/>
							</td>
						  </tr>
					  </table></div><br/>
					  <input class=\"botao\" type=\"button\" name=\"btnVoltar\" id=\"btnVoltar\" value=\"<< Voltar\" disabled=\"true\" onclick=\"location.href= '".$dirTVSYNC."cadProgram.php'\"/>
					  <input class=\"botao\" type=\"submit\" name=\"btnEnviar\" id=\"btnEnviar\" value=\"Avançar >>\" disabled=\"true\" />
					
					";
				$nmCanal="";
				if(isset($_GET['canal'])){//canal setado
					$nmCanal = $_GET['canal'];		
					echo "<script>habilitaForm();</script>";
					}
				echo"
					<input type=\"text\" name=\"nmCanal\" hidden=\"true\" value=\"$nmCanal\"/>
					</form>
				";
				}else
					{//não existe canal cadastrado
						echo "<label class=\"aviso\">Não Existe nenhum canal cadastrado!<br/>
							  Por favor, cadastre um canal!</label><br/><br/>
							<input class=\"botao\" name=\"btnOk\" type=\"button\" value=\"Cadastrar\" onclick=\"location. href= '".$dirTVSYNC."CadCanal.php'\"/>
							";
					}
		}
	}
	mysql_close($conexao);




























 /* 	
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
		
		if ($db)
		{//conectado com o banco de dados
			$sqlSelect = mysql_query("SELECT nome FROM iv3h0_canais WHERE holder=\"$username\";")or die(mysql_error());//retorna canais do usuario logado
		
			if(mysql_num_rows($sqlSelect)> 0)//existe canal
			{
				$array=mysql_fetch_array($sqlSelect);
				$sqlSelect2= mysql_query("SELECT nome FROM iv3h0_programacoes WHERE holder=\"$username\" AND canal=\"".$array['nome']."\";");
//retorna programações	
					if(mysql_num_rows($sqlSelect2)>0)//existe programação neste canal
					{
						$array2= mysql_fetch_array($sqlSelect2);
						echo "
							<form name=\"upload\" enctype=\"multipart/form-data\" method=\"post\" action=\"getEnviarArq.php\">
							<table width=\"200\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							  <tr>
								<td colspan=\"2\"><label class=\"enunciado\">Escolha a programação:</label></td>
							  </tr>
							  <tr>
								<td><label class=\"nmCampo\">Programação: </label></td>
								<td><select name=\"prog\" id=\"canal\">
						";
				
						do //faz um looping e cria um array com os campos da consulta
						{
						  //mostra na tela o nome e a data de nascimento			  
						  echo "<optgroup label=\"".$array['nome']."\">";
						  
						  if($array2)
							{
							  echo "<option>".$array2['nome']."</option>";
							  $array2= mysql_fetch_array($sqlSelect2);
							}
						  
						  echo"</optgroup>";
						}while($array = mysql_fetch_array($sqlSelect));
						
						echo"</select></td>
								</tr>
								<tr>
								  <td colspan=\"2\"><input name=\"arquivo[]\" type=\"file\" multiple=\"multiple\"/></td>
								</tr>
								<tr>
									<td colspan=\"2\"><input type=\"submit\" name=\"enviar\" value=\"Enviar\" /></td>
								</tr>
							</table>						
							</form>
						";
					}else//não existe programação 
						{
							echo "
							<label class=\"aviso\">Não Existe nenhuma Programação cadastrada!<br/>
							Por favor, cadastre uma programação antes de enviar os arquivos!</label><br/>
							<input name=\"btnOk\" type=\"button\" value=\"Ok\" onclick=\"location. href= 'http://localhost/joomla/multi2sync/cadProgram.php'\"/>";
						};
			}else//não existe Canal
				{
					echo "
						<label class=\"aviso\">Não Existe nenhum canal cadastrado!<br/>
						Por favor, cadastre um canal antes de enviar os arquivos!</label><br/>
						<input name=\"btnOk\" type=\"button\" value=\"Ok\" onclick=\"location. href= 'http://localhost/joomla/multi2sync/cadCanal.php'\"/>";
					
				}
		}
	}
	mysql_close($conexao);*/
?>

</body>
</html>