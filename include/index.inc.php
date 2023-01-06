<?php

session_start();

if(isset($_POST["submit"])){

    require_once 'dbc.inc.php';
    require_once 'functions.inc.php';

    $title = $_POST["title"];
    $content = $_POST["content"];
    $author = $_SESSION["userId"];

    if (!empty($conn)) {
        addPost(conn: $conn, title: $title, content: $content, author: $author);
    }

}

else{
    header("location: ../index.php");
}