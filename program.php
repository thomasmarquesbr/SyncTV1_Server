<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('importJoomla.php');
function setSel($value){
			if((isset($_POST['zone']))&&($value==($_POST['zone']))){
					echo "SELECTED";
					}			
		}
 ?>
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
   if (confirm('Tem certeza que deseja excluir?\n\n(Lembrando que todos os arquivos referentes à  cada programação tambem serão excluídos!)')){ 
      document.seuformulario.submit() 
   } 
   return false;
} 
</script>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
<head>
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
			$sqlSelect = mysql_query("SELECT * FROM iv30h_programacoes WHERE holder='$username'")or die(mysql_error());//retorna programaçoes do usuario logado;
		}
		mysql_close($conexao);
	}
	
	if ($array=mysql_fetch_array($sqlSelect))
		{	
		echo"
			Fuso Horário: 
			<form name=\"form1\" method=\"post\" action=\"program.php\">
				<select name=\"zone\" id=\"timezone\" onchange=\"this.form.submit();\">
					<option value=\"0\" ";setSel(0); echo " label=\"GMT/UTC (Europe)\">GMT/UTC (Europe)</option>
					<option value=\"1\" ";setSel(1); echo " label=\"BRT - Brasília time (South America)\">BRT - Brasília time (South America)</option>
					<option value=\"2\" ";setSel(2); echo " label=\"AMT - Amazon Time (South America)\">AMT - Manaus (South America)</option>
				</select>
			</form>
			
			<form id=\"form2\" name=\"form2\" method=\"post\" action=\"getProgram.php\">
			<div class=\"tableCSS\">
			<table>
			  <tr>
				<td><input type=\"checkbox\" name=\"chkAll\" onclick=\"checkAll(this)\"/></td>
				<td>Nome</td>
				<td>Data</td>
				<td>Hora</td>
				<td>Canal</td>
				<td>Serviço</td>
				<td>Disponibilidade</td>
			  </tr>
				 ";
			do //faz um looping e cria um array com os campos da consulta
				{//mostra na tela as opcoes do radiobutton
					if(isset($_POST['zone'])){
							$dthr =  convertZone($_POST['zone'],$array['data'],$array['hora'],"utcToZone");
							$dividir = explode(" ", $dthr);
							$parte1 = $dividir[0]; $parte2 = $dividir[1];
							$data= $parte1;
							$hora= $parte2;
							$zone= $_POST['zone'];
						}else{
							$data= $array['data'];
							$hora= $array['hora'];
							$zone=0;
							}
						  
					echo"
					<tr>
						<td><input type=\"checkbox\" name=\"name[]\" value=\"".$array['nome']."\" /></td>
						<td>".$array['nome']."</td>
						<td>".$data."<input type=\"text\" name=\"data[]\" value=\"".$data."\" hidden=\"true\"/></td>
						<td>".$hora."<input type=\"text\" name=\"hora[]\" value=\"".$hora."\" hidden=\"true\"/></td>
						<td>".$array['canal']."<input type=\"text\" name=\"canal[]\" value=\"".$array['canal']."\" hidden=\"true\"/></td>";
						if($array['service'] == 'both'){
							echo"<td>LinearTV, VoD<input type=\"text\" name=\"service[]\" value=\"".$array['service']."\" hidden=\"true\" /></td>";
						}else{
							echo"<td>".$array['service']."<input type=\"text\" name=\"service[]\" value=\"".$array['service']."\" hidden=\"true\" /></td>";
						}
						echo "<td>".$array['dispon']."<input type=\"text\" name=\"dispon[]\" value=\"".$array['dispon']."\" hidden=\"true\"/></td>
				  	</tr>
					";
				}while($array= mysql_fetch_array($sqlSelect));
			echo"
				</table></div><br/>
				<input class=\"botao\" name=\"action\" type=\"submit\" value=\"Editar\" />
				<input class=\"botao\" name=\"action\" type=\"submit\" value=\"Excluir\" onclick=\"return confirma();\"/>
				<input class=\"botao\" name=\"nova\" type=\"button\" value=\"Nova\" onclick=\"location. href= '".$dirTVSYNC."cadProgram.php'\"/>
				<input name=\"zone\" type=\"text\" value=\"".$zone."\" hidden=\"enabled\"/>
				</form>
			";
			
		}else
			{
				echo "
					<label class=\"aviso\">Não existe nenhuma programação cadastrada!</label><br/><br/>
					<input class=\"botao\" value=\"Cadastrar\" type=\"button\" name=\"cadastrar\" onclick=\"location. href= '".$dirTVSYNC."cadProgram.php'\"/>
					";
				
			}
	
		
?>
</body>
</html>