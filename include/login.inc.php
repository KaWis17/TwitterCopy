<?php
    if(isset($_POST["submit"])){
        $login = $_POST["nickMail"];
        $password = $_POST["password"];

        require_once 'dbc.inc.php';
        require_once 'functions.inc.php';

        if (!empty($conn)) {
            if(emptyInputLogin(login: $login, password: $password) !== false){
                header("location: ../login.php?info=emptyInput");
                exit();
            }

            loginUser(conn: $conn, login: $login, password: $password);
        }
    }
    else{
        header("location: ../login.php");
    }