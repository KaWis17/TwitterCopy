<?php
include_once 'header.php';
?>

    <head>
        <link rel="stylesheet" href="style/form.css">
    </head>

    <section class="register-form">
        <h2>Register</h2>
        <form class="form" action="include/register.inc.php" method="post">
            <input type="text" name="name" placeholder="Name:">
            </br>
            <input type="text" name="surname" placeholder="Surname:">
            </br>
            <input type="text" name="mail" placeholder="Mail:">
            </br>
            <input type="text" name="nick" placeholder="Username:">
            </br>
            <input type="password" name="password" placeholder="Password:">
            </br>
            <input type="password" name="repPassword" placeholder="Repeat password:">
            </br>
            <button type="submit" name="submit">Register</button>
        </form>

        <?php
        if(isset($_GET["error"])){
            if($_GET["error"] == "emptyInput"){
                echo '<script>alert("Fill all inputs!")</script>';
            }
            else if($_GET["error"] == "invalidNick"){
                echo '<script>alert("Username taken or invalid")</script>';
            }
            else if($_GET["error"] == "invalidEmail"){
                echo '<script>alert("Email taken or invalid")</script>';
            }
            else if($_GET["error"] == "differentPasswords"){
                echo '<script>alert("Passwords are not the same")</script>';
            }
            else if($_GET["error"] == "stmtFailure"){
                echo '<script>alert("Currently not available, contact admin")</script>';
            }
        }
        ?>
    </section>


<?php
include_once 'footer.php';
