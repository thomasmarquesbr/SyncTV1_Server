<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
function confirma(){ 
   if (confirm('Tem certeza que deseja excluir o canal!')){
	  document.seuformulario.submit() 
	}
   return false;
} 
</script>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Canais</title>
</head>

<body>
<?php
	$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
		if ($conexao)
		{//conectado com o servidor
			$db= mysql_select_db($DBSelect,$conexao);
			
			if ($db)
			{//conectado com o banco de dados
				$sqlSelect = mysql_query("SELECT nome FROM iv30h_canais WHERE holder='$username'")or die(mysql_error());//retorna canais do usuario logado;
			}
			mysql_close($conexao);
		}
		
		if ($array=mysql_fetch_array($sqlSelect))
		{	
			echo"
				<form action=\"getCanais.php\" method=\"post\" >
				<div class=\"tableCSS\">
				<table>
				<tr>
					<td><input type=\"checkbox\" name=\"chkAll\" onclick=\"checkAll(this)\"/></td>
					<td>Nome</td>
				</tr>
				";
			do //faz um looping e cria um array com os campos da consulta
				{
				  //mostra na tela as opcoes do radiobutton			  
				  echo "
				  		<tr>
    						<td><input name=\"name[]\" type=\"checkbox\" value=\"".$array['nome']."\" /></td>
    						<td>".$array['nome']."</td>
  						</tr>";
				}while($array = mysql_fetch_array($sqlSelect));
			echo "
				</table></div><br/>
				<input class=\"botao\" name=\"action\" type=\"submit\" value=\"Editar\"/>
				<input class=\"botao\" name=\"action\" type=\"submit\" value=\"Excluir\" onclick=\"return confirma()\"/>
				<input class=\"botao\" name=\"novo\" type=\"button\" value=\"Novo\" onclick=\"location. href= '".$dirTVSYNC."CadCanal.php'\"/>
				
</form>

";
			
		}else
			{
				echo "
					<label class=\"aviso\">Não Existe nenhum Canal Cadastrado!</label><br/><br/>
					<input class=\"botao\" value=\"Cadastrar\" type=\"button\" name=\"cadastrar\" onclick=\"location. href= '".$dirTVSYNC."CadCanal.php'\"/>
				";
				echo "";
				
			}

?>  

</body>
</html>