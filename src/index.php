<?php
    require_once 'components/navbar.php';
?>
<html>
<body>
    <div class="flex max-w-7xl mx-auto">
        <div class="w-1/4">
        </div>
        <div class="w-2/4 mr-3 ml-3">
            <?php
                if(isset($_SESSION['userid'])) {
                    echo '<button class="btn w-full mb-3" onclick="create_post_modal.showModal()">CREATE POST</button>';
                }
            ?>
            <div class="p-3">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="mask mask-squircle w-12 h-12 rounded-full">
                        <img src="../public/images/default.png"/>
                    </div>
                    <div>
                        <p class="font-bold">Jerko Jonckers</p>
                        <div class="text-sm opacity-50">14/11/2023</div>
                    </div>
                </div>
                <p class="mb-3">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Non in atque reiciendis eveniet, doloremque incidunt iusto exercitationem est temporibus repellendus nulla odit, deleniti repellat eum reprehenderit, voluptate cum ducimus fugiat.</p>
                <img class="mx-auto" src="../public/images/test.png">
            </div>
        </div>
        <div class="w-1/4">
        </div>
    </div>
</body>
</html>