<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid'])) {
        if(isset($_POST['requestor'], $_POST['receiver'])) {
            $requestor = $_POST['requestor'];
            $receiver = $_POST['receiver'];

            $sql_check_friends = "SELECT * FROM friends WHERE (user_one = ? AND user_two = ?) OR (user_one = ? AND user_two = ?)";
            $stmt_check_friends = $mysqli->prepare($sql_check_friends);
            $stmt_check_friends->bind_param("iiii", $requestor, $receiver, $receiver, $requestor);
            $stmt_check_friends->execute();
            $result_check_friends = $stmt_check_friends->get_result();

            if($result_check_friends->num_rows == 0) {
                $sql_insert_request = "INSERT INTO friend_requests (requestor, receiver) VALUES (?, ?)";
                $stmt_insert_request = $mysqli->prepare($sql_insert_request);
                $stmt_insert_request->bind_param("ii", $requestor, $receiver);
                $stmt_insert_request->execute();
                $stmt_insert_request->close();

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
    } else {
        if(isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: index.php");
        }
    }
?>
