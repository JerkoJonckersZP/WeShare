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
                        ";
                    } else {
                        echo '
                        <div class="w-2/4">
                            <div class="p-3">
                                <img class="w-24 h-24 rounded-full mb-3 object-cover" src="../public/images/'.$user['profile_picture'].'"/>
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
                                            $report_post_modal_id = 'report_post_modal' . $post['id'];

                                            echo "
                                            <dialog id='$report_post_modal_id' class='modal'>
                                                <div class='modal-box'>
                                                    <form method='dialog'>
                                                        <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
                                                    </form>
                                                    <h3 class='font-bold text-2xl'>REPORT <span class='text-[#1987ff]'>POST</span></h3>
                                                    <div class='form-control w-full'>
                                                        <form method='post' action='report-post.php'>
                                                            <label class='label'>
                                                                <span class='label-text'>What's your reason for reporting?</span>
                                                            </label>
                                                            <textarea class='textarea textarea-bordered h-28 w-full resize-none mb-1' name='reason' placeholder='Please explain why you are reporting this post' maxlength='240' required></textarea>
                                                            <input type='hidden' name='post' value='".$post['id']."'>
                                                            <input type='submit' value='REPORT POST' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                                                        </form>
                                                    </div>
                                                </div>
                                            </dialog>
                                            ";
        
                                            $add_comment_modal_id = 'add_comment_modal_' . $post['id'];
        
                                            echo "
                                            <dialog id='$add_comment_modal_id' class='modal'>
                                                <div class='modal-box'>
                                                    <form method='dialog'>
                                                        <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
                                                    </form>
                                                    <h3 class='font-bold text-2xl'>ADD <span class='text-[#1987ff]'>COMMENT</span></h3>
                                                    <div class='form-control w-full'>
                                                        <form method='post' action='add-comment.php'>
                                                            <label class='label'>
                                                                <span class='label-text'>Ready to share your perspective?</span>
                                                            </label>
                                                            <textarea class='textarea textarea-bordered h-28 w-full resize-none mb-1' name='comment' placeholder='Enter your comment' maxlength='240' required></textarea>
                                                            <input type='hidden' name='post' value='".$post['id']."'>
                                                            <input type='submit' value='ADD COMMENT' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                                                        </form>
                                                    </div>
                                                </div>
                                            </dialog>
                                            ";
        
                                            echo '
                                            <div class="p-3">
                                                <div class="flex items-center justify-between space-x-3 mb-3">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                            <img class="w-full h-full object-cover" src="../public/images/'.$post['profile_picture'].'"/>
                                                        </div>
                                                        <div>
                                                            <p class="font-bold">'.$post['username'].'</p>
                                                            <div class="text-sm opacity-50">'.$post['created_at'].'</div>
                                                        </div>
                                                    </div>
                                                    <div class="ml-auto">
                                                        <div class="dropdown dropdown-end">
                                                            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                                                <button class="btn btn-ghost btn-circle">
                                                                    <div class="indicator">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                                        </svg>                                                                                                           
                                                                    </div>
                                                                </button>
                                                            </label>
                                                            <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                                                                <li><a onclick="document.getElementById(\'' . $report_post_modal_id . '\').showModal()">Report</a></li>                                                    </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="post.php?post='.$post['id'].'"><p class="break-words">'. nl2br($post['message']) .'</p></a>';
                        
                                                if(!empty($post['photo'])) {
                                                    echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post['photo'].'">';
                                                }
                                                
                                                $sql_check_if_liked = "SELECT *
                                                                       FROM liked_posts 
                                                                       WHERE user = '".$_SESSION['userid']."' AND post = '".$post['id']."'";
                                                $result_check_if_liked = $mysqli->query($sql_check_if_liked);
        
                                                $sql_like_count = "SELECT *, COUNT(post) AS number_of_likes
                                                                   FROM liked_posts
                                                                   WHERE post = '".$post['id']."'";
                                                $result_like_count = $mysqli->query($sql_like_count);
        
                                                $like_count = $result_like_count->fetch_assoc();
        
                                                echo '
                                                <div class="flex w-full">
                                                    <div class="w-1/2 text-center">
                                                ';
        
                                                if(mysqli_num_rows($result_check_if_liked) == 1) {
                                                    echo '
                                                    <form method="post" action="unlike-post.php">
                                                        <input type="hidden" name="post" value="'.$post['id'].'">
                                                        <input type="submit" class="mt-3 font-bold hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                                    </form>
                                                    '; 
                                                } else {
                                                    echo '
                                                    <form method="post" action="like-post.php">
                                                        <input type="hidden" name="post" value="'.$post['id'].'">
                                                        <input type="submit" class="mt-3 hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                                    </form>
                                                    '; 
                                                }
        
                                                echo '
                                                    </div>
                                                    <div class="w-1/2 text-center">
                                                        <p class="hover:cursor-pointer mt-3" onclick="document.getElementById(\'' . $add_comment_modal_id . '\').showModal()">Comment</p>
                                                    </div>
                                                ';
        
                                            echo '
                                                </div>
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
                                        $report_post_modal_id = 'report_post_modal' . $post['id'];

                                        echo "
                                        <dialog id='$report_post_modal_id' class='modal'>
                                            <div class='modal-box'>
                                                <form method='dialog'>
                                                    <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
                                                </form>
                                                <h3 class='font-bold text-2xl'>REPORT <span class='text-[#1987ff]'>POST</span></h3>
                                                <div class='form-control w-full'>
                                                    <form method='post' action='report-post.php'>
                                                        <label class='label'>
                                                            <span class='label-text'>What's your reason for reporting?</span>
                                                        </label>
                                                        <textarea class='textarea textarea-bordered h-28 w-full resize-none mb-1' name='reason' placeholder='Please explain why you are reporting this post' maxlength='240' required></textarea>
                                                        <input type='hidden' name='post' value='".$post['id']."'>
                                                        <input type='submit' value='REPORT POST' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                                                    </form>
                                                </div>
                                            </div>
                                        </dialog>
                                        ";
    
                                        $add_comment_modal_id = 'add_comment_modal_' . $post['id'];
    
                                        echo "
                                        <dialog id='$add_comment_modal_id' class='modal'>
                                            <div class='modal-box'>
                                                <form method='dialog'>
                                                    <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
                                                </form>
                                                <h3 class='font-bold text-2xl'>ADD <span class='text-[#1987ff]'>COMMENT</span></h3>
                                                <div class='form-control w-full'>
                                                    <form method='post' action='add-comment.php'>
                                                        <label class='label'>
                                                            <span class='label-text'>Ready to share your perspective?</span>
                                                        </label>
                                                        <textarea class='textarea textarea-bordered h-28 w-full resize-none mb-1' name='comment' placeholder='Enter your comment' maxlength='240' required></textarea>
                                                        <input type='hidden' name='post' value='".$post['id']."'>
                                                        <input type='submit' value='ADD COMMENT' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                                                    </form>
                                                </div>
                                            </div>
                                        </dialog>
                                        ";
    
                                        echo '
                                        <div class="p-3">
                                            <div class="flex items-center justify-between space-x-3 mb-3">
                                                <div class="flex items-center space-x-3">
                                                    <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                        <img class="w-full h-full object-cover" src="../public/images/'.$post['profile_picture'].'"/>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold">'.$post['username'].'</p>
                                                        <div class="text-sm opacity-50">'.$post['created_at'].'</div>
                                                    </div>
                                                </div>
                                                <div class="ml-auto">
                                                    <div class="dropdown dropdown-end">
                                                        <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                                            <button class="btn btn-ghost btn-circle">
                                                                <div class="indicator">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                                    </svg>                                                                                                           
                                                                </div>
                                                            </button>
                                                        </label>
                                                        <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                                                            <li><a onclick="document.getElementById(\'' . $report_post_modal_id . '\').showModal()">Report</a></li>                                                    </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="post.php?post='.$post['id'].'"><p class="break-words">'. nl2br($post['message']) .'</p></a>';
                    
                                            if(!empty($post['photo'])) {
                                                echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post['photo'].'">';
                                            }
                                            
                                            $sql_check_if_liked = "SELECT *
                                                                   FROM liked_posts 
                                                                   WHERE user = '".$_SESSION['userid']."' AND post = '".$post['id']."'";
                                            $result_check_if_liked = $mysqli->query($sql_check_if_liked);
    
                                            $sql_like_count = "SELECT *, COUNT(post) AS number_of_likes
                                                               FROM liked_posts
                                                               WHERE post = '".$post['id']."'";
                                            $result_like_count = $mysqli->query($sql_like_count);
    
                                            $like_count = $result_like_count->fetch_assoc();
    
                                            echo '
                                            <div class="flex w-full">
                                                <div class="w-1/2 text-center">
                                            ';
    
                                            if(mysqli_num_rows($result_check_if_liked) == 1) {
                                                echo '
                                                <form method="post" action="unlike-post.php">
                                                    <input type="hidden" name="post" value="'.$post['id'].'">
                                                    <input type="submit" class="mt-3 font-bold hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                                </form>
                                                '; 
                                            } else {
                                                echo '
                                                <form method="post" action="like-post.php">
                                                    <input type="hidden" name="post" value="'.$post['id'].'">
                                                    <input type="submit" class="mt-3 hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                                </form>
                                                '; 
                                            }
    
                                            echo '
                                                </div>
                                                <div class="w-1/2 text-center">
                                                    <p class="hover:cursor-pointer mt-3" onclick="document.getElementById(\'' . $add_comment_modal_id . '\').showModal()">Comment</p>
                                                </div>
                                            ';
    
                                        echo '
                                            </div>
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
                                    $report_post_modal_id = 'report_post_modal' . $post['id'];

                                    echo "
                                    <dialog id='$report_post_modal_id' class='modal'>
                                        <div class='modal-box'>
                                            <form method='dialog'>
                                                <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
                                            </form>
                                            <h3 class='font-bold text-2xl'>REPORT <span class='text-[#1987ff]'>POST</span></h3>
                                            <div class='form-control w-full'>
                                                <form method='post' action='report-post.php'>
                                                    <label class='label'>
                                                        <span class='label-text'>What's your reason for reporting?</span>
                                                    </label>
                                                    <textarea class='textarea textarea-bordered h-28 w-full resize-none mb-1' name='reason' placeholder='Please explain why you are reporting this post' maxlength='240' required></textarea>
                                                    <input type='hidden' name='post' value='".$post['id']."'>
                                                    <input type='submit' value='REPORT POST' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                    ";

                                    $add_comment_modal_id = 'add_comment_modal_' . $post['id'];

                                    echo "
                                    <dialog id='$add_comment_modal_id' class='modal'>
                                        <div class='modal-box'>
                                            <form method='dialog'>
                                                <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
                                            </form>
                                            <h3 class='font-bold text-2xl'>ADD <span class='text-[#1987ff]'>COMMENT</span></h3>
                                            <div class='form-control w-full'>
                                                <form method='post' action='add-comment.php'>
                                                    <label class='label'>
                                                        <span class='label-text'>Ready to share your perspective?</span>
                                                    </label>
                                                    <textarea class='textarea textarea-bordered h-28 w-full resize-none mb-1' name='comment' placeholder='Enter your comment' maxlength='240' required></textarea>
                                                    <input type='hidden' name='post' value='".$post['id']."'>
                                                    <input type='submit' value='ADD COMMENT' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                    ";

                                    echo '
                                    <div class="p-3">
                                        <div class="flex items-center justify-between space-x-3 mb-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                    <img class="w-full h-full object-cover" src="../public/images/'.$post['profile_picture'].'"/>
                                                </div>
                                                <div>
                                                    <p class="font-bold">'.$post['username'].'</p>
                                                    <div class="text-sm opacity-50">'.$post['created_at'].'</div>
                                                </div>
                                            </div>
                                            <div class="ml-auto">
                                                <div class="dropdown dropdown-end">
                                                    <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                                        <button class="btn btn-ghost btn-circle">
                                                            <div class="indicator">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                                </svg>                                                                                                           
                                                            </div>
                                                        </button>
                                                    </label>
                                                    <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                                                        <li><a onclick="document.getElementById(\'' . $report_post_modal_id . '\').showModal()">Report</a></li>                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="post.php?post='.$post['id'].'"><p class="break-words">'. nl2br($post['message']) .'</p></a>';
                
                                        if(!empty($post['photo'])) {
                                            echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post['photo'].'">';
                                        }
                                        
                                        $sql_check_if_liked = "SELECT *
                                                               FROM liked_posts 
                                                               WHERE user = '".$_SESSION['userid']."' AND post = '".$post['id']."'";
                                        $result_check_if_liked = $mysqli->query($sql_check_if_liked);

                                        $sql_like_count = "SELECT *, COUNT(post) AS number_of_likes
                                                           FROM liked_posts
                                                           WHERE post = '".$post['id']."'";
                                        $result_like_count = $mysqli->query($sql_like_count);

                                        $like_count = $result_like_count->fetch_assoc();

                                        echo '
                                        <div class="flex w-full">
                                            <div class="w-1/2 text-center">
                                        ';

                                        if(mysqli_num_rows($result_check_if_liked) == 1) {
                                            echo '
                                            <form method="post" action="unlike-post.php">
                                                <input type="hidden" name="post" value="'.$post['id'].'">
                                                <input type="submit" class="mt-3 font-bold hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                            </form>
                                            '; 
                                        } else {
                                            echo '
                                            <form method="post" action="like-post.php">
                                                <input type="hidden" name="post" value="'.$post['id'].'">
                                                <input type="submit" class="mt-3 hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                            </form>
                                            '; 
                                        }

                                        echo '
                                            </div>
                                            <div class="w-1/2 text-center">
                                                <p class="hover:cursor-pointer mt-3" onclick="document.getElementById(\'' . $add_comment_modal_id . '\').showModal()">Comment</p>
                                            </div>
                                        ';

                                    echo '
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        } else {
                            if(!($user['private_account'] == 1)) {
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
                                                <img class="w-full h-full object-cover" src="../public/images/'. $post['profile_picture'] .'"/>
                                            </div>
                                            <div>
                                                <p class="font-bold">'.$post['username'].'</p>
                                                <div class="text-sm opacity-50">'.$post['created_at'].'</div>
                                            </div>
                                        </div>
                                        <a href="post.php?post='.$post['id'].'"><p class="break-words">'. nl2br($post['message']) .'</p></a>
                                        ';
        
                                        if(!empty($post['photo'])) {
                                            echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post['photo'].'">';
                                        }
        
                                        $sql_like_count = "SELECT *, COUNT(post) AS number_of_likes
                                                           FROM liked_posts
                                                           WHERE post = '".$post['id']."'";
                                        $result_like_count = $mysqli->query($sql_like_count);

                                        $like_count = $result_like_count->fetch_assoc();

                                        echo '<p class="mt-3 w-full text-center">'.$like_count['number_of_likes'].' Likes</p>';

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
            ?>
            </div>
        </div>
    </div>
</body>
</html>