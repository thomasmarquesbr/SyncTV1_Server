<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php 
include('importjoomla.php');

function setString($string){
		$string =str_replace(" ","_",strtolower($string));
		return $string;
	}
function getTypeArq($string){
		$ext = strrchr($string, '.');
		switch ($ext){
			case ($ext==".jpg" || $ext==".jpeg" || $ext==".png" || $ext==".gif"):
				return "imagem";
				break;
			case ($ext==".mp3" || $ext==".aiff" || $ext==".aac"):
				return "audio";
				break;
			case ($ext==".mp4" || $ext==".mpeg4"):
				return "video";
				break;
			}	
		return null;
	}	

	
?>
<script type="text/javascript">
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
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Enviando Arquivos</title>

</head>

<body>
<?php
$prog= $_POST['prog'];
$diretorio = "uploads/".$prog;
$str="";

$conexao= @mysql_connect($servidor,$usuarioDB,$senhaDB)or die("<h3>A Conexão com o banco de dados falhou!</h3>");
	if ($conexao)
	{//conectado com o servidor
		$db= mysql_select_db($DBSelect,$conexao);
	}

$sql = mysql_query("SELECT * FROM iv30h_programacoes WHERE nome='".$_POST['prog']."'; ");
$array = mysql_fetch_array($sql);

