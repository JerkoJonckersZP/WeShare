<?php
    require_once 'database/config.php';
    session_start();

    $email_address = $_POST['email-address'];
    $password = $_POST['password'];

    // Voorbereiden van de statement om de gebruiker op te halen op basis van het e-mailadres
    $sql = "SELECT * FROM users WHERE email_address = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email_address);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    // Controleer of het wachtwoord overeenkomt met de gehashte versie in de database
    if(password_verify($password, $row['password'])) {
        if($result->num_rows == 1) {
            $_SESSION['userid'] = $row['id'];
        }
    }

    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: index.php");
    }
?>
