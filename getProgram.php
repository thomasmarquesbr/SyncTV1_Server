<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
include('importjoomla.php');
?>
<script language="JavaScript"> 
function valida(form) {
	if ((form.nome.value == "")&&(form.data.value == "")&&(form.hora.value == ""))
	{
		alert("Nenhum campo foi preenchido");
		form.nome.focus();
		return false;
	}
}
function confirma(){ 
   if (confirm('Tem certeza que deseja alterar os dados desta programação?')){ 
      document.form.submit(); 
   } 
   return false;
} 
function formatar(src, mask) {
		var i = src.value.length;
		var saida = mask.substring(i,i+1);
		var ascii = event.keyCode;
		if (saida == "A") {
				if ((ascii >=97) && (ascii <= 122)) { event.keyCode -= 32; }
				else { event.keyCode = 0; }
		} else if (saida == "0") {
				if ((ascii >= 48) && (ascii <= 57)) { return }
				else { event.keyCode = 0 }
		} else if (saida == "#") {
				return;
		} else {
				src.value += saida;
				
		   
				if (saida == "A") {
						if ((ascii >=97) && (ascii <= 122)) { event.keyCode -= 32; }
				} else { return; }
		}		
	
}	
function verifica_datas(obj){
    if(obj.value.length < 10)
        obj.value = '';
    else{

        dia = parseInt(obj.value.substring(0,2));
        mes = parseInt(obj.value.substring(3,5));
        ano = parseInt(obj.value.substring(6,10));

		
		
        if((dia < 0 || dia > 31) || (mes < 0 || mes > 12) || (ano < 2013 || ano > 2020)){
            obj.value = '';
            alert('Data inválida!\n dia - 00 à 31 \n Mes - 01 à 12 \n Ano - 2013 à 2020');
        }
    }
}
function verifica_horas(obj){
    if(obj.value.length < 5)
        obj.value = '';
    else{

        hr = parseInt(obj.value.substring(0,2));
        mi = parseInt(obj.value.substring(3,5));
       // se = parseInt(obj.value.substring(6,8));

        if((hr < 0 || hr > 23) || (mi < 0 || mi > 60)){
            obj.value = '';
            alert('Hora inválida!\n Hora - 00 à 23 \n Minuto - 00 à 59');
        }
    }
}	 
function onlyDigit(e) {
  var unicode = e.charCode ? e.charCode : e.keyCode;
  if (unicode != 8 && unicode != 9) {
   if (unicode<48||unicode>57) {
    return false
   }
  }
 }
</script>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Editar Programação</title>
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
		
	}
			if ($_POST['action'] == 'Editar')
			{
				echo"					
					<form name=\"form\" action=\"getProgamEdit.php\" method=\"post\" onsubmit=\"return confirma();\">
					<div class=\"table2CSS\">
					<table>
					<tr>
					 <td>Nome</td>
					 <td>Data</td>
					 <td>Hora</td>
					 <td>Serviço</td>
					 <td>Disponibilidade</td>
					</tr>
				";
				for($k=0;$k < count($_POST['name']);$k++){
					
					echo"
						<tr>
						<td>".$_POST['name'][$k]."<input type=\"text\" name=\"name[]\" value=\"".$_POST['name'][$k]."\" hidden=\"true\"/></td>
						<td><input type=\"text\" name=\"data[]\" value=\"".$_POST['data'][$k]."\" onchange=\"verifica_datas(this)\" OnKeyPress=\"formatar(this,'00/00/0000');return onlyDigit(event)\" maxlength=\"10\" style=\"width:80px;\" /></td>
						<td><input type=\"text\" name=\"hora[]\" value=\"".$_POST['hora'][$k]."\" onchange=\"verifica_horas(this)\" onkeypress=\"formatar(this,'00:00');return onlyDigit(event)\" maxlength=\"5\" style=\"width:80px;\" /></td>
						<td>
							<select id=\"service\" name=\"service[]\">
								<option value=\"linearTV\" label=\"linearTV\""; if($_POST['service'][$k] == 'linearTV'){ echo " SELECTED";} echo">LinearTV</option>";
								echo"<option value=\"VoD\" label=\"VoD\""; if($_POST['service'][$k] == 'VoD'){ echo " SELECTED";} echo">VoD</option>";
								echo"<option value=\"both\" label=\"Ambos\""; if($_POST['service'][$k] == 'both'){ echo " SELECTED";} echo">Ambos</option>;";
							echo"</select>
						</td>
						<td><input type=\"text\" name=\"dispon[]\" value=\"".$_POST['dispon'][$k]."\" onchange=\"verifica_horas(this)\" onkeypress=\"formatar(this,'00:00');return onlyDigit(event)\" maxlength=\"5\"/></td>
						</tr>
						<input type=\"text\" name=\"canal[]\" value=\"".$_POST['canal'][$k]."\" hidden=\"true\"/>
						<input name=\"zone\" type=\"text\" value=\"".$_POST['zone']."\" hidden=\"enabled\"/>
					";	
					
				}
				echo"
					</table></div><br/>
					<input class=\"botao\" type=\"button\" name=\"btnVoltar\" value=\"<< Voltar\" onclick=\"location.href='".$dirTVSYNC."program.php'\"/>
					<input class=\"botao\" type=\"submit\" name=\"action\" value=\"Avançar >>\" />
					</form>
				";
				
				
			}else
				if ($_POST['action'] == 'Excluir')
				{
					$flag=0;
					$str="";
					for($k=0;$k < count($_POST['name']);$k++)
					{
						
						if(mysql_query("DELETE FROM iv30h_programacoes WHERE nome='".$_POST['name'][$k]."';"))
						{	
							removeTree("uploads/".$_POST['name'][$k]);
							
							removeProgXML($_POST['canal'][$k],$_POST['name'][$k]);
							$flag++;
						}else { echo "sim"; $str = $str.$_POST['name'][$k].","; }
					}
					mysql_close($conexao);
					if($flag == $k)
					{
						echo"
							<label class=\"aviso\">Todas as programações selecionadas foram excluidas com sucesso!</label><br/><br/>
							<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location.href='".$dirTVSYNC."program.php'\"/>
						";
					}else
						{
							echo"
								<label class=\"aviso\">Somente os arquivos \"".$str."\" foram excluidos com sucesso!</label><br/><br/>
								<input  class=\"botao\" type=\"button\" name=\"btnOk\" value=\"Ok\" onclick=\"location.href='".$dirTVSYNC."program.php'\"/>
							";	
						}
				}
			
	
	
?>
</body>
</html>