if(($array['type'] == "synctv")&&($array['service'] != "VoD")){

		if (is_dir($diretorio))//verifica a existencia do diretorio
			
			{
				//echo "A Pasta Existe<br>";
				
		
					$arquivo = isset($_FILES['arquivo']) ? $_FILES['arquivo'] : FALSE;
					
					echo"
						<form action=\"regArq.php\" method=\"post\">
						<div class=\"table2CSS\">
						<table>
						  <tr>
							<td>Nome</td>
							<td>Offset</td>
							<td>Duração</td>
						  </tr>
					";
						for ($k = 0; $k < count($arquivo['name']); $k++)
							{
								$nmArquivo = setString($arquivo['name'][$k]);
								
								if(getTypeArq($nmArquivo)!= null)
								{	
									$destino = $diretorio."/".substr($nmArquivo, 0, -4);
									$url=$diretorio."/".$nmArquivo;
									mkdir($destino,0777);
									if ( (!file_exists($destino."/".$nmArquivo))&&(move_uploaded_file($arquivo['tmp_name'][$k], $destino."/".$nmArquivo)) )
										//verifica se ja existe arquivo com mesmo nome
										{

											
											if ($db)
											{//##########inserir tipo tbm
												
												$sqlInsert= mysql_query("INSERT INTO iv30h_arquivos (nome,url,programacao,tipo,holder) VALUES ('".$nmArquivo."','".$dirTVSYNC.$destino."','$prog','".getTypeArq($nmArquivo)."','$username');");
												
											//echo $destino;	
											
											echo"
												<tr>
													<td><label>$nmArquivo<input type=\"text\" name=\"file[]\" value=\"$nmArquivo\" hidden=\"enabled\"/></label></td>
													<td><input name=\"offset[]\" type=\"text\" onkeypress=\"return onlyDigit(event)\" /></td>
													<td><input name=\"dur[]\" type=\"text\" onkeypress=\"return onlyDigit(event)\" /></td>
													<input type=\"text\" name=\"type[]\" value=\"".getTypeArq($nmArquivo)."\" hidden=\"enabled\"/>
												 </tr>";
												
											}
											
											addMediaXML($prog,$nmArquivo,"","",getTypeArq($nmArquivo),$dirTVSYNC.$url,$array['service']);
											
										}
								}
								if(getTypeArq($nmArquivo)== null){
										echo"<label class=\"aviso\"> Não foi possível enviar o(s) arquivo(s) \"".$nmArquivo."\".<br/>Verifique se o tipo de arquivo é válido!</label>";
									}		
							}     
							
						if(count($arquivo['name']) > 0){
							echo"
									</table></div><br/>
									<input type=\"text\" name=\"prog\" value=\"".$prog."\" hidden=\"true\"/>
									<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location. href= '".$dirTVSYNC."files.php' \"/>
									<input class=\"botao\" type=\"submit\" name=\"enviar\" value=\"Avançar >>\"/>
									
									</form>							
								"; 
						}else{
							echo"
									</table></div><br/>
									<input type=\"text\" name=\"prog\" value=\"".$prog."\" hidden=\"true\"/>
									<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location. href= '".$dirTVSYNC."files.php' \"/>
									</form>							
								"; 
							}
			}
}else{
	
	if($array['service'] == "VoD"){
		if (is_dir($diretorio))//verifica a existencia do diretorio
			
			{
				//echo "A Pasta Existe<br>";
				
		
					$arquivo = isset($_FILES['arquivo']) ? $_FILES['arquivo'] : FALSE;
					
						for ($k = 0; $k < count($arquivo['name']); $k++)
							{
								$nmArquivo = setString($arquivo['name'][$k]);
								
								if(getTypeArq($nmArquivo)!= null)
								{	
									$destino = $diretorio."/".substr($nmArquivo, 0, -4);
									$url=$diretorio."/".$nmArquivo;
									mkdir($destino,0777);
									if ( (!file_exists($destino."/".$nmArquivo))&&(move_uploaded_file($arquivo['tmp_name'][$k], $destino."/".$nmArquivo)) )
										//verifica se ja existe arquivo com mesmo nome
										{

											
											if ($db)
											{//##########inserir tipo tbm
												
												$sqlInsert= mysql_query("INSERT INTO iv30h_arquivos (nome,url,programacao,tipo,holder) VALUES ('".$nmArquivo."','".$dirTVSYNC.$destino."','$prog','".getTypeArq($nmArquivo)."','$username');");
													
											}
											
											addMediaXML($prog,$nmArquivo,"","",getTypeArq($nmArquivo),$dirTVSYNC.$url,$array['service']);
											
										}
								}
								if(getTypeArq($nmArquivo)== null){
										echo"<label class=\"aviso\"> Não foi possível enviar o(s) arquivo(s) \"".$nmArquivo."\".<br/>Verifique se o tipo de arquivo é válido!</label>";
									}		
							}     
							
			}
	}
	
	
	if (is_dir($diretorio))//verifica a existencia do diretorio
			
			{		
					$arquivo = isset($_FILES['arquivo']) ? $_FILES['arquivo'] : FALSE;
					
					
						for ($k = 0; $k < count($arquivo['name']); $k++)
							{
								$nmArquivo = setString($arquivo['name'][$k]);
								$destino = $diretorio."/".$nmArquivo;
								
								if(getTypeArq($nmArquivo)!= null)
								{
									if ( (!file_exists($destino))&&(move_uploaded_file($arquivo['tmp_name'][$k], $destino)) )
										//verifica se ja existe arquivo com mesmo nome
										{
											if ($db)
											{//##########inserir tipo tbm
												
												$sqlInsert= mysql_query("INSERT INTO iv30h_arquivos (nome,url,programacao,tipo,holder) VALUES ('".$nmArquivo."','".$dirTVSYNC.$destino."','$prog','".getTypeArq($nmArquivo)."','$username');");
												
											}
																						
										}
								}
								if(getTypeArq($nmArquivo)== null){
										echo"<label class=\"aviso\"> Não foi possível enviar o(s) arquivo(s) \"".$nmArquivo."\".<br/>Verifique se o tipo de arquivo é válido!</label>";
									}		
							}     
							
						if(count($arquivo['name']) > 0){
							echo"
									<label class=\"aviso\">Mídias enviadas com sucesso!</label><br/><br/>
									<input type=\"text\" name=\"prog\" value=\"".$prog."\" hidden=\"true\"/>
									<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location. href= '".$dirTVSYNC."files.php' \"/>						
								"; 
						}else{
							echo"
									<label class=\"aviso\">Mídias enviadas com sucesso!</label><br/><br/>
									<input type=\"text\" name=\"prog\" value=\"".$prog."\" hidden=\"true\"/>
									<input class=\"botao\" type=\"button\" name=\"voltar\" value=\"<< Voltar\" onclick=\"location. href= '".$dirTVSYNC."files.php' \"/>						
								"; 
							}
			}
	
	
	}
	

mysql_close($conexao);
?>

</body>
</html>
