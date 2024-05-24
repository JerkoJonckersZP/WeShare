<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid']) && isset($_POST['post'])) {
        $sql = "SELECT * FROM posts WHERE id = ".$_POST['post']." AND user = ".$_SESSION['userid']."";
        $result = $mysqli->query($sql);

        if($result->num_rows == 1) {
            $sql = "DELETE FROM posts WHERE id = ".$_POST['post']." AND user = ".$_SESSION['userid']."";
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
