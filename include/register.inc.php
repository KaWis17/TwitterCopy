<?php
    if(isset($_POST["submit"])){

        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $mail = $_POST["mail"];
        $nick = $_POST["nick"];
        $password = $_POST["password"];
        $repPassword = $_POST["repPassword"];

        require_once 'dbc.inc.php';
        require_once 'functions.inc.php';


        if (!empty($conn)) {

            if(emptyInputSignup(name: $name, surname: $surname, mail: $mail, nick: $nick, password: $password, repPassword: $repPassword) !== false){
                header("location: ../register.php?error=emptyInput");
                exit();
            }

            if(invalidNick(nick: $nick) !== false){
                header("location: ../register.php?error=invalidNick");
                exit();
            }

            if(invalidEmail(mail: $mail) !== false){
                header("location: ../register.php?error=invalidEmail");
                exit();
            }

            if(checkUser(conn: $conn, nick: $nick, mail: $mail)){
                header("location: ../register.php?error=invalidUser");
                exit();
            }

            if(differentPasswords(password: $password, repPassword: $repPassword) !== false){
                header("location: ../register.php?error=differentPasswords");
                exit();
            }

            createUser(conn: $conn, name: $name, surname: $surname, mail: $mail, nick: $nick, password: $password);
        }

    }
    else{
        header("location: ../register.php");
    }