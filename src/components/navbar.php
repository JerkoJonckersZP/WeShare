<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/weshare/src/database/config.php';

    session_start();
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
        <div class="navbar bg-base-100 max-w-7xl mx-auto flex justify-between items-center rounded-xl mb-3">
            <div class="flex-1">
                <a href="index.php" class="mr-3 normal-case text-3xl font-extrabold text-[#570df8]">WE<span class="text-black">SHARE</span></a>
                <div class="form-control ml-3">
                    <form method="post" action="index.php">
                        <div class="form-control">
                            <div class="input-group">
                                <input type="text" placeholder="Search…" class="input input-bordered w-72"/>
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
                    echo '
                    <a href="index.php" class="btn btn-ghost btn-circle">
                    <div class="indicator flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </div>
                    </a>
                    <button class="btn btn-ghost btn-circle">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="badge badge-xs badge-primary indicator-item bg-[#570df8] border-[#570df8]"></span>
                        </div>
                    </button>
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
                                <img src="../public/images/default.png"/>
                            </div>
                        </label>
                        <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                            <li><a>Profile</a></li>
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
                <!-- Sign In Modal -->
                <dialog id='sign_in_modal' class='modal'>
                    <div class='modal-box'>
                        <form method='dialog'>
                        <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
                        </form>
                        <h3 class='font-bold text-2xl'>SIGN <span class='text-[#570df8]'>IN</span></h3>
                        <p class='mt-3 mb-3'>Don't have an account yet? <span onclick='sign_in_modal.close();sign_up_modal.showModal()' class='underline font-bold hover:cursor-pointer'>Sign Up</span></p>
                        <div class='form-control w-full'>
                            <form method='post' action='sign-in.php'>
                                <label class='label'>
                                    <span class='label-text'>Email Address</span>
                                </label>
                                <input type='email' name="email-address" placeholder='you@example.com' class='input input-bordered w-full' required/>
                                <label class='label'>
                                    <span class='label-text'>Password</span>
                                </label>
                                <input type='password' name="password" placeholder='Enter your password' class='input input-bordered w-full' required/>
                                <input type='submit' value='SIGN IN' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                            </form>
                        </div>
                    </div>
                </dialog>
                <!-- Sign Up Modal -->
                <dialog id='sign_up_modal' class='modal'>
                    <div class='modal-box'>
                        <form method='dialog'>
                            <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
                        </form>
                        <h3 class='font-bold text-2xl'>SIGN <span class='text-[#570df8]'>UP</span></h3>
                        <p class='mt-3 mb-3'>Already have an account? <span onclick='sign_up_modal.close();sign_in_modal.showModal()' class='underline font-bold hover:cursor-pointer'>Sign In</span></p>
                        <div class='form-control w-full'>
                            <form method='post' action='sign-up.php'>
                                <label class='label'>
                                    <span class='label-text'>First name</span>
                                </label>
                                <input type='text' name="first-name" placeholder='Enter your first name' class='input input-bordered w-full' required/>
                                <label class='label'>
                                    <span class='label-text'>Last name</span>
                                </label>
                                <input type='text' name="last-name" placeholder='Enter your last name' class='input input-bordered w-full' required/>
                                <label class='label'>
                                    <span class='label-text'>Email Address</span>
                                </label>
                                <input type='email' name="email-address" placeholder='you@example.com' class='input input-bordered w-full' required/>
                                <label class='label'>
                                    <span class='label-text'>Password</span>
                                </label>
                                <input type='password' name="password" placeholder='Create a password' class='input input-bordered w-full' required/>
                                <input type='submit' value='SIGN UP' class='btn mt-3 bg-[#f2f2f2] w-full'/>
                            </form>
                        </div>
                    </div>
                </dialog>
            </div>
        </div>
    </div>
</body>
</html>

