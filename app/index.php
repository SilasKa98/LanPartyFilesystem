
<html>

    <head>
        <style>
            
            body{
                background-color: rgb(53, 54, 58);
                color:white !important;
            }

            img[src*="../media"]{
                filter: invert(96%) sepia(0%) saturate(18%) hue-rotate(269deg) brightness(93%) contrast(92%);
            }
            #content{
                width: 1440px;
                height: auto;
                margin-left: auto;
                margin-right: auto;
            }

            #uploadWrapper{
                width: 60%;
                height: 20%;
                border: 2px dashed black;
                margin: 0 auto;
            }

            #uploadWrapper span{
                font-size: 20pt;
                font-family: monospace;
                opacity: 0.6;
                top: 65px;
                position: relative;
                left: 36%;
            }

            #uploadFile{
                width: 100%;
                height: 100%;        
                cursor: pointer;
                opacity: 0;
            }

            #directoryDisplay{
                margin-top: 3%;
            }


            .directoryElement{
                float:left;
                margin-right: 5%;
                width: 70px;
                height: 144px;
                border: 1px solid white;
                border-radius: 10px;
                padding-top: 1%;
                padding-right: 1%;
                padding-left: 1%;
                overflow: hidden;
                margin-top: 2%;
                margin-bottom: 2%;
            }

            .directoryElementFolder{
                float:left;
                margin-right: 2%;
                width: 178px;
                height: 92px;
                border: 1px solid white;
                border-radius: 10px;
                padding-right: 1%;
                padding-left: 1%;
                overflow: hidden;
                margin-top: 2%;
                margin-bottom: 2%;
            }

            .directoryElementFolder img{
                width: 50px;
                height: 50px;
                margin-left: 36%;
                
            }

            .directoryElementFolder p{
                text-align: center;
                font-size: 14pt;
                margin: 0;
            }

            .directoryElement img{
                width: 70px;
                height: 70px;
                cursor: pointer;
            }

            .directoryElement p{
                text-align: center;
                font-size: 10pt;
                margin: 0;
                overflow-wrap: break-word;
            }

            h1{
                font-family: sans-serif;
                padding: 2%;
            }

            #noContent{
                text-align: center;
                margin-top: 10%;
                font-size: 17pt;
                font-family: sans-serif;
            }

            #folder, #file{
                float: left;
                font-family: sans-serif;
                width: 100%;
            }

            .currentDirectory{
                color: white;
                text-decoration: none;
                font-size: 14pt;
                font-family: sans-serif;
            }  

            .currentDirectory:last-of-type{
                color: #76ad76;
                font-size: 18pt;
            }

            #startDirectory{
                color: white;
                text-decoration: none;
                font-size: 14pt;
                font-family: sans-serif;
            }

            .currentDirectory:hover{
                background-color: 	#DCDCDC;
                padding: 5px;
                border-radius: 7px;
                
            }
            

            #folder p, #file p{
                font-family: sans-serif;
            }

            .titel{
                color: lightgrey;
                margin: 0;
            }

            #leftSidebar{
                width: 50px;
                float: left;
                margin-top: 20%;
                position: fixed;
                cursor: pointer;
            }
            

            .modal {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                padding-top: 20%; /* Location of the box */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                }

            /* Modal Content */
            .modal-content {
                background-color: #fefefe;
                margin: auto;
                padding: 20px;
                border: 1px solid #888;
                width: 16%;
                height: 14%;
                color: black;
            }

        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-content button{
            float: right;
            margin-top: 5%;
            margin-left: 2%;
            background-color: #008CBA;
            border: none;
            color: white;
            padding: 8px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .modal-content button:hover{
            border: 1px solid black;
        }

        .modal-content input[type="text"]{
            width: 100%;
            font-size: 12pt;
            border: 1px solid #969696;
            border-radius: 3px;
        }

        .modal-content p{
            font-family: sans-serif;
            font-size: 14pt;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            overflow: auto;
            z-index: 1;
            border-radius: 3px;
        }

        .dropdown-content a {
            color: black;
            padding: 6px 5px;
            text-decoration: none;
            display: block;
            font-size: 10pt;
        }

        .dropdown a:hover {
            background-color: grey;
            cursor: pointer;
        }

        .show {
            display: block;
        }
        </style>

        <script>
        //disable contextMenu (rightClick Menu)
       document.addEventListener('contextmenu', event => event.preventDefault());


            function changeText(x){
                var fileSize = x.files[0].size;
                for(let i=1;i<x.files.length;i++){
                     fileSize += x.files[i].size;
                }
               
                var fileSizeMb = (fileSize/1024/1024).toFixed(2) + "Mb";           
                //für uploading erstezen --> x.files[0].name
                x.parentNode.previousElementSibling.innerHTML = "uploading...";
                x.parentNode.previousElementSibling.innerHTML += fileSizeMb;
                x.parentNode.previousElementSibling.previousElementSibling.style.display = "none";
                x.form.submit();
            }

            function highlightField(){
                document.getElementById("uploadWrapper").style.border = "2px dashed #ADD8E6";
            }
            function normalizeField(){
                document.getElementById("uploadWrapper").style.border = "2px dashed black";
            }
        </script>

    </head>

    
    <body ondragover="highlightField();" ondragleave="normalizeField();">

    <?php
        $cntPath = substr_count($_SERVER['REQUEST_URI'], "Pfad");
        if(isset($_GET["Pfad0"])){
            $path = "../mainStorage/";
            for($i=0;$i<$cntPath;$i++){
                $pfadName = "Pfad".$i;
                $path .= $_GET[$pfadName]."/";
            }
        }else{
            $path = "../mainStorage";
        }              
    ?>

    <div id='leftSidebar'>
            <img src='../media/folder-plus.svg' id="myBtn" width=30px height=30px>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Neuer Ordner</p>
            <form action="createFolder.php" method='post'>
                <input type="text" pattern="[^|,/:?*\\]+" value="unbenannter Ordner" name="folderName" required><br>
                <?php echo'<input type="hidden" name="path" value="'.$path.'">' ?>
                <?php echo '<input type="hidden" value="'.$_SERVER['REQUEST_URI'].'" name="currentUrl">'; ?>
                <button onclick="this.form.submit();">Erstellen</button><button id="cancelNewFolder">Abbrechen</button>
            </form>
        </div>
    </div>

    <script>

        var modal = document.getElementById("myModal");
        var btn = document.getElementById("myBtn");
        var span = document.getElementsByClassName("close")[0];
        var abbrechen = document.getElementById("cancelNewFolder");

        btn.onclick = function() {
        modal.style.display = "block";
        }

        abbrechen.onclick = function() {
        modal.style.display = "none";
        }

        span.onclick = function() {
        modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
</script>
        <div id="content">
        <center><h1>Lan Cloud</h1></center>
        <div id="uploadWrapper">
                <span>Drop files here</span>
                <span></span>
                <form method="post" action="uploadFiles.php" enctype="multipart/form-data">
                    <input id="uploadFile" type="file" onchange="changeText(this);" name="files[]" id="file" multiple>
                    <?php echo '<input type="hidden" value="'.$path.'" name="path">'; ?>
                    <?php echo '<input type="hidden" value="'.$_SERVER['REQUEST_URI'].'" name="currentUrl">'; ?>
                </from>
            </div> 
            <div id="directoryDisplay">
               
                <?php 

                //navigation 
                if(isset($_GET["Pfad0"])){
                    for($i=0;$i<$cntPath;$i++){  
                        error_reporting(E_ERROR | E_PARSE);
                        $removeFrom = explode("&",$_SERVER['REQUEST_URI']);
                        $postionInUrl = strpos($_SERVER['REQUEST_URI'], $removeFrom[$i+1]);
                        $pathDeletePart = "&".substr($_SERVER['REQUEST_URI'], $postionInUrl);
                        $pathBack = str_replace($pathDeletePart, "", $_SERVER['REQUEST_URI']);
                        $pfadName = "Pfad".$i;    
                        if($i <= 0){
                            print "<a href='index.php' class='currentDirectory'>mainStorage</a>";
                        }
                        print "&#10132;<a href='".$pathBack."' class='currentDirectory'>".$_GET[$pfadName]."</a>";
                    }
                }else{
                    print "<p id='startDirectory'>".$path."</p>";
                }

                
               
                $scanned_directory = array_diff(scandir($path), array('..', '.'));
                $cnt=array();
                $cnt=count($scanned_directory) + 2;
                   // print_r($scanned_directory);

                if($cnt == 2){
                    print "<p id='noContent'>Dieser Ordner ist leer</p>";
                }

                print "<br><div id='folder'>";
                if($cnt > 2){
                     print "<br><br><p class='titel'>Ordner</p>";
                }
                for($i=2;$i<$cnt;$i++){
                    if(strpos($scanned_directory[$i], ".") == NULL){     
                                          
                        echo "<div class='directoryElementFolder' oncontextmenu='openDropdownFolder(this)' class='dropbtn'>";
                                //building the url to search for correct folders
                                echo "<a href='".$_SERVER['REQUEST_URI'];
                                if($cntPath == 0){
                                    echo "?";
                                }else{
                                    echo "&";
                                }
                                    echo "Pfad".$cntPath."=".$scanned_directory[$i]."'><img oncontextmenu='openDropdown(this)' class='dropbtn' src='../media/folder-open.svg'></a>";
                                   
                                    echo '<div class="dropdown">';
                                        echo '<div id="myDropdown" style="top: -20px;" class="dropdown-content">';
                                            //download funciton --> not working yet for folders
                                                //echo '<a href="'.$path.'/'.$scanned_directory[$i].'" download>Download</a>';
                                                echo '<form style="margin:0;"></form>';
                                                echo '<form action="deleteFile.php" method="post" style="margin:0;">';
                                                    echo '<input type="hidden" name="path" value="'.$path.'">';
                                                    echo '<input type="hidden" name="fileName" value="'.$scanned_directory[$i].'">';
                                                    echo '<input type="hidden" name="currentUrl" value="'.$_SERVER['REQUEST_URI'].'">';
                                                    echo '<input type="hidden" name="type" value="folder">';
                                                    echo '<a onclick="this.parentNode.submit();">Löschen</a>';
                                                echo '</form>';
                                        echo '</div>';
                                    echo '</div>';

                                    echo "<hr>";
                                    echo"<p>".$scanned_directory[$i]."</p>";
                            echo "</div>";      
                        
                    }
                }
                print "</div>";


/*
                echo '<div class="dropdown">';
                echo "<img oncontextmenu='openDropdown(this)' class='dropbtn' src='".$media."'></a>";
                echo '<div id="myDropdown" class="dropdown-content">';
                        echo '<a href="'.$path.'/'.$scanned_directory[$i].'" download>Download</a>';
                        echo '<form style="margin:0;"></form>';
                        echo '<form action="deleteFile.php" method="post" style="margin:0;">';
                            echo '<input type="hidden" name="path" value="'.$path.'">';
                            echo '<input type="hidden" name="fileName" value="'.$scanned_directory[$i].'">';
                            echo '<input type="hidden" name="currentUrl" value="'.$_SERVER['REQUEST_URI'].'">';
                            echo '<input type="hidden" name="type" value="file">';
                            echo '<a onclick="this.parentNode.submit();">Löschen</a>';
                        echo '</form>';
                echo '</div>';
            echo '</div>';

*/


                print "<div id='file'>";
                if($cnt > 2){
                    print "<br><p class='titel'>Dateien</p>";
                }
                for($i=2;$i<$cnt;$i++){
                    //check if its a file (. ending)
                    if(strpos($scanned_directory[$i], ".") != ""){
                        echo "<div class='directoryElement' oncontextmenu='openDropdown(this)' class='dropbtn'>";

                        //all different file cases
                        if(strpos($scanned_directory[$i], ".png") != "" ||strpos($scanned_directory[$i], ".PNG") != "" || strpos($scanned_directory[$i], ".svg") != ""
                        || strpos($scanned_directory[$i], ".SVG") != "" ||strpos($scanned_directory[$i], ".jpg") != "" || strpos($scanned_directory[$i], ".JPG") != ""
                        || strpos($scanned_directory[$i], ".GIF") != "" || strpos($scanned_directory[$i], ".JPEG") != ""|| strpos($scanned_directory[$i], ".jpeg") != ""
                        ){
                            $media= $path."/".$scanned_directory[$i];
                        }
                        elseif(strpos($scanned_directory[$i], ".pdf") != "" ){
                            $media = "../media/file-pdf.svg";
                        }
                        elseif(strpos($scanned_directory[$i], ".txt") != "" ){
                            $media = "../media/file-alt.svg";
                        }
                        elseif(strpos($scanned_directory[$i], ".docx") != "" ){
                            $media = "../media/file-word.svg";
                        }
                        elseif(strpos($scanned_directory[$i], ".xlsx") != "" ){
                            $media = "../media/file-excel.svg";
                        }
                        elseif(strpos($scanned_directory[$i], ".pptx") != "" ){
                            $media = "../media/file-powerpoint.svg";
                        }
                        elseif(strpos($scanned_directory[$i], ".zip") != "" ){
                            $media = "../media/folder.svg";
                        }
                        else{
                            $media = "../media/file.svg";
                        }


                        //implementing the file display and the dropdown
                    echo '<div class="dropdown">';
                        echo "<img src='".$media."'></a>";
                        echo '<div id="myDropdown" class="dropdown-content">';
                                echo '<a href="'.$path.'/'.$scanned_directory[$i].'" download>Download</a>';
                                echo '<form style="margin:0;"></form>';
                                echo '<form action="deleteFile.php" method="post" style="margin:0;">';
                                    echo '<input type="hidden" name="path" value="'.$path.'">';
                                    echo '<input type="hidden" name="fileName" value="'.$scanned_directory[$i].'">';
                                    echo '<input type="hidden" name="currentUrl" value="'.$_SERVER['REQUEST_URI'].'">';
                                    echo '<input type="hidden" name="type" value="file">';
                                    echo '<a onclick="this.parentNode.submit();">Löschen</a>';
                                echo '</form>';
                        echo '</div>';
                    echo '</div>';

                            echo "<hr>";
                            echo"<p>".$scanned_directory[$i]."</p>";
                        echo"</div>";
                    }
                }
                print "</div>";

                ?>
            </div>
        </div>

        <script>
            /* When the user clicks on the button, 
            toggle between hiding and showing the dropdown content */
            function openDropdown(x) {
                    x.firstChild.childNodes[1].classList.toggle("show"); 
            }

            function openDropdownFolder(x) {
                    x.childNodes[1].firstChild.classList.toggle("show");
            }

            // Close the dropdown if the user clicks outside of it
            window.onclick = function(event) {
                if (!event.target.matches('.dropbtn')) {
                    var dropdowns = document.getElementsByClassName("dropdown-content");
                    var i;
                    for (i = 0; i < dropdowns.length; i++) {
                        var openDropdown = dropdowns[i];
                        if (openDropdown.classList.contains('show')) {
                            openDropdown.classList.remove('show');
                        }
                    }
                }
            }
        </script>
    </body>
</html>

<?php


?>