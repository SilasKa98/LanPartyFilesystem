<?php

$files = $_FILES["files"]["name"];
$path = $_POST["path"];

$finalpath = $path."/";
$currentUrl = $_POST["currentUrl"];
    $cnt=array();
	$cnt=count($files);
	for($i=0;$i<$cnt;$i++){
        $fileName = $_FILES['files']['name'][$i];
        if (($fileName !="")){
            // Where the file is going to be stored
            $target_dir = $finalpath;
            $file = $_FILES['files']['name'][$i];
            $path = pathinfo($file);
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $_FILES['files']['tmp_name'][$i];
            $path_filename_ext = $target_dir.$filename.".".$ext;
            
            // Check if file already exists
            if (file_exists($path_filename_ext)) {
                echo "Es existiert bereits eine Datei mit diesem Name.";
            }else{
                move_uploaded_file($temp_name,$path_filename_ext);
            }
        }
    }
    header("LOCATION:".$currentUrl);

















?>
