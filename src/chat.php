<?php
    require_once 'components/navbar.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['message']) && isset($_POST['receiver'])) {
            $message = $_POST['message'];
            $receiver = $_POST['receiver'];

            $sql = "INSERT INTO messages (sender, receiver, message) 
                    VALUES ('".$_SESSION['userid']."', '".$receiver."', '".$message."')";
            $result = $mysqli->query($sql);
            
        }
    }
?>
<html>
<body>
    <div class="flex max-w-7xl mx-auto items-start">
        <div class="w-1/4">
        </div>
        <?php
            if(isset($_SESSION['userid'])) {
                if(isset($_GET['receiver']) AND isset($_GET['sender'])) {
                    if($_SESSION['userid'] == $_GET['sender']) {
                        $sql = "SELECT * 
                                FROM friends 
                                WHERE (user_one = '".$_SESSION['userid']."' AND user_two = '".$_GET['receiver']."') OR (user_one = '".$_GET['receiver']."' AND user_two = '".$_SESSION['userid']."')";
                        $result = $mysqli->query($sql);

                        if(mysqli_num_rows($result) > 0) {
                            echo '
                            <div class="w-2/4">
                                <div class="p-3">
                                    <div id="chat-messages" class="max-h-[750px] overflow-y-auto"></div>
                                    <form id="chat-form">
                                        <input type="text" id="message" placeholder="Type here" class="input input-bordered w-full"/>
                                        <input type="hidden" id="receiver" name="receiver" value="'.$_GET['receiver'].'">
                                    </form>
                                </div>
                            </div>
                            ';
                        } else {
                            echo "
                            <div class='w-2/4'>
                                <div class='p-3'>
                                    <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                                    <p class='text-center'>It seems we're not connected as friends yet, so chatting isn't enabled.<br>Feel free to send a friend request if you'd like to chat.<br>Thanks for your understanding!</p>
                                </div>
                            </div>
                            ";
                        }
                    } else {
                        echo "
                        <div class='w-2/4'>
                            <div class='p-3'>
                                <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                                <p class='text-center'>This chat page isn't linked to your account, so chatting isn't possible.<br>Please check your account or try the correct page.<br>Thanks!</p>
                            </div>
                        </div>
                        ";
                    }
                } else {
                    echo "
                    <div class='w-2/4'>
                        <div class='p-3'>
                            <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                            <p class='text-center'> It appears that the necessary parameters haven't been filled in correctly, so chatting isn't available at the moment. Please try again with the correct information.<br>Thank you!</p>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "
                <div class='w-2/4'>
                    <div class='p-3'>
                        <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                        <p class='text-center'>It seems like you're not logged in, so chatting isn't available at the moment.<br>Please log in to start chatting. <br>Thank you!</p>
                    </div>
                </div>
                ";
            }
        ?>
        <div class="w-1/4">
        </div>
    </div>
</body>
</html>
<script>
    let isAutoScrollEnabled = true; // Variabeel om automatisch scrollen in/uit te schakelen

    // Deze functie haalt de berichten op tussen de huidige gebruiker en de geselecteerde ontvanger
    function getMessages() {
        // De ID van de ontvanger wordt opgehaald uit het verborgen veld in het HTML-formulier
        const receiver = document.getElementById('receiver').value;

        // Een nieuw XMLHttpRequest-object wordt aangemaakt
        const xhttp = new XMLHttpRequest();

        // De functie wordt aangeroepen telkens wanneer de status van het XMLHttpRequest-object verandert
        xhttp.onreadystatechange = function () {
            // Als het verzoek is voltooid en de status "OK" is
            if (this.readyState == 4 && this.status == 200) {
                const chatMessages = document.getElementById("chat-messages");
                const shouldScrollToBottom = isAutoScrollEnabled && (chatMessages.scrollTop + chatMessages.clientHeight === chatMessages.scrollHeight);

                // De ontvangen berichten worden toegevoegd aan het chat-venster
                chatMessages.innerHTML = this.responseText;

                // Als automatisch scrollen is ingeschakeld en de gebruiker niet handmatig naar boven scrollt, scroll dan naar beneden
                if (shouldScrollToBottom) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }
        };

        // Het verzoek om berichten op te halen wordt geopend
        xhttp.open("GET", `get-messages.php?receiver=${receiver}`, true);

        // Het verzoek wordt verzonden
        xhttp.send();
    }

    // Deze functie wordt gebruikt om een nieuw bericht te verzenden
    function sendMessage() {
        event.preventDefault();

        const message = document.getElementById('message').value;
        const receiver = document.getElementById('receiver').value;

        const xhttp = new XMLHttpRequest();

        // De functie wordt aangeroepen telkens wanneer de status van het XMLHttpRequest-object verandert
        xhttp.onreadystatechange = function () {
            // Als het verzoek is voltooid en de status "OK" is
            if (this.readyState == 4 && this.status == 200) {
                // Het tekstveld wordt leeggemaakt nadat het bericht is verzonden
                document.getElementById("message").value = '';

                // De functie wordt opnieuw aangeroepen om de chat bij te werken met het nieuwe bericht
                getMessages();
            }
        };

        // Het verzoek om het bericht te verzenden wordt geopend
        xhttp.open("POST", "chat.php", true);

        // De juiste HTTP-header wordt toegevoegd om aan te geven dat het een formulier met URL-gecodeerde gegevens betreft
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        // Het verzoek om het bericht te verzenden wordt verzonden met het bericht en de ontvanger als parameters
        xhttp.send(`message=${message}&receiver=${receiver}`);
    }

    // De functie getMessages wordt onmiddellijk aangeroepen om de chat te laden
    getMessages();

    // De functie getMessages wordt periodiek om de 1000 milliseconden (1 seconde) aangeroepen om de chat up-to-date te houden
    setInterval(getMessages, 1000);

    // Deze event listener zorgt ervoor dat wanneer het formulier wordt verzonden, het bericht wordt verzonden
    document.getElementById("chat-form").addEventListener("submit", function (event) {
        sendMessage();
    });

    // Event listener om bij te houden of de gebruiker handmatig naar boven aan het scrollen is
    document.getElementById("chat-messages").addEventListener("scroll", function() {
        // Als de gebruiker handmatig naar boven scrollt, schakel automatisch scrollen uit
        isAutoScrollEnabled = (this.scrollHeight - this.scrollTop === this.clientHeight);
    });
</script>