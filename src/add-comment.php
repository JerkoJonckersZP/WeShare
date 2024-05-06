<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid'])) {
        if(isset($_POST['post'])) {
            $sql = "INSERT INTO comments (user, post, comment) 
                    VALUES ('".$_SESSION['userid']."','".mysqli_real_escape_string($mysqli, $_POST['post'])."','".mysqli_real_escape_string($mysqli, $_POST['comment'])."')";
            $result = $mysqli->query($sql);

            if(isset($_SERVER['HTTP_REFERER'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: index.php");
            }
        } else {
            if(isset($_SERVER['HTTP_REFERER'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: index.php");
            }
        }
    } else {
        if(isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: index.php");
        }
    }
?>