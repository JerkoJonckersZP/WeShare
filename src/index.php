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
                ?>
            </div>
        </div>
        <div class="w-2/4">
            <?php
                if(isset($_SESSION['userid'])) {
                    $sql = "SELECT * 
                            FROM friends 
                            WHERE user_one = ".$_SESSION['userid']." OR user_two = ".$_SESSION['userid']."";
                    $result = $mysqli->query($sql);

                    if(mysqli_num_rows($result) > 0) {
                        $sql = "SELECT posts.id, posts.message, posts.photo, posts.created_at, posts.user, users.profile_picture, users.username
                                FROM posts
                                JOIN users ON posts.user = users.id
                                WHERE posts.user = ".$_SESSION['userid']." AND posts.deleted = 0
                                OR posts.user IN (
                                    SELECT user_two
                                    FROM friends
                                    WHERE user_one = ".$_SESSION['userid']." AND posts.deleted = 0
                                )
                                OR posts.user IN (
                                    SELECT user_one
                                    FROM friends
                                    WHERE user_two = ".$_SESSION['userid']." AND posts.deleted = 0
                                )
                                ORDER BY posts.created_at DESC";
                        $result = $mysqli->query($sql);

                        if(mysqli_num_rows($result) > 0) {
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
                                                <a href="profile.php?user='.$post['user'].'">
                                                    <p class="font-bold">'.$post['username'].'</p>
                                                </a>
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
                                                <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">';
                                                    
                                                    if($post['user'] == $_SESSION['userid']) {
                                                        echo '
                                                        <li>
                                                            <form method="post" action="delete-post.php" class="flex w-full">
                                                                <input type="hidden" name="post" value="'.$post['id'].'">
                                                                <input type="submit" value="Delete" class="flex-grow text-left hover:cursor-pointer">
                                                            </form>
                                                        </li> 
                                                        ';
                                                    } else {
                                                        echo '
                                                        <li>
                                                            <a onclick="document.getElementById(\'' . $report_post_modal_id . '\').showModal()">Report</a>
                                                        </li>  
                                                        ';
                                                    }   
                                    echo '                                                 
                                                </ul>
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
                        } else {
                            $sql = "SELECT posts.id, posts.user, posts.message, posts.photo, posts.created_at, users.username, users.profile_picture
                                    FROM posts 
                                    INNER JOIN users ON (posts.user = users.id)
                                    WHERE posts.user = ".$_SESSION['userid']." AND posts.deleted = 0
                                    ORDER BY posts.created_at DESC";
                            $result = $mysqli->query($sql);
                            
                            if(mysqli_num_rows($result) > 0) {
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
                                                    <a href="profile.php?user='.$post['user'].'">
                                                        <p class="font-bold">'.$post['username'].'</p>
                                                    </a>
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
                                                    <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">';
                                                        
                                                        if($post['user'] == $_SESSION['userid']) {
                                                            echo '
                                                            <li>
                                                                <form method="post" action="delete-post.php" class="flex w-full">
                                                                    <input type="hidden" name="post" value="'.$post['id'].'">
                                                                    <input type="submit" value="Delete" class="flex-grow text-left hover:cursor-pointer">
                                                                </form>
                                                            </li> 
                                                            ';
                                                        } else {
                                                            echo '
                                                            <li>
                                                                <a onclick="document.getElementById(\'' . $report_post_modal_id . '\').showModal()">Report</a>
                                                            </li>  
                                                            ';
                                                        }   
                                        echo '                                                 
                                                    </ul>
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
                            } else {
                                echo "
                                <div class='p-3'>
                                    <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                                    <p class='text-center'>No posts from you or your friends yet?<br>Hang tight! Once someone shares something, your feed will light up.<br>Keep in touch!</p>
                                </div>
                                ";
                            }
                        }
                    } else {
                        $sql = "SELECT posts.id, posts.user, posts.message, posts.photo, posts.created_at, users.username, users.profile_picture
                                FROM posts 
                                INNER JOIN users ON (posts.user = users.id)
                                WHERE user = ".$_SESSION['userid']." AND posts.deleted = 0
                                ORDER BY posts.created_at DESC";
                        $result = $mysqli->query($sql);
                        
                        if(mysqli_num_rows($result) > 0) {
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
                                                <a href="profile.php?user='.$post['user'].'">
                                                    <p class="font-bold">'.$post['username'].'</p>
                                                </a>
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
                                                <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">';
                                                    
                                                    if($post['user'] == $_SESSION['userid']) {
                                                        echo '
                                                        <li>
                                                            <form method="post" action="delete-post.php" class="flex w-full">
                                                                <input type="hidden" name="post" value="'.$post['id'].'">
                                                                <input type="submit" value="Delete" class="flex-grow text-left hover:cursor-pointer">
                                                            </form>
                                                        </li> 
                                                        ';
                                                    } else {
                                                        echo '
                                                        <li>
                                                            <a onclick="document.getElementById(\'' . $report_post_modal_id . '\').showModal()">Report</a>
                                                        </li>  
                                                        ';
                                                    }   
                                    echo '                                                 
                                                </ul>
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
                        } else {
                            echo "
                            <div class='p-3'>
                                <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                                <p class='text-center'>Looks like your feed is empty. Don't worry, once<br>you start making friends and sharing posts, your feed will come to life.<br>Keep exploring and connecting!</p>
                            </div>
                            ";
                        }
                    }
                } else {
                    echo "
                    <div class='p-3'>
                        <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
                        <p class='text-center'>It seems like you're not logged in, so you can't view any posts at the moment.<br>Please log in to access the content.<br>Thank you!</p>
                    </div>
                    ";
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