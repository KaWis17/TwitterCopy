<?php
include_once 'header.php';
?>

    <head>
        <link rel="stylesheet" href="style/index.css">
        <link rel="stylesheet" href="style/singleComment.css">
    </head>

<?php
require_once 'include/dbc.inc.php';
require_once 'include/functions.inc.php';
?>

    <body>
    <div id="display-posts">
        <?php
        if (!empty($conn) && isset($_SESSION["userId"])) {
            displayFollowed($conn);
        }
        ?>
    </div>

    </body>

<?php
include_once 'footer.php';