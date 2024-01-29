<?php
    require_once 'database/config.php';
    session_start();

    $username = $_POST['username'];
    $description = trim($_POST['description']);

    if($_POST['private-account'] == "on") {
        $private_account = 1;
    } else {
        $private_account = 0;
    }

    $profile_picture = $_FILES['profile-picture']['name'];
    $profile_picture_temporary = $_FILES['profile-picture']['tmp_name'];
    $upload_directory = $_SERVER['DOCUMENT_ROOT'] . '/weshare/public/images/';

    if(isset($_SESSION['userid'])) {
        if(isset($username)) {
            if(!(empty($profile_picture))) {
                $sql = "SELECT * FROM users WHERE id = '".$_SESSION['userid']."'";
                $result = $mysqli->query($sql);

                $user = $result->fetch_assoc();

                unlink($upload_directory . $user['profile_picture']);

                $profile_picture_info = pathinfo($profile_picture);
                $new_profile_picture = "profile_picture_" . $user['id'] . "_" . time() .".png";
                $profile_picture = $new_profile_picture;
            
                move_uploaded_file($profile_picture_temporary, $upload_directory . $profile_picture);

                $sql = "UPDATE users
                        SET username = '".$username."', description = '".$description."', profile_picture = '".$profile_picture."', private_account = '".$private_account."' 
                        WHERE id = ".$_SESSION['userid']."";
                $result = $mysqli->query($sql);
            } else {
                $sql = "UPDATE users
                        SET username = '".$username."', description = '".$description."', private_account = '".$private_account."' 
                        WHERE id = ".$_SESSION['userid']."";
                $result = $mysqli->query($sql);
            }
        }
    }
    
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>