<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use JetBrains\PhpStorm\NoReturn;
session_status() === PHP_SESSION_ACTIVE || session_start();
function emptyInputSignup($name, $surname, $mail, $nick, $password, $repPassword): bool{
    if(empty($name) || empty($surname) || empty($mail) || empty($nick) || empty($password) || empty($repPassword)){
        return true;
    }
    return false;
}

function emptyInputLogin($login, $password): bool{
    if(empty($login) || empty($password)){
        return true;
    }
    return false;
}

function invalidNick($nick): bool{
    $result = false;
    if(!preg_match("/^[a-zA-Z0-9]*$/", $nick)){
        $result = true;
    }
    return $result;
}

function checkUser($conn, $nick, $mail){
    $result = false;
    $sql = "SELECT * FROM users WHERE nick = ? OR email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../register.php?error=stmtFailure");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $nick, $mail);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        $result = $row;
    }
    mysqli_stmt_close($stmt);
    return $result;
}

function invalidEmail($mail): bool{
    $result = false;
    /* nie działa na polskie maile
    if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
        $result = true;
    }
    */
    return $result;
}

function differentPasswords($password, $repPassword): bool{
    if($password !== $repPassword){
        return true;
    }
    return false;
}


#[NoReturn] function createUser($conn, $name, $surname, $mail, $nick, $password): void
{
    $sql = "INSERT INTO users(name, surname, email, nick, password, type) VALUES(?, ?, ?, ?, ?, 'user');";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../register.php?error=stmtFailure");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssss", $name, $surname, $mail, $nick, $hashedPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../login.php?info=registerSuccess");
    exit();
}

#[NoReturn] function loginUser($conn, $login, $password): void{
    $user = checkUser($conn, $login, $password);
    if(!$user){
        header("location: ../login.php?info=invalidLogin");
        exit();
    }

    $passwordHashed = $user["password"];
    $validPassword = password_verify($password, $passwordHashed);

    if(!$validPassword){
        header("location: ../login.php?info=failedLogin");
        exit();
    }

    session_start();
    $_SESSION["userId"] = $user["id"];
    $_SESSION["userNick"] = $user["nick"];
    $_SESSION["userType"] = $user["type"];
    header("location: ../index.php");
    exit();
}

function displayPosts($conn): void
{
    $sql = "SELECT * FROM posts ORDER BY posts.id DESC LIMIT 25;";
    display($conn, $sql, "../index.php");
}

function displayTop($conn): void
{
    $sql = "SELECT * FROM posts ORDER BY posts.score DESC LIMIT 10;";
    display($conn, $sql, "../top.php");
}

function displayFollowed($conn): void{
    $userId = $_SESSION["userId"];
    $sql = "SELECT * FROM posts WHERE posts.author IN (SELECT following FROM followers WHERE follower = ?) ORDER BY posts.id DESC LIMIT 25;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userId);
    table($stmt, $conn, "../followers.php");
}

function displayUser($conn, $user): void
{
    if(!$user){
        $userId = $_SESSION["userId"];
        $sql = "SELECT posts.id, title, content, author, score FROM posts WHERE posts.author = $userId ORDER BY posts.id DESC;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../index.php");
            exit();
        }

    }
    else{
        $sql = "SELECT posts.id, title, content, author, score FROM posts INNER JOIN users ON posts.author = users.id WHERE users.nick LIKE ? ORDER BY posts.id DESC;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../index.php");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $user);

    }
    table($stmt, $conn, "../profile.php?user=$user");
}

function display($conn, string $sql, $site): void
{
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php");
        exit();
    }

    table($stmt, $conn, $site);
}

function table(bool|mysqli_stmt $stmt, $conn, $site): void
{
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($resultData)) {
        $postID = $row["id"];
        $postTitle = $row["title"];
        $postContent = $row["content"];
        $user = getUserById($conn, $row["author"])["nick"];
        $userType = getUserById($conn, $row["author"])["type"];
        $userId = getUserById($conn, $row["author"])["id"];
        $points = $row["score"];

        include 'singlePost.php';

    }
    mysqli_stmt_close($stmt);
}

