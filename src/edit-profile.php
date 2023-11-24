<?php
    require_once 'database/config.php';
    session_start();

    $username = $_POST['username'];
    $description = $_POST['description'];
    $profile_picture = $_FILES['profile-picture']['name'];
    $profile_picture_temporary = $_FILES['profile-picture']['tmp_name'];
    $upload_directory = $_SERVER['DOCUMENT_ROOT'] . '/weshare/public/images/';

    if(isset($_SESSION['userid'])) {
        if(isset($username)) {
            if(!(empty($profile_picture))) {
                $i = 1;
                
                do {
                    $profile_picture_info = pathinfo($profile_picture);
                    $new_profile_picture = $i . "." . $profile_picture_info['extension'];
                    $profile_picture = $new_profile_picture;
            
                    $i++;
                } while (file_exists($upload_directory . $profile_picture));
            
                move_uploaded_file($profile_picture_temporary, $upload_directory . $profile_picture);

                $sql = "UPDATE users
                    SET username = '".$username."', description = '".$description."', profile_picture = '".$profile_picture."' 
                    WHERE id = ".$_SESSION['userid']."";
                $result = $mysqli->query($sql);
            } else {
                $sql = "UPDATE users
                    SET username = '".$username."', description = '".$description."' 
                    WHERE id = ".$_SESSION['userid']."";
                $result = $mysqli->query($sql);
            }
        }
    }
    
    header("Location: index.php");
?>