<?php
//---------------------importa valores de usuario logado no joomla-------------------------
define('_JEXEC', 1 );
define('JPATH_BASE', '../' ); // poderia ser /var/www/html/site-joomla/
define('DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
$mainframe = JFactory::getApplication('site');
$user   =  JFActory::getUser();
$username = $user->username;
$urlMulti="localhost/joomla/multi2sync/";

//----------------------------------------DEFINES----------------------------------------
$dirTVSYNC="http://localhost/synctv/syncfiles/";
$urlArquivos="http://localhost/synctv/syncfiles/uploads/";
$servidor="localhost";
$usuarioDB="root";
$senhaDB="";
$DBSelect="synctvdb";

//--------------------------------------Define DYLD para uso do FFMPEG------------------------
exec('unset DYLD_LIBRARY_PATH ;');
putenv('DYLD_LIBRARY_PATH');
putenv('DYLD_LIBRARY_PATH=/usr/bin');


//----------------------------------------FUNÇÕES----------------------------------------
function formataData($data, $tipo = 1) {//converte data do formato padrão (aaaa-mm-dd) europeu para brasileiro (dd/mm/aaa)
	$data = str_replace('-', '/', $data);
	$dividir = explode("/", $data);
	$parte1 = $dividir[0]; $parte2 = $dividir[1]; $parte3 = $dividir[2];
	$data = "$parte3-$parte2-$parte1"; 
	if ($tipo == 1) $data = str_replace('-', '/', $data);
	return $data;
}
function removeTree($rootDir)
{
    if (!is_dir($rootDir))
    {
        return false;
    }

    if (!preg_match("/\\/$/", $rootDir))
    {
        $rootDir .= '/';
    }


    $stack = array($rootDir);

    while (count($stack) > 0)
    {
        $hasDir = false;
        $dir    = end($stack);
        $dh     = opendir($dir);

        while (($file = readdir($dh)) !== false)
        {
            if ($file == '.'  ||  $file == '..')
            {
                continue;
            }

            if (is_dir($dir . $file))
            {
                $hasDir = true;
                array_push($stack, $dir . $file . '/');
            }

            else if (is_file($dir . $file))
            {
                unlink($dir . $file);
            }
        }

        closedir($dh);

        if ($hasDir == false)
        {
            array_pop($stack);
            rmdir($dir);
        }
    }

    return true;
}



//---------------------------------XML SYNCTV---------------------------------------------

function addChannelXML($channel){
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->load('synctv.xml');
		
		$canalElem = $doc->createElement('channel');
		$canalElem->setAttribute("name", $channel);
		
		
		$keys = $doc->getElementsByTagName('synctv');
		$syncTVElem = $keys->item(0);
		$syncTVElem->appendChild($canalElem);
		
		$doc->save('synctv.xml');
	}
function removeChannelXML($channel){
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->load('synctv.xml');
				
		$keys = $doc->getElementsByTagName('synctv');
		$syncTVElem = $keys->item(0);
		
		$channels = $syncTVElem->getElementsByTagName('channel');
		
		foreach($channels as $channelElem){
				if ($channelElem->getAttribute('name') == $channel){//encontrou elemento
						$syncTVElem->removeChild($channelElem);
					}
			}
			
		$doc->save('synctv.xml');
	}
function addProgrammingXML($channel, $prog, $date, $hour, $url, $dispon,$type,$service){
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->load('synctv.xml');
				
		$keys = $doc->getElementsByTagName('synctv');
		$syncTVElem = $keys->item(0);
		
		$channels = $syncTVElem->getElementsByTagName('channel');
		
		foreach($channels as $channelElem){
				if ($channelElem->getAttribute('name') == $channel){//encontrou elemento
						$progElem = $doc->createElement('programming');
						$progElem->setAttribute("name", $prog);
						$progElem->setAttribute("date", $date);
						$progElem->setAttribute("hour", $hour);
						$progElem->setAttribute("available",$dispon);
						$progElem->setAttribute("url", $url);
						$progElem->setAttribute("type",$type);
						$progElem->setAttribute("service",$service);
						
						$channelElem->appendChild($progElem);
					}
			}
		if ($type == "synctv"){
			createProgrammingXML($prog,$date,$hour,$url,$dispon,$type,$service);
		}
		$doc->save('synctv.xml');
	}
function removeProgXML($channel,$prog){
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->load('synctv.xml');
				
		$keys = $doc->getElementsByTagName('synctv');
		$syncTVElem = $keys->item(0);
		
		$channels = $syncTVElem->getElementsByTagName('channel');
		
		foreach($channels as $channelElem){
				if ($channelElem->getAttribute('name') == $channel){//encontrou o canal
						
						$progs = $channelElem->getElementsByTagName('programming');
						foreach($progs as $progElem){
								if($progElem->getAttribute('name') == $prog){//encontrou a programação
										$channelElem->removeChild($progElem);
									}
							}
						
						
					}
			}

		$doc->save('synctv.xml');
	}
function replaceChannelXML($channel, $newName){
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->load('synctv.xml');
				
		$keys = $doc->getElementsByTagName('synctv');
		$syncTVElem = $keys->item(0);
		
		$channels = $syncTVElem->getElementsByTagName('channel');
		
		foreach($channels as $channelElem){
				if ($channelElem->getAttribute('name') == $channel){//encontrou elemento
						$channelElem->setAttribute("name", $newName);;
					}
			}
			
		$doc->save('synctv.xml');
		
	}
function replaceProgXML($channel,$prog,$date,$hour,$dispon,$service){
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->load('synctv.xml');
				
		$keys = $doc->getElementsByTagName('synctv');
		$syncTVElem = $keys->item(0);
		
		$channels = $syncTVElem->getElementsByTagName('channel');
		
		foreach($channels as $channelElem){
				if ($channelElem->getAttribute('name') == $channel){//encontrou o canal
						
						$progs = $channelElem->getElementsByTagName('programming');
						foreach($progs as $progElem){
								if($progElem->getAttribute('name') == $prog){//encontrou a programação
										$progElem->setAttribute('date',$date);
										$progElem->setAttribute('hour',$hour);
										$progElem->setAttribute('available',$dispon);
										$progElem->setAttribute('service',$service);
										$type = $progElem->getAttribute('type');
									}
							}						
					}
			}
		if ($type == "synctv"){
			replaceMediaXMLProg($prog,$date,$hour,$dispon,$service);
		}	
		$doc->save('synctv.xml');
	}
//---------------------------------XML Programming----------------------------------------	
function createProgrammingXML($prog,$date,$hour,$url,$dispon,$type,$service){ //cria arquivo xml da programação dentro de sua respectiva pasta
		$doc = new DOMDocument('1.0', 'utf-8');
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
	
		$progElem = $doc->createElement('programming');
		$progElem->setAttribute('name', $prog);
		$progElem->setAttribute('date', $date);
		$progElem->setAttribute('hour', $hour);
		$progElem->setAttribute('available',$dispon);
		$progElem->setAttribute('url', $url);
		$progElem->setAttribute("type",$type);
		$progElem->setAttribute("service",$service);

		$doc->appendChild($progElem);
		$doc->save('uploads/'.$prog.'/'.$prog.'.xml');
	}
function replaceMediaXMLProg($prog,$date,$hour,$dispon,$service){//atualiza atributos do elemento programming do arquivo xml 
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->load('uploads/'.$prog.'/'.$prog.'.xml');
				
		$keys = $doc->getElementsByTagName('programming');
		$progElem= $keys->item(0);
		
		$progElem->setAttribute('date', $date);
		$progElem->setAttribute('hour', $hour);
		$progElem->setAttribute('available', $dispon);
		$progElem->setAttribute('service',$service);
		
		$doc->save('uploads/'.$prog.'/'.$prog.'.xml');
	}
function addMediaXML($prog,$fileName,$offset,$dur,$type,$url,$service){
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->load('uploads/'.$prog.'/'.$prog.'.xml');
			
		$fileElem = $doc->createElement('media');
		$fileElem->setAttribute("name", $fileName);
		if($service != "VoD"){
			$fileElem->setAttribute("offset", $offset);
			$fileElem->setAttribute("dur", $dur);
		}
		
		$fileElem->setAttribute("type", $type);
		if($type != 'video')
			$fileElem->setAttribute("url", substr($url,0,-4)."/".$fileName);
		
		if($type == 'video'){
			//####################CONVERTENDO VIDEOS###################
			$source = substr($url,0,-4)."/".$fileName;
			$pathSyncFiles = "/Applications/XAMPP/xamppfiles/htdocs/synctv/syncfiles";
			$destiny = $pathSyncFiles."/uploads/".$prog."/".substr($fileName, 0,strrpos($fileName, "."));
			//echo $source."  >>  ".$destiny;
			exec("./convert.sh ".$source." ".$destiny." > /dev/null 2> /dev/null");
			//###########################################
			
			$arrayResolution= array("240p","360p","480p","720p","1080p");
			foreach ($arrayResolution as $key => $valueRes) {
				$propertiesElem= $doc->createElement('properties');
				
				$propertiesElem->setAttribute("resolution", $valueRes);
				$propertiesElem->setAttribute("url",substr($url, 0,strrpos($url, "."))."/".$valueRes.".mp4");
				
				$fileElem->appendChild($propertiesElem);
			}
		}
		
		

		
		$keys = $doc->getElementsByTagName('programming');
		
		$progElem = $keys->item(0);
		$progElem->appendChild($fileElem);
		$doc->save('uploads/'.$prog.'/'.$prog.'.xml');
	}

function replaceAtribMediaXMLProg($prog,$fileName, $offset, $dur, $type){
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->load('uploads/'.$prog.'/'.$prog.'.xml');
				
		$keys = $doc->getElementsByTagName('programming');
		$progElem = $keys->item(0);
		
		$files = $progElem->getElementsByTagName('media');
		
		foreach($files as $fileElem){
				if ($fileElem->getAttribute('name') == $fileName){//encontrou elemento
						$fileElem->setAttribute("offset", $offset);
						$fileElem->setAttribute("dur", $dur);
						$fileElem->setAttribute("type", $type);
					}
			}
			
		$doc->save('uploads/'.$prog.'/'.$prog.'.xml');
	}

function removeMediaXMLProg($prog,$fileName){
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->load('uploads/'.$prog.'/'.$prog.'.xml');
				
		$keys = $doc->getElementsByTagName('programming');
		$progElem = $keys->item(0);
		
		$files = $progElem->getElementsByTagName('media');
		
		foreach($files as $fileElem){
				if ($fileElem->getAttribute('name') == $fileName){//encontrou elemento
						$progElem->removeChild($fileElem);
					}
			}
			
		$doc->save('uploads/'.$prog.'/'.$prog.'.xml');	
	}

function convertZone($i,$date,$hour,$mode){
	$listZones = array(array("UTC","0","GMT/UTC (Europe)"),
						array("BRT","-3","BRT - Brasília time (South America)"),
						array("AMT","-4","AMT - Manaus (South America)"));
				
	switch($mode){
		case "zoneToUtc"://converte hora do fuso horario para o padrão UTC
//				echo $hour." ".-$listZones[$i][1]." hour <br/>";
//				$str= $hour." ".-$listZones[$i][1]." hour";
//				echo date("d/m/Y H:i", strtotime($str));



				
				//$date1 = new DateTime('2013-08-31 23:00');
				$date1 = new DateTime(formataData($date,2)." ".$hour);
				$date1->modify('+'.-$listZones[$i][1].' hours');
				return $date1->format('d/m/Y H:i');
				
				break;
		case "utcToZone"://converte hora padrão UTC para o fuso horário selecionado
				//$str= $hour." ".$listZones[$i][1]." hour";
				//echo date("d/m/Y H:i", strtotime($str));
				$date1 = new DateTime(formataData($date,2)." ".$hour);
				$date1->modify('+'.$listZones[$i][1].' hours');
				return $date1->format('d/m/Y H:i');
				break;
		
		}
		
		
		
	}

	


?>

