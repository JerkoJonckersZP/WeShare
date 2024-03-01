<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid'])) {
        if(isset($_POST['user_one']) AND isset($_POST['user_two'])) {
            $user_one = $_POST['user_one'];
            $user_two = $_POST['user_two'];
    
            $sql = "SELECT * 
                    FROM friends 
                    WHERE user_one = ".$user_one." AND user_two = ".$user_two." OR user_one = ".$user_two." AND user_two = ".$user_one."";
            $result = $mysqli->query($sql);
    
            if(mysqli_num_rows($result) == 1) {
                $sql = "DELETE FROM friends
                        WHERE user_one = ".$user_one." AND user_two = ".$user_two." OR user_one = ".$user_two." AND user_two = ".$user_one."";
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