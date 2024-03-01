<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid'])) {
        if(isset($_POST['requestor']) AND isset($_POST['receiver'])) {
            $requestor = $_POST['requestor'];
            $receiver = $_POST['receiver'];
    
            $sql = "SELECT * 
                    FROM friends 
                    WHERE user_one = ".$requestor." AND user_two = ".$receiver." OR user_one = ".$receiver." AND user_two = ".$requestor."";
            $result = $mysqli->query($sql);
    
            if(mysqli_num_rows($result) == 0) {
                $sql = "INSERT INTO friend_requests (requestor, receiver) 
                        VALUES ('".$requestor."','".$receiver."')";
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