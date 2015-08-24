<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
session_start("valores");
include('importJoomla.php'); 
?>
<script language="JavaScript"> 
function confirma(){ 
   if (confirm('Tem certeza que deseja alterar o nome deste(s) canal(is)?')){ 
      document.seuformulario.submit() 
   } 
   return false;
} 
function valida(form){
	if(form.nome.value == ""){
		alert('O nome deve ser preenchido!')
		return false;
		}
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
			if ($_POST['action']== 'Editar')
			{//editar canal
				echo "
					<form action=\"getCanaisEdit.php\" method=\"post\" onsubmit=\"return valida(this);\">
					<div class=\"table2CSS\">
					<table>
					  <tr>
						<td>Nome: </td>
						<td>Novo nome:</td>
					  </tr>
					  ";
					  for($i=0;$i < count($_POST['name']);$i++){
						
						echo"  
						  <tr>
							<td>".$_POST['name'][$i]."<input type=\"text\" name=\"oldName[]\" value=\"".$_POST['name'][$i]."\" hidden=\"true\"/></td>
							<td><input  class=\"camptxt\" type=\"text\" name=\"newName[]\" value=\"".$_POST['name'][$i]."\"/></td>
						  </tr>
						  ";
					  }
					  echo"
					</table></div><br/>
					<input class=\"botao\" name=\"voltar\" value=\"<< Voltar\" type=\"button\" onclick=\"return confirma()\"/>
					<input class=\"botao\" name=\"avancar\" value=\"Avançar >>\" type=\"submit\" onclick=\"return confirma()\"/>
					</form>
					
				";
			
			}else if($_POST['action'] == 'Excluir')
					{//deletar canal
					$flag=0;
					$str="";
					for($k=0;$k < count($_POST['name']);$k++)
					{	
						$result = mysql_query("SELECT * FROM iv30h_programacoes WHERE canal='".$_POST['name'][$k]."';");
						while($array = mysql_fetch_array($result)){//removendo pastas de programações referentes ao canal
								removeTree("uploads/".$array['nome']);
							}
							
						if(mysql_query("DELETE FROM iv30h_canais WHERE nome='".$_POST['name'][$k]."';"))//deletando canal do bd
						{
							removeChannelXML($_POST['name'][$k]);
							$flag++;
						}else { $str = $str.",".$_POST['name'][$k]; }
					}
					
					if($flag = $k)
					{
						echo"
							<label class=\"aviso\">Todas os canais selecionados foram excluídos com sucesso!</label><br/><br/>
							<input class=\"botao\" type=\"button\" name=\"novo\" value=\"Novo\" onclick=\"location.href='".$dirTVSYNC."CadCanal.php'\"/>
							<input class=\"botao\" name=\"voltar\" type=\"button\" value=\"Voltar\" onclick=\"location. href= '".$dirTVSYNC."canais.php'\"/>
						";
					}else
						{
							echo"
								<label class=\"aviso\">Somente os canais \"".$str."\" foram excluídos com sucesso!</label><br/><br/>
								<input class=\"botao\" type=\"button\" name=\"novo\" value=\"Novo\" onclick=\"location.href='".$dirTVSYNC."CadCanal.php'\"/>
								<input class=\"botao\" name=\"voltar\" type=\"button\" value=\"Voltar\" onclick=\"location. href= '".$dirTVSYNC."canais.php'\"/>
							";	
						}
					}
			}
				
	}
		
?>


</body>
</html>