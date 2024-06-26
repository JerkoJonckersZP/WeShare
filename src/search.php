<?php
    require_once 'components/navbar.php';

    echo "
    <div class='flex max-w-7xl mx-auto items-start'>
        <div class='w-1/4'>
            <div class='p-3'>
            <a href='index.php'>
                <button class='btn btn-ghost justify-start w-full mb-3'>
                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-6 h-6'>
                    <path stroke-linecap='round' stroke-linejoin='round' d='m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'/>
                </svg>
                    HOME
                </button>
            </a>";
            if(isset($_SESSION['userid'])) {
                echo '
                <a href="liked-posts.php">
                    <button class="btn btn-ghost justify-start w-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                        LIKED POSTS
                    </button>
                </a>
                <button class="btn w-full" onclick="create_post_modal.showModal()">CREATE POST</button>
                ';
            }
            
    echo "
        </div>
    </div>
    ";

    if(isset($_GET['query'])) {
        $query = $_GET['query'];
    }

    if(!(empty($query))) {
        // Voorbereiden van de statement om gebruikers te zoeken op basis van de zoekopdracht
        $sql_search_users = "SELECT * FROM users WHERE username LIKE ?";
        $stmt_search_users = $mysqli->prepare($sql_search_users);
        $search_query = "%$query%";
        $stmt_search_users->bind_param("s", $search_query);
        $stmt_search_users->execute();
        $result_search_users = $stmt_search_users->get_result();

        if($result_search_users->num_rows == 0) {
            echo "
            <div class='w-2/4'>
                <div class='p-3'>
                    <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                    <p class='text-center'>It seems that there are no results for the search query you entered.<br>Don't worry, though! Try using a different search query.<br>Happy searching!</p>
                </div>
            </div>";
        } else {
            echo '
            <div class="overflow-x-auto w-2/4">
                <div class="p-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            while ($row = $result_search_users->fetch_assoc()) {
                echo '
                    <tr>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar">
                                <div class="mask mask-squircle w-12 h-12 rounded-full">
                                    <img class="w-full h-full object-cover" src="../public/images/'.$row['profile_picture'].'" alt="'.$row['profile_picture'].'"/>
                                </div>
                                </div>
                                <div>
                                <div class="font-bold">'.$row['username'].'</div>
                                </div>
                            </div>
                        </td>
                        <th class="text-right">
                            <a href="profile.php?user='.$row['id'].'"><button class="btn btn-ghost btn-xs">view profile</button></a>
                        </th>
                    </tr>
                ';
            }

            echo '
                    </tbody>
                </table>
                </div>
            </div>
            ';
        }
    } else {
        echo "
        <div class='w-2/4'>
            <div class='p-3'>
                <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                <p class='text-center'>It looks like you forgot to enter a search query. Please enter a search query in the search bar, and we'll do our best to find what you're looking for.<br>Happy searching!</p>
            </div>
        </div>";
    }

    echo "
    <div class='w-1/4'>
        <div class='p-3'>
    ";

    if(isset($_SESSION['userid'])) {
        // Voorbereiden van de statement om vrienden van de huidige gebruiker op te halen
        $sql_get_friends = "SELECT * FROM friends WHERE user_one = ? OR user_two = ?";
        $stmt_get_friends = $mysqli->prepare($sql_get_friends);
        $stmt_get_friends->bind_param("ii", $_SESSION['userid'], $_SESSION['userid']);
        $stmt_get_friends->execute();
        $result_get_friends = $stmt_get_friends->get_result();

        echo '
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
        ';

        if($result_get_friends->num_rows > 0) {
            while ($row = $result_get_friends->fetch_assoc()) {
                if ($row['user_one'] == $_SESSION['userid']) {
                    $friendid = $row['user_two'];
                } else {
                    $friendid = $row['user_one'];
                }

                // Voorbereiden van de statement om vriendinformatie op te halen
                $sql_friend_information = "SELECT profile_picture, username FROM users WHERE id = ?";
                $stmt_friend_information = $mysqli->prepare($sql_friend_information);
                $stmt_friend_information->bind_param("i", $friendid);
                $stmt_friend_information->execute();
                $result_friend_information = $stmt_friend_information->get_result();

                $friend_information = $result_friend_information->fetch_assoc();

                echo '
                <tr>
                    <td>
                        <div class="flex items-center space-x-3">
                            <div class="avatar">
                            <div class="mask mask-squircle w-12 h-12 rounded-full">
                                <img class="w-full h-full object-cover" src="../public/images/'.$friend_information['profile_picture'].'" alt="'.$friend_information['profile_picture'].'"/>
                            </div>
                            </div>
                            <div>
                            <a href="profile.php?user='.$friendid.'">
                                <div class="font-bold">'.$friend_information['username'].'</div>
                            </a>
                            </div>
                        </div>
                    </td>
                    <th class="text-center">
                        <a href="chat.php?sender='.$_SESSION['userid'].'&receiver='.$friendid.'"><button class="btn btn-ghost btn-xs">chat</button></a>
                    </th>
                </tr>
                ';
            }
        } else {
            echo "
            <tr>
                <td>You've not added any friends yet.</td>
            </tr>
            ";
        
            echo '
                </tbody>
            </table>
            ';
        }
    }

    echo "
            </div>
        </div>
    </div>
    ";
?>
