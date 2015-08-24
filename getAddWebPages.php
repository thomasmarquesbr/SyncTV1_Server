<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php 
include('importjoomla.php');
?>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Enviando Arquivos</title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript">
	$(document).on('click', '#add', function(){
										var row = "<tr style=\"background-color:#b2b2b2;\">"
											row += "<td><input class=\"text\" name=\"nameMedia[]\" id=\"nameMedia\" /></td>";
											row += "<td><input class=\"text\" name=\"urlMedia[]\" id=\"urlMedia\" /></td>";
											row += "<td><input class=\"text\" name=\"offsetMedia[]\" id=\"offsetMedia\" /></td>";
											row += "<td><input class=\"text\" name=\"durMedia[]\" id=\"durMedia\" /></td>";
											row += "<td><button class=\"botao\" onclick=\"RemoveTableRow(this)\" type=\"button\">Remover</button></td>";
											row += "</tr>"  
									     $(this).closest('table').append(row);  
									});   
</script>
</head>

<body>
<?php
	$nameChannel = $_POST['nmCanal'];
	$nameProg = $_POST['prog'];
	
?>

<form action="regAddWebPages.php" method="post">
	
		<table>
			<tbody>
				<tr style="background-color:#1c1c1c; font-size:16px; color:#ffffff; font-weight:bold;">
					<td>Nome</td>
					<td>URL</td>
					<td>Offset</td>
					<td>Duração</td>
					<td style="background-color:#ffffff"></td>
				</tr>
				<tr style="background-color:#b2b2b2;">
					<td><input class="text" name="nameMedia[]" id="nameMedia" /></td>
					<td><input class="text" name="urlMedia[]" id="urlMedia" /></td>
					<td><input class="text" name="offsetMedia[]" id="offsetMedia" /></td>
					<td><input class="text" name="durMedia[]" id="durMedia" /></td>
					<td>
				      <button class="botao" onclick="RemoveTableRow(this)" type="button">Remover</button>
				    </td>
				</tr>
			</tbody>
			<tfoot>
			 <tr>
			   <td colspan="5" style="text-align: right;">
			     <button id="add" class="botao" type="button">Adicionar</button>
			   </td>
			 </tr>
			</tfoot>
		</table>
	<input class="botao" type="submit" name="action" value="Enviar" />
	<?php
		echo "<input type=\"text\" name=\"nameChannel\" value=\"".$nameChannel." \" hidden=\"true\"/>";
		echo "<input type=\"text\" name=\"nameProg\" value=\"".$nameProg."\" hidden=\"true\"/>";
	?>
</form>

</body>
</html>
