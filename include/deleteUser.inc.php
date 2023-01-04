<?php
if(isset($_POST["sub"])){
    $id =$_POST["id"];

    require_once 'dbc.inc.php';
    require_once 'functions.inc.php';

    echo 'test1';
    if (!empty($conn)) {
        deleteUser($conn, $id);
    }
}
else{
    header("location: ../index.php?error=true");
}