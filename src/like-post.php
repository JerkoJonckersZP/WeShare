<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid']) && isset($_POST['post'])) {
        $stmt_check_like = $mysqli->prepare("SELECT * FROM liked_posts WHERE user = ? AND post = ?");
        $stmt_check_like->bind_param("ii", $_SESSION['userid'], $_POST['post']);
        $stmt_check_like->execute();
        $result_check_like = $stmt_check_like->get_result();

        if($result_check_like->num_rows == 0) {
            $stmt_insert_like = $mysqli->prepare("INSERT INTO liked_posts (user, post) VALUES (?, ?)");
            $stmt_insert_like->bind_param("ii", $_SESSION['userid'], $_POST['post']);
            $stmt_insert_like->execute();
            $stmt_insert_like->close();
        }

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
?>
