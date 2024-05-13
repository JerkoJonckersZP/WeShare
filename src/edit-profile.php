<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid'])) {
        $username = $_POST['username'];
        $description = trim($_POST['description']);
        $private_account = isset($_POST['private-account']) && $_POST['private-account'] == "on" ? 1 : 0;
        $profile_picture = $_FILES['profile-picture']['name'];
        $profile_picture_temporary = $_FILES['profile-picture']['tmp_name'];
        $upload_directory = $_SERVER['DOCUMENT_ROOT'] . '/weshare/public/images/';

        if(isset($username)) {
            // Bereid de SQL-instructie voor
            $sql = "UPDATE users
                    SET username = ?, description = ?, private_account = ?";

            // Als er een profielfoto is geüpload, voeg deze toe aan de SQL-instructie
            if(!empty($profile_picture)) {
                $sql .= ", profile_picture = ?";
            }

            $sql .= " WHERE id = ?";
            
            // Aantal parameters is afhankelijk van of er een profielfoto is geüpload
            if(!empty($profile_picture)) {
                // Als er een profielfoto is, moeten er vijf parameters worden gebonden
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssii", $username, $description, $private_account, $profile_picture, $_SESSION['userid']);
            } else {
                // Als er geen profielfoto is, moeten er vier parameters worden gebonden
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssii", $username, $description, $private_account, $_SESSION['userid']);
            }

            // Voer de SQL-instructie uit
            $stmt->execute();
        }
    }

    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: index.php");
    }
?>
