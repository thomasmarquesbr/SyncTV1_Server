<?php 
	session_start("timezone");
	
	
	
	$offset= $_SESSION['offset']*3600;
	echo $horaServidor = gmdate("M d Y",time()+($offset))."<br/>".gmdate("H:i",time()+($offset))."<br/>";
	
	//$date1 = new DateTime('2013-08-31 23:00');   
	//echo $date1->format('d/m/Y H:i')."<br/>";  
	    
	//$date1->modify('+3 hours');  
	//echo $date1->format('d/m/Y H:i');  
	
	echo "<meta HTTP-EQUIV='refresh' CONTENT='60;URL=clock.php'>";
	
	
		
?>