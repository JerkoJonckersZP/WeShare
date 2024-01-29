<?php
    require_once 'database/config.php';
    session_start();

    $message = trim($_POST['message']);
    $photo = $_FILES['photo']['name'];
    $photo_temporary = $_FILES['photo']['tmp_name'];
    $upload_directory = $_SERVER['DOCUMENT_ROOT'] . '/weshare/public/images/';

    if(isset($_SESSION['userid'])) {
        if(isset($message)) {
            if(!(empty($photo))) {
                $photo_info = pathinfo($photo);
                $new_photo = "post_" . $_SESSION['userid'] . "_" . time() . ".png";
                $photo = $new_photo;
        
                move_uploaded_file($photo_temporary, $upload_directory . $photo);
            }
        
            $sql = "INSERT INTO posts (user, message, photo) 
                    VALUES ('".$_SESSION['userid']."','".$message."','".$photo."')";
        
            $result = $mysqli->query($sql);
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>