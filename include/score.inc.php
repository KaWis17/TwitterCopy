<?php

require_once 'dbc.inc.php';
require_once 'functions.inc.php';

if(isset($_POST["like"])){
    $id = $_POST["id"];
    $site = $_POST["site"];

    if (!empty($conn)) {
        likeAction($conn, $id, $site);
    }
}

else if(isset($_POST["dislike"])){
    $id = $_POST["id"];
    $site = $_POST["site"];

    if (!empty($conn)) {
        dislikeAction($conn, $id, $site);
    }
}

else{
    header("location: ../index.php");
}