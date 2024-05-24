<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid']) && isset($_POST['post'])) {
        $sql = "SELECT * FROM posts WHERE id = ".$_POST['post']." AND user = ".$_SESSION['userid']."";
        $result = $mysqli->query($sql);

        $stmt = $mysqli->prepare("SELECT * FROM posts WHERE id = ? AND user = ?");
        $stmt->bind_param("ii", $_POST['post'],$_SESSION['userid']);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1) {
            $stmt = $mysqli->prepare("UPDATE posts 
                                      SET deleted = 1
                                      WHERE id = ? AND user = ?");
            $stmt->bind_param("ii", $_POST['post'], $_SESSION['userid']);
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
