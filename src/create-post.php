<?php
    require_once 'database/config.php';
    session_start();

    $message = $_POST['message'];
    $photo = $_FILES['photo']['name'];
    $photo_temporary = $_FILES['photo']['tmp_name'];
    $upload_directory = $_SERVER['DOCUMENT_ROOT'] . '/weshare/public/images/';

    if(isset($_SESSION['userid'])) {
        if(isset($message)) {
            if(!(empty($photo))) {
                $sql = "SELECT * FROM posts WHERE user = '".$_SESSION['userid']."'";
                $result = $mysqli->query($sql);

                $postid = mysqli_num_rows($result) + 1;
            
                $photo_info = pathinfo($photo);
                $new_photo = $_SESSION['userid'] . "_post_" . $postid . ".png";
                $photo = $new_photo;
        
                move_uploaded_file($photo_temporary, $upload_directory . $photo);
            }
        
            $sql = "INSERT INTO posts (user, message, photo) 
                    VALUES ('".$_SESSION['userid']."','".$message."','".$photo."')";
        
            $result = $mysqli->query($sql);
        }
    }

    header("Location: index.php");
?>