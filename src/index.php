<?php
    require_once 'components/navbar.php';
?>
<html>
<body>
    <div class="flex max-w-7xl mx-auto items-start">
        <div class="w-1/4">
            <div class="p-3">
                <a href='index.php'>
                    <button class="btn btn-ghost justify-start w-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                        HOME
                    </button>
                </a>
                <?php
                    if(isset($_SESSION['userid'])) {
                        echo '
                        <button class="btn w-full" onclick="create_post_modal.showModal()">CREATE POST</button>
                        ';
                    }
                ?>
            </div>
        </div>
        <div class="w-2/4">
        </div>
        <div class="w-1/4">
            <div class="p-3">
            <?php
                if(isset($_SESSION['userid'])) {
                    $sql = "SELECT * 
                            FROM friends 
                            WHERE user_one = '".$_SESSION['userid']."' OR user_two = '".$_SESSION['userid']."'";
                    $result = $mysqli->query($sql);

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

                    if(mysqli_num_rows($result) > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if ($row['user_one'] == $_SESSION['userid']) {
                                $friendid = $row['user_two'];
                            } else {
                                $friendid = $row['user_one'];
                            }

                            $sql_friend_information = "SELECT users.profile_picture, users.username 
                                                        FROM users 
                                                        WHERE id = '".$friendid."'";
                            $result_friend_information = $mysqli->query($sql_friend_information);

                            $friend_information = $result_friend_information->fetch_assoc();

                            echo '
                            <tr>
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div class="avatar">
                                        <div class="mask mask-squircle w-12 h-12 rounded-full">
                                            <img src="../public/images/'.$friend_information['profile_picture'].'" alt="'.$friend_information['profile_picture'].'"/>
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
                                    <a href="#"><button class="btn btn-ghost btn-xs">chat</button></a>
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
            ?>
            </div>
        </div>
    </div>
</body>
</html>