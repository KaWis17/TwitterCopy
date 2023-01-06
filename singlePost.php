<?php

if (isset($conn, $postID, $postTitle, $user, $postContent, $site, $points, $userType, $userId)) {
    $liked = isLiked($conn, $postID);
    $disliked = isDisliked($conn, $postID);

    echo "<div class='anchor' id='$postID'></div>";
    echo "<table class='single-post'>";

    echo "<tr><td class='title'>$postTitle</td><td class='user'><b><a href='profile.php?user=$user'>$user</a></b> - $userType</td></tr>";
    echo "<tr><td class='content' colspan='2'>$postContent</td></tr>";
    echo "<tr>";
    if (isset($_SESSION["userId"])) {
        echo "<td class='buttons'>";
        echo "<form class='form' action='include/score.inc.php' method='post'>";
        echo "<button type='submit' name='like' class='like'";
        if ($disliked) echo " disabled=true>";
        else echo ">";
        if ($liked) echo "un-LIKE";
        else echo "LIKE";
        echo "</button>";
        echo "<input type='hidden' value='$postID' name='id'/>";
        echo "<input type='hidden' value='$site' name='site'/>";
        echo "<button type='submit' name='dislike' class='dislike'";
        if ($liked) echo " disabled=true>";
        else echo ">";
        if ($disliked) echo "un-DISLIKE";
        else echo "DISLIKE";
        echo "</button>";
        echo "</form>";
        echo "</td>";
    } else {
        echo "<td class='buttons'></td>";
    }
    echo "<td class='points'>score: $points</td>
                    </tr>";

    if (isset($_SESSION["userId"])) {
        if ($_SESSION["userType"] == 'admin' || $userId == $_SESSION["userId"]) {
            echo "<td class='deletePost' colspan='3'> 
                        <form class='form' action='include/deletePost.inc.php' method='post'>
                            <input type='hidden' value='$postID' name='id'/>
                            <button type='submit' name='delete' class='delete'>DELETE</button>
                        </form></td>";
        }
    }

    if (isset($_SESSION["userId"])) {
        if($_SESSION["userType"] == 'admin' || $_SESSION["userType"] == 'user')
        echo "<tr><td class='submitComment' colspan='3'>";
        echo "<form class='form' action='include/addComment.inc.php' method='post'>
                            <input type='hidden' value='$postID' name='id'/>
                            <input type='hidden' value='$site' name='site'/>
                            <input type='text' name='commentContent' placeholder='comment: '>
                            <button type='submit' name='submit' class='submit'>SUBMIT</button>
                        </form></td>";
        echo "</tr>";
    }


    echo "<tr><td class='comments' colspan='3'>";
        displayComments($conn, $postID);
    echo "</td></tr>";
    echo "</table></br>";
}
