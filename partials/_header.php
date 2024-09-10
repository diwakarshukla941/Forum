<?php
    session_start();
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/forum">DiscussNow</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/forum">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../forum/about.php">About</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Categories
                    </a>
                    <ul class="dropdown-menu">
                        <?php 
                        $sql = "SELECT category_name,category_id FROM categories LIMIT 3";
                        $query = mysqli_query($conn,$sql);
                        $noResult = true;
                        while($row = mysqli_fetch_assoc($query)){
                        ?>
                        <li><a class="dropdown-item" href="threadlist.php?catid=<?php echo $row['category_id'];?>"><?php echo $row['category_name'];?></a></li>
                    <?php } ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../forum/contact.php">Contact</a>
                </li>
            </ul>

            <?php
                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
            ?>
            <form class="d-flex form-inline align-items-center" role="search" action="search.php" method="get">
                <input class="form-control me-3" name="search" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-success me-2" type="submit">Search</button>
                <p class="mb-0 text-light" style="white-space: nowrap;">Welcome <?php echo $_SESSION['useremail'];?></p>
                <a href="partials/_logout.php" class="btn btn-outline-success ms-2">Logout</a>
            </form>
            <?php
               }else{
            ?>
            <form class="d-flex form-inline align-items-center" role="search">
                <input class="form-control me-3" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-success me-2" type="submit">Search</button>
            </form>
            <div class="mx-2">
                <button class="btn btn-outline-success" data-bs-toggle="modal"
                    data-bs-target="#loginModal">Login</button>
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#signupModal">Sign
                    Up</button>
            </div>
            <?php }?>

        </div>
    </div>
</nav>

<?php include 'partials/_loginModal.php';?>
<?php include 'partials/_signupModal.php';?>


<?php
        if(isset($_GET['signupsuccess']) && $_GET['signupsuccess'] == "true"){
            echo '<div class="alert alert-success alert-dismissible fade show my-0" role="alert">
                    <strong>Success!!</strong> Your Can Now Login Using Your Email.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
?>