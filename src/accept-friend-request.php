<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid'])) {
        if(isset($_POST['requestor']) AND isset($_POST['receiver'])) {
            $requestor = $_POST['requestor'];
            $receiver = $_POST['receiver'];

            $sql_check_request = "SELECT * FROM friend_requests WHERE requestor = ? AND receiver = ?";
            $stmt_check_request = $mysqli->prepare($sql_check_request);
            $stmt_check_request->bind_param("ii", $requestor, $receiver);
            $stmt_check_request->execute();
            $result_check_request = $stmt_check_request->get_result();

            if($result_check_request->num_rows == 1) {
                $sql_insert_friendship = "INSERT INTO friends (user_one, user_two) VALUES (?, ?)";
                $stmt_insert_friendship = $mysqli->prepare($sql_insert_friendship);
                $stmt_insert_friendship->bind_param("ii", $requestor, $receiver);
                $stmt_insert_friendship->execute();

                $sql_delete_request = "DELETE FROM friend_requests WHERE requestor = ? AND receiver = ?";
                $stmt_delete_request = $mysqli->prepare($sql_delete_request);
                $stmt_delete_request->bind_param("ii", $requestor, $receiver);
                $stmt_delete_request->execute();
            }

            $stmt_check_request->close();
            $stmt_insert_friendship->close();
            $stmt_delete_request->close();

            if(isset($_SERVER['HTTP_REFERER'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: index.php");
            }
        }
    }

    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: index.php");
    }
?>
