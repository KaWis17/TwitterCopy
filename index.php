<?php
    include_once 'header.php';
?>

    <head>
        <link rel="stylesheet" href="style/index.css">
        <link rel="stylesheet" href="style/singleComment.css">
    </head>
<?php /*
    <script type="text/javascript" src="der/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="der/jquery.cookie.js"></script>
    <script type="text/javascript">
        function get_position(){
            var top_position = document.documentElement.scrollTop;
        };
        function set_position(){
            var top_position = $.cookie('pos');
            window.scrollTo(0,top_position)
            $.cookie('pos', null);
        };
    </script>
*/?>
<?php
    require_once 'include/dbc.inc.php';
    require_once 'include/functions.inc.php';
?>

<body>
    <?php
    if(isset($_SESSION["userId"])){
        echo '
            <div id="add-post">
            <form class="add-form" action="include/index.inc.php" method="post">
                <input type="text" name="title" placeholder="Title: ">
                </br>
                <textarea name="content" id="content" placeholder="Content: "></textarea>
                </br>
                <button type="submit" name="submit">Add post</button>
            </form>
        </div>
            ';
    }
    ?>

    <div id="display-posts">
        <?php
        if (!empty($conn)) {
            displayPosts($conn);
        }
        ?>
    </div>

</body>

<?php
    include_once 'footer.php';
