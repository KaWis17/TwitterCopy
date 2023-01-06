<?php
include_once 'header.php';
?>

    <head>
        <link rel="stylesheet" href="style/form.css">
    </head>

    <section class="register-form">
        <h2>Login</h2>
        <form class="form" action="include/login.inc.php" method="post">
            <input type="text" name="nickMail" placeholder="Username (or email?):">
            </br>
            <input type="password" name="password" placeholder="Password:">
            </br>
            <button type="submit" name="submit">Login</button>
        </form>

        <?php
        if(isset($_GET["info"])){
            if($_GET["info"] == "registerSuccess"){
                echo "<p>Account has been created</p>";
            }
        }
        ?>
    </section>
<?php
include_once 'footer.php';
