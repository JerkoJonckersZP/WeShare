<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION['userid'])) {
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
                    $sql = "SELECT * FROM reports WHERE closed = 0 GROUP BY reports.post";
                    $result = $mysqli->query($sql);

                    echo '
                    <div class="stats w-full">
                        <div class="stat">
                            <div class="stat-title">Total Reported Posts</div>
                            <div class="stat-value">'.mysqli_num_rows($result).'</div>
                            <div class="stat-desc underline"><a href="reports.php">View all reported posts</a></div>
                        </div>
                    </div>
                    ';
                ?>
            </div>
        </div>
    </div>
</body>
</html>