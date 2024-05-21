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
            <?php
                if(empty($_GET['post'])) {
                    echo "
                    <div class='p-3'>
                        <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                        <p class='text-center'>Looks like there's been an error. Can't fetch any posts with the parameters provided. Please check and try again.<br>Thanks!</p>
                    </div>
                    ";
                } else {
                    $sql_post_information = "SELECT * 
                                             FROM posts 
                                             WHERE id = ".$_GET['post']."";
                    $result_post_information = $mysqli->query($sql_post_information);

                    if(mysqli_num_rows($result_post_information) > 0) {
                        $post_information = $result_post_information->fetch_assoc();

                        $sql_post_creator_information = "SELECT * 
                                                         FROM users 
                                                         WHERE id = ".$post_information['user']."";
                        $result_post_creator_information = $mysqli->query($sql_post_creator_information);

                        $creator_information = $result_post_creator_information->fetch_assoc();

                        if($creator_information['private_account'] == 0) {
                            $modal_id = 'add_comment_modal_' . $post_information['id'];

                            echo "
                            <dialog id='$modal_id' class='modal'>
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
                                            <input type='hidden' name='post' value='".$post_information['id']."'>
                                            <input type='submit' value='ADD COMMENT' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                            ";

                            echo '
                            <div class="p-3">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="mask mask-squircle w-12 h-12 rounded-full">
                                        <img class="w-full h-full object-cover" src="../public/images/'. $creator_information['profile_picture'] .'"/>
                                    </div>
                                    <div>
                                        <p class="font-bold">'.$creator_information['username'].'</p>
                                        <div class="text-sm opacity-50">'.$post_information['created_at'].'</div>
                                    </div>
                                </div>
                                <p class="break-words">'. nl2br($post_information['message']) .'</p>
                                ';

                            if(!empty($post_information['photo'])) {
                                echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post_information['photo'].'">';
                            }

                            if(isset($_SESSION['userid'])) {
                                $sql_check_if_liked = "SELECT *
                                                    FROM liked_posts 
                                                    WHERE user = '".$_SESSION['userid']."' AND post = '".$post_information['id']."'";
                                $result_check_if_liked = $mysqli->query($sql_check_if_liked);

                                $sql_like_count = "SELECT *, COUNT(post) AS number_of_likes
                                                FROM liked_posts
                                                WHERE post = '".$post_information['id']."'";
                                $result_like_count = $mysqli->query($sql_like_count);

                                $like_count = $result_like_count->fetch_assoc();

                                echo '
                                <div class="flex w-full">
                                    <div class="w-1/2 text-center">
                                ';

                                if(mysqli_num_rows($result_check_if_liked) == 1) {
                                    echo '
                                    <form method="post" action="unlike-post.php">
                                        <input type="hidden" name="post" value="'.$post_information['id'].'">
                                        <input type="submit" class="mt-3 font-bold hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                    </form>
                                    '; 
                                } else {
                                    echo '
                                    <form method="post" action="like-post.php">
                                        <input type="hidden" name="post" value="'.$post_information['id'].'">
                                        <input type="submit" class="mt-3 hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                    </form>
                                    '; 
                                }

                                echo '
                                    </div>
                                    <div class="w-1/2 text-center">
                                        <p class="hover:cursor-pointer mt-3" onclick="document.getElementById(\'' . $modal_id . '\').showModal()">Comment</p>
                                    </div>
                                ';

                                echo '
                                    </div>
                                </div>
                                ';
                            } else {
                                $sql_like_count = "SELECT *, COUNT(post) AS number_of_likes
                                                   FROM liked_posts
                                                   WHERE post = '".$post_information['id']."'";
                                $result_like_count = $mysqli->query($sql_like_count);

                                $like_count = $result_like_count->fetch_assoc();

                                echo '<p class="mt-3 w-full text-center">'.$like_count['number_of_likes'].' Likes</p>
                                </div>';
                            }

                            $sql_comments = "SELECT *, users.username, users.profile_picture 
                                             FROM comments 
                                             INNER JOIN users ON (comments.user = users.id)
                                             WHERE post = ".$post_information['id']."
                                             ORDER BY commented_at DESC";
                            $result_comments = $mysqli->query($sql_comments);

                            if(mysqli_num_rows($result_comments) > 0) {
                                while ($comment = $result_comments->fetch_assoc()) {
                                    echo '
                                    <div class="p-3">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                <img class="w-full h-full object-cover" src="../public/images/'. $comment['profile_picture'] .'"/>
                                            </div>
                                            <div>
                                                <p class="font-bold">'.$comment['username'].'</p>
                                                <div class="text-sm opacity-50">'.$comment['commented_at'].'</div>
                                            </div>
                                        </div>
                                        <p class="break-words">'. nl2br($comment['comment']) .'</p>
                                    </div>
                                    ';
                                }
                            }
                        } else {
                            if(isset($_SESSION['userid'])) {
                                if($_SESSION['userid'] == $creator_information['id']) {
                                    $post_information = $result_post_information->fetch_assoc();

                                    $sql_post_creator_information = "SELECT * 
                                                                     FROM users 
                                                                     WHERE id = ".$post_information['user']."";
                                    $result_post_creator_information = $mysqli->query($sql_post_creator_information);
            
                                    $creator_information = $result_post_creator_information->fetch_assoc();
            
                                    if($creator_information['private_account'] == 0) {
                                        $modal_id = 'add_comment_modal_' . $post_information['id'];
            
                                        echo "
                                        <dialog id='$modal_id' class='modal'>
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
                                                        <input type='hidden' name='post' value='".$post_information['id']."'>
                                                        <input type='submit' value='ADD COMMENT' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                                                    </form>
                                                </div>
                                            </div>
                                        </dialog>
                                        ";
            
                                        echo '
                                        <div class="p-3">
                                            <div class="flex items-center space-x-3 mb-3">
                                                <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                    <img class="w-full h-full object-cover" src="../public/images/'. $creator_information['profile_picture'] .'"/>
                                                </div>
                                                <div>
                                                    <p class="font-bold">'.$creator_information['username'].'</p>
                                                    <div class="text-sm opacity-50">'.$post_information['created_at'].'</div>
                                                </div>
                                            </div>
                                            <p class="break-words">'. nl2br($post_information['message']) .'</p>
                                            ';
            
                                        if(!empty($post_information['photo'])) {
                                            echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post_information['photo'].'">';
                                        }
            
                                        $sql_check_if_liked = "SELECT *
                                                               FROM liked_posts 
                                                               WHERE user = '".$_SESSION['userid']."' AND post = '".$post_information['id']."'";
                                        $result_check_if_liked = $mysqli->query($sql_check_if_liked);
            
                                        $sql_like_count = "SELECT *, COUNT(post) AS number_of_likes
                                                           FROM liked_posts
                                                           WHERE post = '".$post_information['id']."'";
                                        $result_like_count = $mysqli->query($sql_like_count);
            
                                        $like_count = $result_like_count->fetch_assoc();
            
                                        echo '
                                        <div class="flex w-full">
                                            <div class="w-1/2 text-center">
                                        ';
            
                                        if(mysqli_num_rows($result_check_if_liked) == 1) {
                                            echo '
                                            <form method="post" action="unlike-post.php">
                                                <input type="hidden" name="post" value="'.$post_information['id'].'">
                                                <input type="submit" class="mt-3 font-bold hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                            </form>
                                            '; 
                                        } else {
                                            echo '
                                            <form method="post" action="like-post.php">
                                                <input type="hidden" name="post" value="'.$post_information['id'].'">
                                                <input type="submit" class="mt-3 hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                            </form>
                                            '; 
                                        }
            
                                        echo '
                                            </div>
                                            <div class="w-1/2 text-center">
                                                <p class="hover:cursor-pointer mt-3" onclick="document.getElementById(\'' . $modal_id . '\').showModal()">Comment</p>
                                            </div>
                                        ';
            
                                        echo '
                                            </div>
                                        </div>
                                        ';
            
                                        $sql_comments = "SELECT *, users.username, users.profile_picture 
                                                         FROM comments 
                                                         INNER JOIN users ON (comments.user = users.id)
                                                         WHERE post = ".$post_information['id']."
                                                         ORDER BY commented_at DESC";
                                        $result_comments = $mysqli->query($sql_comments);
            
                                        if(mysqli_num_rows($result_comments) > 0) {
                                            while ($comment = $result_comments->fetch_assoc()) {
                                                echo '
                                                <div class="p-3">
                                                    <div class="flex items-center space-x-3 mb-3">
                                                        <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                            <img class="w-full h-full object-cover" src="../public/images/'. $comment['profile_picture'] .'"/>
                                                        </div>
                                                        <div>
                                                            <p class="font-bold">'.$comment['username'].'</p>
                                                            <div class="text-sm opacity-50">'.$comment['commented_at'].'</div>
                                                        </div>
                                                    </div>
                                                    <p class="break-words">'. nl2br($comment['comment']) .'</p>
                                                </div>
                                                ';
                                            }
                                        }
                                    }
                                } else {
                                    $sql_friend_check = "SELECT * 
                                                         FROM friends 
                                                         WHERE user_one = ".$_SESSION['userid']." AND user_two = ".$creator_information['id']." OR user_one = ".$creator_information['id']." AND user_two = ".$_SESSION['userid']."";
                                    $result_friend_check = $mysqli->query($sql_friend_check);

                                    if(mysqli_num_rows($result_friend_check) == 1) {
                                        $post_information = $result_post_information->fetch_assoc();

                                        $sql_post_creator_information = "SELECT * 
                                                                         FROM users 
                                                                         WHERE id = ".$post_information['user']."";
                                        $result_post_creator_information = $mysqli->query($sql_post_creator_information);
                
                                        $creator_information = $result_post_creator_information->fetch_assoc();
                
                                        if($creator_information['private_account'] == 0) {
                                            $modal_id = 'add_comment_modal_' . $post_information['id'];
                
                                            echo "
                                            <dialog id='$modal_id' class='modal'>
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
                                                            <input type='hidden' name='post' value='".$post_information['id']."'>
                                                            <input type='submit' value='ADD COMMENT' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                                                        </form>
                                                    </div>
                                                </div>
                                            </dialog>
                                            ";
                
                                            echo '
                                            <div class="p-3">
                                                <div class="flex items-center space-x-3 mb-3">
                                                    <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                        <img class="w-full h-full object-cover" src="../public/images/'. $creator_information['profile_picture'] .'"/>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold">'.$creator_information['username'].'</p>
                                                        <div class="text-sm opacity-50">'.$post_information['created_at'].'</div>
                                                    </div>
                                                </div>
                                                <p class="break-words">'. nl2br($post_information['message']) .'</p>
                                                ';
                
                                            if(!empty($post_information['photo'])) {
                                                echo '<img class="mx-auto w-full mt-3" src="../public/images/'.$post_information['photo'].'">';
                                            }
                
                                            $sql_check_if_liked = "SELECT *
                                                                   FROM liked_posts 
                                                                   WHERE user = '".$_SESSION['userid']."' AND post = '".$post_information['id']."'";
                                            $result_check_if_liked = $mysqli->query($sql_check_if_liked);
                
                                            $sql_like_count = "SELECT *, COUNT(post) AS number_of_likes
                                                               FROM liked_posts
                                                               WHERE post = '".$post_information['id']."'";
                                            $result_like_count = $mysqli->query($sql_like_count);
                
                                            $like_count = $result_like_count->fetch_assoc();
                
                                            echo '
                                            <div class="flex w-full">
                                                <div class="w-1/2 text-center">
                                            ';
                
                                            if(mysqli_num_rows($result_check_if_liked) == 1) {
                                                echo '
                                                <form method="post" action="unlike-post.php">
                                                    <input type="hidden" name="post" value="'.$post_information['id'].'">
                                                    <input type="submit" class="mt-3 font-bold hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                                </form>
                                                '; 
                                            } else {
                                                echo '
                                                <form method="post" action="like-post.php">
                                                    <input type="hidden" name="post" value="'.$post_information['id'].'">
                                                    <input type="submit" class="mt-3 hover:cursor-pointer" value="'.$like_count['number_of_likes'].' Likes">
                                                </form>
                                                '; 
                                            }
                
                                            echo '
                                                </div>
                                                <div class="w-1/2 text-center">
                                                    <p class="hover:cursor-pointer mt-3" onclick="document.getElementById(\'' . $modal_id . '\').showModal()">Comment</p>
                                                </div>
                                            ';
                
                                            echo '
                                                </div>
                                            </div>
                                            ';
                
                                            $sql_comments = "SELECT *, users.username, users.profile_picture 
                                                             FROM comments 
                                                             INNER JOIN users ON (comments.user = users.id)
                                                             WHERE post = ".$post_information['id']."
                                                             ORDER BY commented_at DESC";
                                            $result_comments = $mysqli->query($sql_comments);
                
                                            if(mysqli_num_rows($result_comments) > 0) {
                                                while ($comment = $result_comments->fetch_assoc()) {
                                                    echo '
                                                    <div class="p-3">
                                                        <div class="flex items-center space-x-3 mb-3">
                                                            <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                                <img class="w-full h-full object-cover" src="../public/images/'. $comment['profile_picture'] .'"/>
                                                            </div>
                                                            <div>
                                                                <p class="font-bold">'.$comment['username'].'</p>
                                                                <div class="text-sm opacity-50">'.$comment['commented_at'].'</div>
                                                            </div>
                                                        </div>
                                                        <p class="break-words">'. nl2br($comment['comment']) .'</p>
                                                    </div>
                                                    ';
                                                }
                                            }
                                        }
                                    } else {
                                        echo "
                                        <div class='p-3'>
                                            <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                                            <p class='text-center'>This post is from a private account, and you're not connected with the creator. Consider sending a friend request if you'd like to view their content.<br>Thanks!</p>
                                        </div>
                                        ";
                                    }
                                }
                            } else {
                                echo "
                                <div class='p-3'>
                                    <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                                    <p class='text-center'>This post is from a private account.<br>Please log in and ensure you're friends with the creator to view it.<br>Thank you!</p>
                                </div>
                                ";
                            }
                        }
                    } else {
                        echo "
                        <div class='p-3'>
                            <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                            <p class='text-center'>Looks like there's been an error. Can't fetch any posts with the parameters provided. Please check and try again.<br>Thanks!</p>
                        </div>
                        ";
                    }
                }
            ?>
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