<?php
    require_once 'components/navbar.php';

    // Controleer of de gebruiker is ingelogd, anders doorsturen naar de inlogpagina
    if (!isset($_SESSION['userid'])) {
        header("Location: login.php");
        exit;
    }

    // Als de POST request wordt gemaakt, voeg dan het bericht toe aan de database
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['message']) && isset($_POST['receiver'])) {
            $message = $_POST['message'];
            $receiver = $_POST['receiver'];

            $sql = "INSERT INTO messages (sender, receiver, message) 
                    VALUES ('".$_SESSION['userid']."', '".$receiver."', '".$message."')";
            $result = $mysqli->query($sql);
            
        }
    }

    $receiverid = $_GET['receiver'] ?? null;
?>
<html>
<body>
    <div class="flex max-w-7xl mx-auto items-start">
        <div class="w-1/4">
        </div>
        <div class="w-2/4">
            <div class="p-3">
                <div id="chat-messages" class="max-h-[750px] overflow-y-auto"></div>
                <form id="chat-form">
                    <input type="text" id="message" placeholder="Type here" class="input input-bordered w-full"/>
                    <input type="hidden" id="receiver" name="receiver" value="<?= $receiverid ?>">
                </form>
                </div>
        </div>
        <div class="w-1/4">
        </div>
    </div>
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

</body>
</html>
