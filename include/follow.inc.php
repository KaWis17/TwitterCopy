<?php
if(isset($_POST["sub"])){
    $id =$_POST["id"];

    require_once 'dbc.inc.php';
    require_once 'functions.inc.php';

    if (!empty($conn)) {
        alterFollow($conn, $_SESSION["userId"], $id);
    }
}
else{
    header("location: ../index.php?error=true");
}