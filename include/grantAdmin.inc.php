<?php
if(isset($_POST["sub"])){
    $id =$_POST["id"];

    require_once 'dbc.inc.php';
    require_once 'functions.inc.php';

    if (!empty($conn)) {
        grantAdmin($conn, $id);
    }
}
else{
    header("location: ../index.php?error=true");
}