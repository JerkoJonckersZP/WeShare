<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'database/config.php';

    if(isset($_SESSION['userid'])) {
        $sql_user = "SELECT * FROM users WHERE ".$_SESSION['userid']."";
        $result_user = $mysqli->query($sql_user);

        $user = $result_user->fetch_assoc();

        if($user['user_type'] == 1) {
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }

    require_once 'components/navbar.php';
?>
<html>
<body>
    <div class="flex max-w-7xl mx-auto items-start">
        <div class="w-1/4">
            <div class="p-3">
                <a href='dashboard.php'>
                    <button class="btn btn-ghost justify-start w-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                        DASHBOARD
                    </button>
                </a>
                <a href='users.php'>
                    <button class="btn btn-ghost justify-start w-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                        USERS
                    </button>
                </a>
                <a href='reports.php'>
                    <button class="btn btn-ghost justify-start w-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5" />
                    </svg>
                        REPORTS
                    </button>
                </a>
            </div>
        </div>
        <div class="w-3/4">
            <div class="p-3">
                <?php
                    $sql = "SELECT users.id, users.username, users.profile_picture, users.email_address, users.created_at, user_types.user_type FROM users INNER JOIN user_types ON (users.user_type = user_types.id) ORDER BY users.user_type DESC, created_at ASC";
                    $result = $mysqli->query($sql);

                    echo '
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Email-Address</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Created at</th>
                    ';

                    if($user['user_type'] == 3) {
                        echo '<th></th>';
                    }

                    echo '
                            </tr>
                        </thead>
                        <tbody>
                    ';

                    while ($row = $result->fetch_assoc()) {
                        $sql_user_types = "SELECT * FROM user_types";
                        $result_user_types = $mysqli->query($sql_user_types);

                        $modal_id = 'change_user_type_modal' . $row['id'];
                
                        echo "
                        <dialog id='$modal_id' class='modal'>
                            <div class='modal-box'>
                                <form method='dialog'>
                                    <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
                                </form>
                                <h3 class='font-bold text-2xl'>CHANGE <span class='text-[#1987ff]'>USERTYPE</span></h3>
                                <div class='form-control w-full'>
                                    <form method='post' action='change-user-type.php'>
                                        <label class='form-control w-full'>
                                            <div class='label'>
                                                <span class='label-text'>Pick new usertype</span>
                                            </div>
                                            <select name='type' id='type' class='select select-bordered uppercase'>
                        ";

                                    while ($user_types = $result_user_types->fetch_assoc()) {
                                        echo "<option value='".$user_types['id']."'>".$user_types['user_type']."</option>";
                                    }

                        echo "
                                                
                                            </select>
                                        </label>
                                        <input type='hidden' name='user' value='".$row['id']."'>
                                        <input type='submit' value='CHANGE USERTYPE' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                        ";

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
                                    <a href="profile.php?user='.$row['id'].'">
                                        <div class="font-bold">'.$row['username'].'</div>
                                    </a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                '.$row['email_address'].'
                            </td>
                            <td class="uppercase text-center">
                                '.$row['user_type'].'
                            </td>
                            <td class="text-center">
                                '.$row['created_at'].'
                            </td>';

                            if($user['user_type'] == 3) {
                                echo '
                                <td>
                                    <button class="btn btn-ghost" onclick="document.getElementById(\'' . $modal_id . '\').showModal()">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                </td>';
                            }

                        echo '
                        </tr>
                        ';
                    }

                    echo '
                        </tbody>
                    </table>
                    ';
                ?>
            </div>
        </div>
    </div>
</body>
</html>