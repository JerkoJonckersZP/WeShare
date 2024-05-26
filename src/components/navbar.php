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
    <link rel="icon" type="image/x-icon" href="../public/images/weshare.png">
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