#[NoReturn] function deletePost($conn, $id): void{
    $sql = "CALL deletePOST(?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=notDeleted");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../index.php?note=deleted");
    exit();
}

function getUserById($conn, $id): bool|array|null
{
    $sql = "SELECT * FROM users WHERE id = ?;";
    return getUser($conn, $sql, $id);
}

function getUserByUsername($conn, $nick): bool|array|null
{
    $sql = "SELECT * FROM users WHERE nick = ?;";
    return getUser($conn, $sql, $nick);
}

function getUser($conn, string $sql, $user)
{
    $result = false;
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php");
        exit();
    }
    if(is_int($user)) mysqli_stmt_bind_param($stmt, "i", $user);
    else mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        $result = $row;
    }
    mysqli_stmt_close($stmt);
    return $result;
}

#[NoReturn] function addPost($conn, $title, $content, $author): void{
    $sql = "INSERT INTO posts(title, content, author) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $author);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../index.php");
    exit();

}

#[NoReturn] function likeAction($conn, $postID, $site): void{
    $userID = $_SESSION["userId"];
    $sql = "CALL alterLikes(?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?blad=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $postID, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: $site#$postID");
    exit();
}

#[NoReturn] function dislikeAction($conn, $postID, $site): void{
    $userID = $_SESSION["userId"];
    $sql = "CALL alterDislikes(?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?blad=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $postID, $userID);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: $site#$postID");
    exit();
}

function isLiked($conn, $postID):bool{
    $sql = "SELECT * FROM likes WHERE idPost= ? AND idUser = ?;";
    return checkOpinion($conn, $sql, $postID);
}


function isDisliked($conn, $postID):bool{
    $sql = "SELECT * FROM dislikes WHERE idPost=? AND idUser = ?;";
    return checkOpinion($conn, $sql, $postID);
}

function checkOpinion($conn, string $sql, $postID)
{
    $result = false;
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmtFailure");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $postID, $_SESSION["userId"]);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if (mysqli_fetch_assoc($resultData)) {
        $result = true;
    }
    mysqli_stmt_close($stmt);
    return $result;
}

function isFollowing($conn, $following): bool{
    $sql = "SELECT * FROM followers WHERE followers.follower=? AND followers.following = ?;";
    $result = false;
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmtFailure");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $_SESSION["userId"], $following);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if (mysqli_fetch_assoc($resultData)) {
        $result = true;
    }
    mysqli_stmt_close($stmt);
    return $result;
}

#[NoReturn] function alterFollow($conn, $follower, $following): void{
    $sql = "CALL alterFollow(?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?blad=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $follower, $following);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $followingName = getUserById($conn, $following)['nick'];
    header("location: ../profile.php?user=$followingName");
    exit();
}

#[NoReturn] function grantAdmin($conn, $id): void{
    $sql = "UPDATE users SET type='admin' WHERE id=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?blad=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $site = getUserById($conn, $id)['nick'];
    header("location: ../profile.php?user=$site");
    exit();
}

#[NoReturn] function deleteUser($conn, $id): void{
    $sql = "CALL deleteUSER(?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?blad=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if($_SESSION["userId"] == $id){
        session_start();
        session_unset();
        session_destroy();
    }

    header("location: ../index.php");
    exit();
}

function displayComments($conn, $postID): void{
    $sql = "SELECT * FROM comments WHERE postID = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $postID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($resultData)) {
        $username = getUserById($conn, $row['userID'])['nick'];
        $userType = getUserById($conn, $row['userID'])['type'];
        $content = $row['content'];

        include 'singleComment.php';

    }
    mysqli_stmt_close($stmt);
}

#[NoReturn] function addComment($conn, $postID, $userID, $site, $comment): void{
    $sql = "INSERT INTO comments(postid, userid, content) VALUES(?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?blad=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "iis", $postID, $userID, $comment);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: $site#$postID");
    exit();
}