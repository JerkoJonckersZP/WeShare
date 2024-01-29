<?php
    require_once 'components/navbar.php';

    $sql = "SELECT *, COUNT(posts.message) AS number_of_posts 
            FROM users 
            INNER JOIN posts ON (posts.user = users.id)
            WHERE users.id = '".$_GET['user']."'";
    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
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
            <div class="p-3">
                <img class="w-24 h-24 rounded-full mb-3" src="../public/images/<?= $user['profile_picture'] ?>"/>
                <h2 class="text-3xl font-bold mb-3"><?= $user['username'] ?></h2>
                <p class="break-words mb-3"><?= nl2br($user['description']) ?></p>
                <p><span class="font-bold"><?= $user['number_of_posts'] ?></span> Posts <span class="font-bold">-----</span> Friends</p>
                <?php
                    if(isset($_SESSION['userid'])) {
                        if($_GET['user'] == $_SESSION['userid']) {
                            echo '
                            <button class="btn mt-3" onclick="edit_profile_modal.showModal()">EDIT PROFILE</button>
                            ';
                        }
                    }
                ?>
            </div>
            <?php
                $sql = "SELECT users.private_account, posts.id, users.profile_picture, users.username, posts.created_at, posts.message, posts.photo
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
            ?>
        </div>
        <div class="w-1/4">
        </div>
    </div>
</body>
</html>