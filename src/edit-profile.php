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

            $stmt = $mysqli->prepare($sql);

            // Bind de parameters aan de SQL-instructie
            $stmt->bind_param("ssiii", $username, $description, $private_account, $_SESSION['userid']);

            // Als er een profielfoto is geüpload, voeg deze toe aan de bind_param-functie
            if(!empty($profile_picture)) {
                // Nieuwe profielfoto-naam genereren
                $new_profile_picture = "profile_picture_" . $_SESSION['userid'] . "_" . time() .".png";
                $profile_picture = $new_profile_picture;
                move_uploaded_file($profile_picture_temporary, $upload_directory . $profile_picture);

                $stmt->bind_param("ssisi", $username, $description, $private_account, $profile_picture, $_SESSION['userid']);
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
