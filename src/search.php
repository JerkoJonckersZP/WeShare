<?php
    require_once 'components/navbar.php';

    $query = $_GET['query'];

    if(!(empty($query))) {
        $sql = "SELECT * 
            FROM users 
            WHERE username LIKE '%".$query."%'";
        $result = $mysqli->query($sql);

        if(mysqli_num_rows($result) == 0) {
            echo "
            <div class='w-1/3 mx-auto'>
            <h1 class='text-5xl font-bold mb-3 text-center'>Uh-oh!</h1>
            <p class='text-center'>It seems that there are no results for the search query you entered.<br>Don't worry, though! Try using a different search query.<br>Happy searching!</p>
            </div>";
        } else {
            echo '
            <div class="overflow-x-auto w-1/3 mx-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            while ($row = $result->fetch_assoc()) {
                echo '
                    <tr>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar">
                                <div class="mask mask-squircle w-12 h-12">
                                    <img src="../public/images/'.$row['profile_picture'].'" alt="'.$row['profile_picture'].'"/>
                                </div>
                                </div>
                                <div>
                                <div class="font-bold">'.$row['username'].'</div>
                                </div>
                            </div>
                        </td>
                        <th class="text-center">
                            <button class="btn btn-ghost btn-xs">view profile</button>
                        </th>
                    </tr>
                ';
            }

            echo '
                    </tbody>
                </table>
            </div>
            ';
        }
    } else {
        echo "
        <div class='w-1/3 mx-auto'>
        <h1 class='text-5xl font-bold mb-3 text-center'>Oops!</h1>
        <p class='text-center'>It looks like you forgot to enter a search query. Please enter a search query in the search bar, and we'll do our best to find what you're looking for.<br>Happy searching!</p>
        </div>";
    }
?>