<?php
    require_once 'components/navbar.php';

    if(isset($_GET['user'])) {
        $sql = "SELECT * 
                FROM users 
                WHERE users.id = '".$_GET['user']."'";
        $result = $mysqli->query($sql);

        $user = $result->fetch_assoc();
    }
?>
<html>
<body>
    <div class="flex max-w-7xl mx-auto items-start">
        <!-- Links -->
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
        <!-- Midden -->
        <?php
            if(empty($_GET['user'])) {
                echo "
                <div class='w-2/4'>
                    <div class='p-3'>
                        <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                        <p class='text-center'>It looks like the user page you're trying to access doesn't exist.<br>No worries, though! Try searching for another user.<br>Happy navigating!</p>
                    </div>
                </div>
                ";
            } else {
                if(isset($_GET['user'])) {
                    $sql = "SELECT * 
                            FROM users
                            WHERE id = '".$_GET['user']."'";
                    $result = $mysqli->query($sql);

                    if(mysqli_num_rows($result) == 0) {
                        echo "
                        <div class='w-2/4'>
                            <div class='p-3'>
                                <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                                <p class='text-center'>It looks like the user page you're trying to access doesn't exist.<br>No worries, though! Try searching for another user.<br>Happy navigating!</p>
                            </div>
                        </div>
                        ";
                    } else {
                        echo '
                        <div class="w-2/4">
                            <div class="p-3">
                                <img class="w-24 h-24 rounded-full mb-3" src="../public/images/'.$user['profile_picture'].'"/>
                                <h2 class="text-3xl font-bold mb-3">'.$user['username'].'</h2>
                                <p class="break-words mb-3">'.nl2br($user['description']).'</p>
                                ';
                                    
                                $sql_post_count = "SELECT COUNT(posts.message) AS number_of_posts 
                                                   FROM posts
                                                   WHERE posts.user = '".$_GET['user']."'";
                                $result_post_count = $mysqli->query($sql_post_count);
                        
                                $post_count = $result_post_count->fetch_assoc();

                                $sql_friend_count = "SELECT COUNT(friends.user_one) AS number_of_friends
                                                     FROM friends
                                                     WHERE friends.user_one = '".$_GET['user']."' OR friends.user_two = '".$_GET['user']."'";
                                $result_friend_count = $mysqli->query($sql_friend_count);

                                $friend_count = $result_friend_count->fetch_assoc();

                        echo '<p><span class="font-bold">'.$post_count['number_of_posts'].'</span> Posts <span class="font-bold">'.$friend_count['number_of_friends'].'</span> Friends</p>';
                                
                        if(isset($_SESSION['userid'])) {
                            if($_GET['user'] == $_SESSION['userid']) {
                                echo '
                                <button class="btn mt-3" onclick="edit_profile_modal.showModal()">EDIT PROFILE</button>
                                ';
                            }

                            if($_GET['user'] != $_SESSION['userid']) {
                                $sql = "SELECT * 
                                        FROM friends 
                                        WHERE user_one = ".$_GET['user']." AND user_two = ".$_SESSION['userid']." OR user_one = ".$_SESSION['userid']." AND user_two = ".$_GET['user']."";
                                $result = $mysqli->query($sql);

                                if (mysqli_num_rows($result) == 0) {
                                    $sql = "SELECT * 
                                        FROM friend_requests 
                                        WHERE requestor = '".$_GET['user']."' AND receiver = '".$_SESSION['userid']."'";
                                    $result = $mysqli->query($sql);

                                    if (mysqli_num_rows($result) == 1) {
                                        echo '
                                        <div class="flex">
                                            <form class="mr-3" method="post" action="accept-friend-request.php">
                                                <input type="hidden" name="requestor" value="'.$_GET['user'].'">
                                                <input type="hidden" name="receiver" value="'.$_SESSION['userid'].'">
                                                <input type="submit" class="btn mt-3 bg-[#f2f2f2]" value="ACCEPT FRIEND REQUEST">
                                            </form>
                                            <form method="post" action="refuse-friend-request.php">
                                                <input type="hidden" name="requestor" value="'.$_GET['user'].'">
                                                <input type="hidden" name="receiver" value="'.$_SESSION['userid'].'">
                                                <input type="submit" class="btn mt-3 bg-[#f2f2f2]" value="REFUSE FRIEND REQUEST">
                                            </form>
                                        </div>
                                        ';
                                    } else {
                                        $sql = "SELECT * 
                                                FROM friend_requests 
                                                WHERE requestor = '".$_SESSION['userid']."' AND receiver = '".$_GET['user']."'";
                                        $result = $mysqli->query($sql);

                                        if (mysqli_num_rows($result) == 1) {
                                            echo '
                                            <form method="post" action="remove-friend-request.php">
                                                <input type="hidden" name="requestor" value="'.$_SESSION['userid'].'">
                                                <input type="hidden" name="receiver" value="'.$_GET['user'].'">
                                                <input type="submit" class="btn mt-3 bg-[#f2f2f2]" value="REMOVE FRIEND REQUEST">
                                            </form>
                                            '; 
                                        } else {
                                            echo '
                                            <form method="post" action="add-friend.php">
                                                <input type="hidden" name="requestor" value="'.$_SESSION['userid'].'">
                                                <input type="hidden" name="receiver" value="'.$_GET['user'].'">
                                                <input type="submit" class="btn mt-3 bg-[#f2f2f2]" value="ADD FRIEND">
                                            </form>
                                            ';
                                        }
                                    }
                                } else {
                                    echo '
                                    <form method="post" action="remove-friend.php">
                                        <input type="hidden" name="user_one" value="'.$_SESSION['userid'].'">
                                        <input type="hidden" name="user_two" value="'.$_GET['user'].'">
                                        <input type="submit" class="btn mt-3 bg-[#f2f2f2]" value="REMOVE FRIEND">
                                    </form>
                                    '; 
                                }
                            }
                        }

                        echo '</div>';

                        if(isset($_SESSION['userid'])) {
                            if(!($_GET['user'] == $_SESSION['userid'])) {
                                if($user['private_account'] == 1) {
                                    $sql = "SELECT * 
                                            FROM friends 
                                            WHERE user_one = '".$_GET['user']."' AND user_two = '".$_SESSION['userid']."' OR user_one = '".$_SESSION['userid']."' AND user_two = '".$_GET['user']."'";
                                    $result = $mysqli->query($sql);
    
                                    if(mysqli_num_rows($result) == 1) {
                                        $sql = "SELECT posts.id, users.profile_picture, users.username, posts.created_at, posts.message, posts.photo
                                                FROM posts
                                                INNER JOIN users ON (posts.user = users.id) 
                                                WHERE users.id = '".$_GET['user']."'
                                                ORDER BY posts.created_at DESC";
                                        $result = $mysqli->query($sql);
    
                                        while ($post = $result->fetch_assoc()) {
                                            echo '
                                            <div class="p-3">
                                                <div class="flex items-center space-x-3 mb-3">
                                                    <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                        <img src="../public/images/'. $post['profile_picture'] .'"/>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold">'.$post['username'].'</p>
                                                        <div class="text-sm opacity-50">'.$post['created_at'].'</div>
                                                    </div>
                                                </div>
                                                <p class="break-words">'. nl2br($post['message']) .'</p>';
                
                                                if(!empty($post['photo'])) {
                                                    echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post['photo'].'">';
                                                }
                
                                            echo '
                                            </div>
                                            ';
                                        }
                                    }
                                } else {
                                    $sql = "SELECT posts.id, users.profile_picture, users.username, posts.created_at, posts.message, posts.photo
                                            FROM posts
                                            INNER JOIN users ON (posts.user = users.id) 
                                            WHERE users.id = '".$_GET['user']."'
                                            ORDER BY posts.created_at DESC";
                                    $result = $mysqli->query($sql);
    
                                    while ($post = $result->fetch_assoc()) {
                                        echo '
                                        <div class="p-3">
                                            <div class="flex items-center space-x-3 mb-3">
                                                <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                    <img src="../public/images/'. $post['profile_picture'] .'"/>
                                                </div>
                                                <div>
                                                    <p class="font-bold">'.$post['username'].'</p>
                                                    <div class="text-sm opacity-50">'.$post['created_at'].'</div>
                                                </div>
                                            </div>
                                            <p class="break-words">'. nl2br($post['message']) .'</p>';
                
                                        if(!empty($post['photo'])) {
                                            echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post['photo'].'">';
                                        }
        
                                    echo '
                                    </div>
                                    ';
                                }
                                }
                            } else {
                                $sql = "SELECT posts.id, users.profile_picture, users.username, posts.created_at, posts.message, posts.photo
                                                FROM posts
                                                INNER JOIN users ON (posts.user = users.id) 
                                                WHERE users.id = '".$_GET['user']."'
                                                ORDER BY posts.created_at DESC";
                                $result = $mysqli->query($sql);
    
                                while ($post = $result->fetch_assoc()) {
                                    echo '
                                    <div class="p-3">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                <img src="../public/images/'. $post['profile_picture'] .'"/>
                                            </div>
                                            <div>
                                                <p class="font-bold">'.$post['username'].'</p>
                                                <div class="text-sm opacity-50">'.$post['created_at'].'</div>
                                            </div>
                                        </div>
                                        <p class="break-words">'. nl2br($post['message']) .'</p>';
                
                                        if(!empty($post['photo'])) {
                                            echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post['photo'].'">';
                                        }
        
                                    echo '
                                    </div>
                                    ';
                                }
                            }
                        } else {
                            if($user['private_account'] == 1) {

                            } else {
                                $sql = "SELECT posts.id, users.profile_picture, users.username, posts.created_at, posts.message, posts.photo
                                        FROM posts
                                        INNER JOIN users ON (posts.user = users.id) 
                                        WHERE users.id = '".$_GET['user']."'
                                        ORDER BY posts.created_at DESC";
                                $result = $mysqli->query($sql);
    
                                while ($post = $result->fetch_assoc()) {
                                    echo '
                                    <div class="p-3">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                <img src="../public/images/'. $post['profile_picture'] .'"/>
                                            </div>
                                            <div>
                                                <p class="font-bold">'.$post['username'].'</p>
                                                <div class="text-sm opacity-50">'.$post['created_at'].'</div>
                                            </div>
                                        </div>
                                        <p class="break-words">'. nl2br($post['message']) .'</p>';
                
                                        if(!empty($post['photo'])) {
                                            echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post['photo'].'">';
                                        }
        
                                    echo '
                                    </div>
                                    ';
                                }
                            }
                        } 
                    }
                }
            }
        ?>
        </div>
        <!-- Rechts -->
        <div class="w-1/4">
        </div>
    </div>
</body>
</html>