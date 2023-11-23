<!-- Sign In Modal -->
<dialog id='sign_in_modal' class='modal'>
    <div class='modal-box'>
        <form method='dialog'>
            <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
        </form>
        <h3 class='font-bold text-2xl'>SIGN <span class='text-[#1a56db]'>IN</span></h3>
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
        <h3 class='font-bold text-2xl'>SIGN <span class='text-[#1a56db]'>UP</span></h3>
        <p class='mt-3 mb-3'>Already have an account? <span onclick='sign_up_modal.close();sign_in_modal.showModal()' class='underline font-bold hover:cursor-pointer'>Sign In</span></p>
        <div class='form-control w-full'>
            <form method='post' action='sign-up.php'>
                <label class='label'>
                    <span class='label-text'>Username</span>
                </label>
                <input type='text' name="username" placeholder='Enter your username' class='input input-bordered w-full' maxlength="15" required/>
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
<!-- Create Post Modal -->
<dialog id='create_post_modal' class='modal'>
    <div class='modal-box'>
        <form method='dialog'>
            <button class='btn btn-sm btn-circle btn-ghost absolute right-2 top-2'>✕</button>
        </form>
        <h3 class='font-bold text-2xl'>CREATE <span class='text-[#1a56db]'>POST</span></h3>
        <div class='form-control w-full'>
            <form method='post' action='create-post.php' enctype="multipart/form-data">
                <label class="label">
                    <span class="label-text">What's on your mind?</span>
                </label>
                <textarea class="textarea textarea-bordered h-28 w-full resize-none mb-1" name="message" placeholder="Enter your message" maxlength="240" required></textarea>
                <label for="photo">
                    <span class="btn w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                        PHOTO
                    </span>
                </label>
                <input type="file" id="photo" name="photo" class="hidden" accept="image/*"/>
                <input type='submit' value='CREATE POST' class='btn mt-3 bg-[#f2f2f2] w-full'/>
            </form>
        </div>
    </div>
</dialog>