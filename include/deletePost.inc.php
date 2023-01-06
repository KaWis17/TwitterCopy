<?php

require_once 'dbc.inc.php';
require_once 'functions.inc.php';

if(isset($_POST["delete"])){
    $id = $_POST["id"];

    if (!empty($conn)) {
        deletePost($conn, $id);
    }
}

else{
    header("location: ../index.php");
}