<?php
    require_once 'database/config.php';

    $username = $_POST['username'];
    $email_address = $_POST['email-address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if(isset($username) && isset($email_address) && isset($password)) {
        // Voorbereiden van de statement om te controleren of het e-mailadres al in de database bestaat
        $sql_check_email = "SELECT * FROM users WHERE email_address = ?";
        $stmt_check_email = $mysqli->prepare($sql_check_email);
        $stmt_check_email->bind_param("s", $email_address);
        $stmt_check_email->execute();
        $result_check_email = $stmt_check_email->get_result();

        if($result_check_email->num_rows == 0) {
            // Voorbereiden van de statement om een nieuwe gebruiker toe te voegen
            $sql_insert_user = "INSERT INTO users (username, email_address, password) VALUES (?, ?, ?)";
            $stmt_insert_user = $mysqli->prepare($sql_insert_user);
            $stmt_insert_user->bind_param("sss", $username, $email_address, $password);
            $stmt_insert_user->execute();

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
    } else {
        if(isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: index.php");
        }
    }
?>
