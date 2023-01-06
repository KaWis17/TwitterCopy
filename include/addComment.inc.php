<?php
if(isset($_POST["submit"])){
    $postID = $_POST["id"];
    $site = $_POST["site"];
    $comment = $_POST["commentContent"];

    require_once 'dbc.inc.php';
    require_once 'functions.inc.php';

    if (!empty($conn)) {
        addComment($conn, $postID, $_SESSION["userId"], $site, $comment);
    }
}
else{
    header("location: ../index.php?error=true");
}