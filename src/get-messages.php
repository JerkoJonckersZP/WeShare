<?php
    session_start();
    require_once 'database/config.php';

    $receiverid = $_GET['receiver'] ?? null;

    // Haal alle berichten op tussen de huidige gebruiker en de geselecteerde ontvanger
    function getMessages($mysqli, $sender, $receiver) {
        $sql = "SELECT messages.message, messages.sender, users.profile_picture, users.username, messages.receiver 
                FROM messages 
                INNER JOIN users ON (users.id = messages.sender)
                WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) 
                ORDER BY sent_at ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iiii", $sender, $receiver, $receiver, $sender);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $messages;
    }

    $messages = getMessages($mysqli, $_SESSION['userid'], $receiverid);

    foreach ($messages as $message) {
        $message_content = htmlspecialchars($message['message']);
        $profile_picture = htmlSpecialchars($message['profile_picture']);
        $username = htmlSpecialchars($message['username']);
        if ($message['sender'] == $_SESSION['userid']) {
            echo "
            <div class='chat chat-end mb-3'>
                <div class='chat-image avatar'>
                    <div class='w-12 rounded-full'>
                        <img src='../public/images/".$profile_picture."'/>
                    </div>
                </div>
                <div class='chat-header'>".$username."</div>
                <div class='chat-bubble break-words'>".$message_content."</div>
            </div>
            ";
        } else {
            echo "
            <div class='chat chat-start mb-3'>
                <div class='chat-image avatar'>
                    <div class='w-12 rounded-full'>
                        <img src='../public/images/".$profile_picture."'/>
                    </div>
                </div>
                <div class='chat-header'>".$username."</div>
                <div class='chat-bubble break-words'>".$message_content."</div>
            </div>
            ";
        }
    }
?>
