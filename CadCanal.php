<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cadastrar Canal</title>
<script language="JavaScript">
function valida(form) {
	if (form.nmCanal.value == "") 
	{
		alert("O nome do canal não foi preenchido!");
		form.nmCanal.focus();
		return false;
	}
} 
</script>
<link rel="stylesheet" type="text/css" href="css/syncfiles.css" />
</head>

<body>


<div class="background">
    <form name="form1" action="getCadCanal.php" method="post" onsubmit="return valida(this);">  
    <div class="table2CSS">
    <table>
      <tr>
        <td colspan="2"><label class="enunciado">Entre com o nome do canal:</label></td>
        </tr>
      <tr>
        <td>Nome:</td>
        <td><input class="camptxt" name="nmCanal" type="text" /></td>
      </tr>
    </table>
    </div>
    <br/>
    <input class="botao" type="button" value="<< Voltar" name="voltar"/>
    <input class="botao" type="submit" name="action" value="Cadastrar"/>
    </form>
</div>

</body>
</html>