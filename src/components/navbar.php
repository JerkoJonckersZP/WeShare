<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/weshare/src/database/config.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_POST['search-button'])) {
        header("Location: search.php?query=".$_POST['query']."");
    }
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.8.1/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>WeShare</title>
</head>
<body class="text-black">
    <div class="p-3">
        <div class="navbar bg-base-100 max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex-1">
                <a href="index.php" class="mr-3 normal-case text-3xl font-extrabold text-[#1987ff]">WE<span class="text-black">SHARE</span></a>
                <div class="form-control ml-3">
                    <form method="post" action="index.php">
                        <div class="form-control">
                            <div class="input-group">
                                <input type="text" placeholder="Search…" name="query" class="input input-bordered w-72"/>
                                <button type="submit" name="search-button" class="btn btn-square bg-[#f2f2f2]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex-none gap-2">
            <?php
                if(isset($_SESSION['userid'])) {
                    $sql = "SELECT * 
                            FROM users 
                            WHERE id = '".$_SESSION['userid']."'";
                    $result = $mysqli->query($sql);

                    $user = $result->fetch_assoc();

                    $sql_friend_request_count = "SELECT COUNT(id) AS count
                                                 FROM friend_requests 
                                                 WHERE receiver = ".$_SESSION['userid']."";
                    $result_friend_request_count = $mysqli->query($sql_friend_request_count);

                    $friend_request = $result_friend_request_count->fetch_assoc();

                    echo '
                    <a href="index.php" class="btn btn-ghost btn-circle">
                    <div class="indicator flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </div>
                    </a>';                    
                            
                    if($friend_request['count'] > 0) {
                        echo '
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                <button class="btn btn-ghost btn-circle">
                                <div class="indicator">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                    </svg>
                                    <span class="text-white badge badge-xs badge-primary indicator-item bg-[#1987ff] border-[#1987ff]">'.$friend_request['count'].'</span>
                                </div>
                                </button>
                            </label>
                            <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-80">
                            ';

                        $sql_friend_requests = "SELECT *, users.username, users.profile_picture 
                                                FROM friend_requests 
                                                INNER JOIN users ON (users.id = friend_requests.requestor)
                                                WHERE receiver = ".$_SESSION['userid']."
                                                ORDER BY requested_at DESC";
                        $result_friend_requests = $mysqli->query($sql_friend_requests);
                        
                        while ($friend_request_information = $result_friend_requests->fetch_assoc()) {
                            echo '
                                <li>
                                    <a href="profile.php?user='.$friend_request_information['requestor'].'">
                                    <div class="flex items-center space-x-3">
                                        <div class="avatar">
                                            <div class="mask mask-squircle w-12 h-12 rounded-full">
                                                <img src="../public/images/'.$friend_request_information['profile_picture'].'"/>
                                            </div>
                                        </div>
                                        <div>
                                            <p><span class="font-bold">'.$friend_request_information['username'].'</span> has sent you a friend request.</p>
                                        </div>
                                    </div>
                                    </a>
                                </li>
                            ';
                        }

                        echo '
                            </ul>
                        </div>
                        ';
                    }
                    
                    echo '
                    <button class="btn btn-ghost btn-circle">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                         </div>
                    </button>
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full">
                                <img src="../public/images/'.$user['profile_picture'].'"/>
                            </div>
                        </label>
                        <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                            <li><a href="profile.php?user='.$_SESSION['userid'].'">Profile</a></li>';

                    if($user['user_type'] == 2 or $user['user_type'] == 3) {
                        echo '
                        <li><a href="dashboard.php">Dashboard</a></li>
                        ';
                    }

                    echo '        
                            <li><a href="sign-out.php">Sign out</a></li>
                        </ul>
                    </div>
                    ';
                } else {
                    echo "
                    <button class='btn' onclick='sign_in_modal.showModal()'>SIGN IN</button>
                    ";
                }
            ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/weshare/src/components/modals.php';
?>

