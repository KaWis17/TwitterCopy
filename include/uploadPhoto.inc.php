<?php

require_once 'dbc.inc.php';
require_once 'functions.inc.php';

if(isset($_POST["sub"]) && isset($_FILES["myPhoto"])){
    $photo = $_FILES["myPhoto"];

    if (!empty($conn)) {
        $nick = $_SESSION["userNick"];

        if($photo['error'] === 0){
            if($photo["size"] > 125000){
                header("location: ../profile.php?user=$nick?error=imgToLarge");
            }
        }
        else{
            header("location: ../profile.php?user=$nick");
        }
    }
}

else{
    header("location: ../index.php?error=true");
}