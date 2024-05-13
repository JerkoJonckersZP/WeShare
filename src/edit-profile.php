<?php
    require_once 'database/config.php';
    session_start();

    // Verkrijg en ontsnap de gebruikersinvoer
    $username = mysqli_real_escape_string($mysqli, $_POST['username']);
    $description = $_POST['description']; // Geen ontsnapping nodig, behoud de opmaak

    // Controleer of het privé-account is ingeschakeld
    $private_account = isset($_POST['private-account']) && $_POST['private-account'] == "on" ? 1 : 0;

    // Ontsnappen en controleren van het profielfoto-bestand
    $profile_picture = mysqli_real_escape_string($mysqli, $_FILES['profile-picture']['name']);
    $profile_picture_temporary = $_FILES['profile-picture']['tmp_name'];
    $upload_directory = $_SERVER['DOCUMENT_ROOT'] . '/weshare/public/images/';

    if(isset($_SESSION['userid'])) {
        if(isset($username)) {
            if(!(empty($profile_picture))) {
                // Profielfoto vervangen: oude verwijderen en nieuwe uploaden
                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("i", $_SESSION['userid']);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                unlink($upload_directory . $user['profile_picture']);

                $profile_picture_info = pathinfo($profile_picture);
                $new_profile_picture = "profile_picture_" . $user['id'] . "_" . time() .".png";
                $profile_picture = $new_profile_picture;
            
                move_uploaded_file($profile_picture_temporary, $upload_directory . $profile_picture);

                // Update gebruikersgegevens met nieuwe profielfoto
                $sql = "UPDATE users
                        SET username = ?, description = ?, profile_picture = ?, private_account = ? 
                        WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sssii", $username, $description, $profile_picture, $private_account, $_SESSION['userid']);
                $stmt->execute();
            } else {
                // Update gebruikersgegevens zonder profielfoto te wijzigen
                $sql = "UPDATE users
                        SET username = ?, description = ?, private_account = ? 
                        WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssii", $username, $description, $private_account, $_SESSION['userid']);
                $stmt->execute();
            }
        }
    }

    // Terugkeren naar de vorige pagina
    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: index.php");
    }
?>
