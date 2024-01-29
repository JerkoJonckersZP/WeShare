<?php
    require_once 'database/config.php';
    session_start();

    $email_address = $_POST['email-address'];
    $password = $_POST['password'];
    
    $sql = "SELECT * 
            FROM users 
            WHERE email_address = '".$email_address."'";
    $result = $mysqli->query($sql);

    $row = $result->fetch_assoc();

    if(password_verify($_POST['password'], $row['password'])) {
        if(mysqli_num_rows($result) == 1) {
            $_SESSION['userid'] = $row['id'];
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
