
<?php
if(isset($content, $username, $userType)){
    ?>
<?php
    echo "<div id='single-comment'>";
    echo "<table class='single-comment'>";
    echo "<tr><dt id='username'>";
    echo "<b><a href='profile.php?user=$username'>$username</a></b> - $userType</td></tr>";
    echo "</dt><dt>";
    echo $content;
    echo '</dt></tr>';
    echo '</table>';
    echo "</div>";
    echo '</br>';
}