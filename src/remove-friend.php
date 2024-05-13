<?php
    require_once 'database/config.php';
    session_start();

    if(isset($_SESSION['userid']) && isset($_POST['user_one']) && isset($_POST['user_two'])) {
        $user_one = $_POST['user_one'];
        $user_two = $_POST['user_two'];

        // Voorbereiden van de statement om te controleren of er een vriendschap bestaat
        $sql_check_friendship = "SELECT * FROM friends WHERE (user_one = ? AND user_two = ?) OR (user_one = ? AND user_two = ?)";
        $stmt_check_friendship = $mysqli->prepare($sql_check_friendship);
        $stmt_check_friendship->bind_param("iiii", $user_one, $user_two, $user_two, $user_one);
        $stmt_check_friendship->execute();
        $result_check_friendship = $stmt_check_friendship->get_result();

        if($result_check_friendship->num_rows == 1) {
            // Voorbereiden van de statement om de vriendschap te verwijderen
            $sql_delete_friendship = "DELETE FROM friends WHERE (user_one = ? AND user_two = ?) OR (user_one = ? AND user_two = ?)";
            $stmt_delete_friendship = $mysqli->prepare($sql_delete_friendship);
            $stmt_delete_friendship->bind_param("iiii", $user_one, $user_two, $user_two, $user_one);
            $stmt_delete_friendship->execute();
            $stmt_delete_friendship->close();

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
