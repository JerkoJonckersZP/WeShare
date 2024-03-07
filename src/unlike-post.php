<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid'])) {
        if(isset($_POST['post'])) {
            $sql = "SELECT * 
                    FROM liked_posts 
                    WHERE user = '".$_SESSION['userid']."' AND post = '".$_POST['post']."'";
            $result = $mysqli->query($sql);

            if(mysqli_num_rows($result) == 1) {
                $sql = "DELETE FROM liked_posts 
                        WHERE user = '".$_SESSION['userid']."' AND post = '".$_POST['post']."'";
                $result = $mysqli->query($sql);

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
    } else {
        if(isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: index.php");
        }
    }
?>