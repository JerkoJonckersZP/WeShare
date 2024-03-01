<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid'])) {
        if(isset($_POST['requestor']) AND isset($_POST['receiver'])) { 
            $requestor = $_POST['requestor'];
            $receiver = $_POST['receiver'];

            $sql = "SELECT * 
                    FROM friend_requests 
                    WHERE requestor = '".$requestor."' AND receiver = '".$receiver."'";
            $result = $mysqli->query($sql);

            if(mysqli_num_rows($result) > 0) {
                $sql = "DELETE FROM friend_requests 
                        WHERE requestor = '".$requestor."' AND receiver = '".$receiver."'";
                $result = $mysqli->query($sql);
            }

            if(isset($_SERVER['HTTP_REFERER'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: index.php");
            }
        }
    }

    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: index.php");
    }
?>