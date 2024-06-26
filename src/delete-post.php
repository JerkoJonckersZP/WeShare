<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid']) && isset($_POST['post'])) {
        $stmt = $mysqli->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->bind_param("i", $_POST['post']);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1) {
            $stmt = $mysqli->prepare("UPDATE posts 
                                      SET deleted = 1
                                      WHERE id = ?");
            $stmt->bind_param("i", $_POST['post']);
            $stmt->execute();
            $stmt->close();

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
