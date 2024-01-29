<?php
    require_once 'database/config.php';

    $username = $_POST['username'];
    $email_address = $_POST['email-address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if(isset($username) && isset($email_address) && isset($password)) {
        $sql = "SELECT * 
            FROM users 
            WHERE email_address = '".$email_address."'";
        $result = $mysqli->query($sql);

        if(mysqli_num_rows($result) == 0) {
            $sql = "INSERT INTO users (username, email_address, password)
                    VALUES ('".$username."','".$email_address."','".$password."')";
            $result = $mysqli->query($sql);

            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
?>
