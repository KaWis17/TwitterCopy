<?php
include_once 'header.php';
?>

    <head>
        <link rel="stylesheet" href="style/profile.css">
        <link rel="stylesheet" href="style/index.css">
        <link rel="stylesheet" href="style/singleComment.css">
        <title>NotaBird</title>
    </head>

<?php
require_once 'include/dbc.inc.php';
require_once 'include/functions.inc.php';
?>

    <body>
        <div class="credentials">
            <?php
            if (!empty($conn)) {
                $user = $_GET['user'];
                $id = getUserByUsername($conn, $user)["id"];
                $name = getUserByUsername($conn, $user)["name"];
                $surname = getUserByUsername($conn, $user)['surname'];
                $mail = getUserByUsername($conn, $user)['email'];
                $followerCount = getUserByUsername($conn, $user)['followerCount'];
                $userType = getUserByUsername($conn, $user)['type'];

                if(isset($_SESSION['userType'])){
                    if($_SESSION['userType'] == 'admin' || $_SESSION['userNick']==$user){
                        echo "<table class='panel'>";
                        echo "<tr><th>";
                        echo "<form class='form' action='include/grantAdmin.inc.php' method='post'>";
                        if($userType != 'admin' && $_SESSION['userType'] == 'admin'){
                            echo "<input type='hidden' value='$id' name='id'/>";
                            echo "<button id='grantAdmin' type='submit' name='sub'>GRANT ADMIN</button>";
                        }
                        echo "</form>";
                        echo "</th>";
                        echo "<th>";

                        echo "<form class='form' action='include/deleteUser.inc.php' method='post'>";
                        echo "<input type='hidden' value='$id' name='id'/>";
                        echo "<button id='deleteAcc' type='submit' name='sub'>DELETE ACCOUNT</button>";
                        echo "</form>";
                        echo "</th></tr>";
                        echo "</table>";
                    }
                }

                echo "<table class='credentialsTable'>
                    <tr>
                        <th class='aboutUser'>
                        Username: <b>$user</b></br>
                        Name: <b>$name</b></br>
                        Surname: <b>$surname</b></br>
                        eMail: <b>$mail</b></br>
                        Followers: <b>$followerCount</b></br>
                        User type: <b>$userType</b></th>
                        <th>";
                echo "<form class='form' action='include/follow.inc.php' method='post'>";
                echo "<input type='hidden' value='$id' name='id'/>";
                if(isset($_SESSION['userNick'])){
                    if($user != $_SESSION['userNick'] && !isFollowing($conn, $id)){
                        echo "<button id='follow' type='submit' name='sub'>FOLLOW</button>";
                    }
                    else if($user != $_SESSION['userNick']){
                        echo "<button id='follow' type='submit' name='sub'>un-FOLLOW</button>";
                    }
                }
                else{
                    echo "</th>";
                }
                echo "</form>";
                echo "</tr>
                </table>";
            }
            ?>
        </div>

        <div id="display-posts">
            <?php
            if (!empty($conn)) {
                if(!empty($_GET['user'])){
                    displayUser($conn, $_GET['user']);
                }
                else{
                    displayUser($conn,false);
                }
            }
            ?>
        </div>

    </body>

<?php
include_once 'footer.php';
