<?php
    require_once 'database/config.php';

    $firs_tname = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $email_address = $_POST['email-address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "SELECT * 
            FROM users 
            WHERE email_address = '".$email_address."'";
    $result = $mysqli->query($sql);

    if(mysqli_num_rows($result) == 0) {
        $sql = "INSERT INTO users (first_name, last_name, email_address, password, profile_picture)
                VALUES ('".$firs_tname."','".$last_name."','".$email_address."','".$password."','default.png')";
        $result = $mysqli->query($sql);

        header("Location: index.php");
    } else {
        header("Location: index.php");
    }
?>
