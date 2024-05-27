<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid']) && isset($_POST['post'])) {
        $sql = "SELECT * FROM users WHERE id = ".$_SESSION['userid']."";
        $result = $mysqli->query($sql);

        $user = $result->fetch_assoc();

        if($user['user_type'] > 1) {
            $sql = "UPDATE reports
                    SET closed = 1
                    WHERE reports.post = ".$_POST['post']."";
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