<?php

$path = $_POST["path"];
$folderName = $_POST["folderName"];
$currentUrl = $_POST["currentUrl"];


$allFolders = scandir($path);


if (in_array($folderName, $allFolders)) {
    header("LOCATION:".$currentUrl);
}else{
    mkdir($path."/".$folderName, 0700);
    header("LOCATION:".$currentUrl);
}
