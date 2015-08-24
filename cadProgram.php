<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php	include('importJoomla.php'); ?>
<script language="JavaScript">
function valida(form) {
	if (form.nmProgram.value == "") 
	{
		alert("O nome do canal não foi preenchido!");
		form.nmProgram.focus();
		return false;
	}else{
	if (form.data.value == "") 
	{
		alert("A data não foi preenchida!");
		form.data.focus();
		return false;
	}else{
	if (form.hora.value == "") 
	{
		alert("A hora não foi preenchida!");
		form.hora.focus();
		return false;
	}}}
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
function habilitaForm(){
	document.getElementById("data").disabled = false;
	document.getElementById("hora").disabled = false;
	document.getElementById("nmProgram").disabled = false;
	document.getElementById("avancar").disabled = false;
	document.getElementById("voltar").disabled = false;
	document.getElementById("timezone").disabled = false;
	document.getElementById("dispon").disabled = false;
	document.getElementById("service").disabled = false;
	}
</script>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cadastrar Programação</title>
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
				echo "
					<form name=\"form1\" action=\"CadProgram.php\" method=\"get\" >
					<div class=\"table2CSS\">
					<table>
					  <tr>
						<td colspan=\"2\"><label class=\"enunciado\">Escolha o canal em que deseja incluir uma nova programação:</label></td>
					  </tr>
					  <tr>
						<td><label class=\"nmCampo\">Canal: </label></td>
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
				//echo " </select><input type=\"submit\" name=\"btnEnviar\" value=\"Selecionar\"/></form>";
				//echo"</select></form>";
				
				echo"
															</select></td>
							</tr>	
					</form>
				";
				
				echo "
						<form name=\"form\" action=\"getCadProgram.php\" method=\"post\" onsubmit=\"return valida(this);\">
					  	  
						  <tr>
							<td>Nome</td>
							<td><input name=\"nmProgram\" id=\"nmProgram\" type=\"text\" disabled=\"true\" /></td>
						  </tr>
						  <tr>
							<td><label>Data: </label></td>
							<td><input type=\"text\" name=\"data\" id=\"data\"  onchange=\"verifica_datas(this)\" OnKeyPress=\"formatar(this,'00/00/0000');return onlyDigit(event)\" maxlength=\"10\" disabled=\"true\" />(dd/mm/aaaa)</td>
						  </tr>
						  <tr>
							<td>Hora: </td>
							<td><input type=\"text\" name=\"hora\" id=\"hora\" onchange=\"verifica_horas(this)\" onkeypress=\"formatar(this,'00:00');return onlyDigit(event)\" maxlength=\"5\" disabled=\"true\" />(hh:mm)</td>
						  </tr>
						  <tr>
						  	<td>Serviço</td>
							<td>
								<select id=\"service\" name=\"service\" disabled=\"true\">
									<option value=\"linearTV\" label=\"linearTV\">linearTV</option>
									<option value=\"VoD\" label=\"VoD\">VoD</option>
									<option value=\"both\" label=\"Ambos\">Ambos</option>
								</select>
							</td>
						  </tr>
						  <tr>
						  	<td>Disponibilidade</td>
							<td><input type=\"text\" name=\"dispon\" id=\"dispon\"  onchange=\"verifica_horas(this)\" onkeypress=\"formatar(this,'00:00');return onlyDigit(event)\" maxlength=\"5\" disabled=\"true\" />(hh:mm)</td>
						  </tr>
						  <tr>
						  	<td>Fuso:</td>
							<td>
								<select name=\"zone\" id=\"timezone\" disabled=\"true\">
									<option value=\"0\" label=\"GMT/UTC (Europe)\">GMT/UTC (Europe)</option>
									<option value=\"1\" label=\"BRT - Brasília time (South America)\">BRT - Brasília time (South America)</option>
									<option value=\"2\"  label=\"AMT - Amazon Time (South America)\">AMT - Manaus (South America)</option>
								</select>
							</td>
						  </tr>
						  
					  </table></div><br/>
					  <input class=\"botao\" type=\"button\" name=\"voltar\" id=\"voltar\" value=\"<< Voltar\" disabled=\"true\" onclick=\"location.href= '".$dirTVSYNC."cadProgram.php'\"/>
					  <input class=\"botao\" type=\"submit\" name=\"avancar\" id=\"avancar\" value=\"Avançar >>\" disabled=\"true\" />
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
						echo "<label class=\"aviso\">Não Existe nenhum canal cadastrado!<br/>Por favor, cadastre um canal!</label><br/><br/>
							<input class=\"botao\" name=\"btnOk\" type=\"button\" value=\"Cadastrar\" onclick=\"location. href= '".$dirTVSYNC."CadCanal.php'\"/>";
					}
		}
	}
	mysql_close($conexao);

?>
<!--
<tr>
							  <td>Fuso</td>
							  <td>
								<select name=\"zone\" style=\"width:30em\" > 
									<optgroup value=\"eu_gmt\" label=\"-  (Europe)\">
										<option value=\"0\"  >GMT/UTC (all year)</option>
										<option value=\"136\"  >London (This time zone offset is used during winter in this location)</option>
										<option value=\"211\"  >Reykjavik (This time zone offset is used all year in this location)</option>
									</optgroup>
									<optgroup value=\"au_cdt\" label=\"ACDT - Australian Central Daylight Time (Australia)\">
										<option value=\"5\" >Adelaide (This time zone offset is used during summer in this location)</option>
									</optgroup>
									<optgroup value=\"sa_amt\" label=\"AMT - Amazon Time (South America)\">
										<option value=\"144\">Manaus (This time zone offset is used all year in this location)</option>
										<option value=\"479\" >Cuiabá (This time zone offset is used during winter in this location)</option>
									</optgroup>
									<optgroup value=\"sa_brst\" label=\"BRST - Brasilia Summer Time (South America)\">
										<option value=\"233\" >São Paulo (This time zone offset is used during summer in this location)</option>
									</optgroup>
									<optgroup value=\"sa_brt\" label=\"BRT - Brasília time (South America)\">
										<option value=\"222\">Salvador (This time zone offset is used all year in this location)</option>
										<option value=\"233\">São Paulo (This time zone offset is used during winter in this location)</option>
									</optgroup>
								</select></td>
						  </tr>
                          -->
</body>
</html>