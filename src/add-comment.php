<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid'])) {
        if(isset($_POST['post'])) {
            // Bereid de SQL-instructie voor
            $sql = "INSERT INTO comments (user, post, comment) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("iss", $_SESSION['userid'], $_POST['post'], $_POST['comment']);
            $stmt->execute();

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
