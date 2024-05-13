<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid']) && isset($_POST['requestor']) && isset($_POST['receiver'])) {
        $requestor = $_POST['requestor'];
        $receiver = $_POST['receiver'];

        // Voorbereiden van de statement om het vriendschapsverzoek te controleren en te verwijderen
        $sql_check_request = "SELECT * FROM friend_requests WHERE requestor = ? AND receiver = ?";
        $stmt_check_request = $mysqli->prepare($sql_check_request);
        $stmt_check_request->bind_param("ii", $requestor, $receiver);
        $stmt_check_request->execute();
        $result_check_request = $stmt_check_request->get_result();

        if($result_check_request->num_rows > 0) {
            // Voorbereiden van de statement om het vriendschapsverzoek te verwijderen
            $sql_delete_request = "DELETE FROM friend_requests WHERE requestor = ? AND receiver = ?";
            $stmt_delete_request = $mysqli->prepare($sql_delete_request);
            $stmt_delete_request->bind_param("ii", $requestor, $receiver);
            $stmt_delete_request->execute();
        }

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
?>
