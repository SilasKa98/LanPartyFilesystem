<?php

$path = $_POST["path"];
$fileName = $_POST["fileName"];
$currentUrl = $_POST["currentUrl"];
$type = $_POST["type"];


if($type== "file"){
    unlink($path."/".$fileName);
    header("LOCATION:".$currentUrl);
}


if($type == "folder"){

    function delete_files($target) {	
		if(is_dir($target)){
			$files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

			foreach( $files as $file ){
				delete_files( $file );      
			}

			rmdir( $target );
		} 	
		elseif(is_file($target)) {
			unlink( $target );  
		}
	}
    delete_files($path."/".$fileName);

    header("LOCATION:".$currentUrl);
    
}
