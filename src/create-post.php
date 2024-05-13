<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid'])) {
        $message = trim($_POST['message']);
        $photo = $_FILES['photo']['name'];
        $photo_temporary = $_FILES['photo']['tmp_name'];
        $upload_directory = $_SERVER['DOCUMENT_ROOT'] . '/weshare/public/images/';

        if(!(empty($message))) {
            $sql = "INSERT INTO posts (user, message, photo) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("iss", $_SESSION['userid'], $message, $photo);

            if(!(empty($photo))) {
                $photo_info = pathinfo($photo);
                $new_photo = "post_" . $_SESSION['userid'] . "_" . time() . ".png";
                $photo = $new_photo;
                move_uploaded_file($photo_temporary, $upload_directory . $photo);
            } else {
                $photo = '';
            }

            $stmt->execute();
        }
    }

    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: index.php");
    }
?>
