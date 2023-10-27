<?php
    require_once 'database/config.php';
    session_start();

    $email_address = $_POST['email-address'];
    $password = $_POST['password'];
    
    $sql = "SELECT * 
            FROM users 
            WHERE email_address = '".$email_address."'
            AND password = '".$password."'";
    $result = $mysqli->query($sql);

    if(mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['userid'] = $row['id'];
    }

    header("Location: index.php");
?>
