<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DiscussNow - Dev Forums</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
    .carousel-item img {
        max-width: 100%;
        max-height: 500px;
        object-fit: cover;
    }
    </style>
</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>

    <?php
        $getId = $_GET['thread_id'];
        $select = "SELECT * FROM threads WHERE thread_id = $getId";
        $query = mysqli_query($conn, $select);
        $row = mysqli_fetch_assoc($query);
        $title = $row['thread_title'];
        $Tdesc = $row['thread_desc']; 
        $thread_user_id = $row['thread_user_id']; // Fetch the user ID of the thread poster

        // Fetch the user email who posted the thread
        $sql = "SELECT user_email FROM users WHERE user_id = $thread_user_id";
        $result = mysqli_query($conn, $sql);
        $row2 = mysqli_fetch_assoc($result);
        $user_email = $row2['user_email']; // Now the user_email variable is set
    ?>


    <?php
        $method = $_SERVER["REQUEST_METHOD"];
        $showAlert = false;
        if($method == "POST"){
            // insert comment into db
            $user_id = $_POST['user_id'];
            $comment = $_POST['comment'];

            // preventing xss attack (eg. <script>alert("hello world")</script>) this can cause error in the website
            $comment = str_replace("<", "&lt;", $comment);
            $comment = str_replace(">", "&gt;", $comment); 

            $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ( '$comment', '$getId', '$user_id', current_timestamp())";
            $result = mysqli_query($conn, $sql);

            $showAlert = true;

            if($showAlert){
                echo '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!!</strong> Your comment Has been Added.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        }
    ?>
    <!-- category container cards -->
    <div class="container my-4">
        <div class="jumbotron bg-dark text-light px-4   ">
            <h1 class="display-4 mx-4"><?php echo $title;?></h1>
            <p class="mx-5"><?php echo $Tdesc;?></p>
            <hr>
            <p class="lead mx-4">Be respectful: Avoid personal insults, harassment, and demeaning or discriminatory
                behavior.
                <br>
                This is peer to peer please do not spam or send any inappropriate comments to the group.
            </p>
            <hr class="my-4">
            <p class="mx-4">This is a peer to peer discussion platform.</p>
            <p class="text-left mx-4 pb-4" style="text-decoration:underline;"><b>Posted By <?php echo $user_email; ?></b></p>
        </div>
    </div>


    <div class="container">
        <h1>Post A Comment</h1>

        <?php
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    ?>
        <!-- form -->
        <form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
            <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a comment here" id="comment"
                    name="comment"></textarea>
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION["user_id"]; ?>">
                <label for="desc">Type Your Comment</label>
            </div>

            <button type="submit" class="btn btn-success mb-4 mt-3">Post Comment</button>
        </form>
    </div>
    <?php } else { ?>
    <div class="container">
        <h4>Please Login To Comment!!</h4>
    </div>

    <?php } ?>

    <div class="container">
        <h1 class="py-2">Discussions</h1>
        <?php
            $getId = $_GET['thread_id'];
            $select = "SELECT * FROM comments WHERE thread_id = $getId";
            $query = mysqli_query($conn, $select);
            $noResult = true;
            while($row = mysqli_fetch_assoc($query)){
            $noResult = false;
            $id =  $row['comment_id'];
            $content =  $row['comment_content'];
            $comment_time =  $row['comment_time'];
            $date = new DateTime($comment_time);
            $formatted_comment_time = $date->format('d/m/Y H:i:s');
            $comment_by = $row['comment_by'];
            $sql = "SELECT user_email FROM users WHERE user_id = '$comment_by'";
            $query2 = mysqli_query($conn, $sql);
            $row2 = mysqli_fetch_assoc($query2);
            $user_email = $row2['user_email'];
        ?>
        <div class="d-flex my-3">
            <div class="flex-shrink-0">
                <img style="width: 54px;" src="images/user.png" style>
            </div>
            <div class="flex-grow-1 ms-3">
                <b>
                    <p class="my-0"><?php echo $user_email;  ?> at <?php echo $formatted_comment_time; ?></p>
                </b>
                <?php echo $content;?>
            </div>
        </div>
        <?php }

        if($noResult){ ?>
        <div class="jumbotron jumbotron-fluid bg-dark text-light p-4 m-2">
            <div class="container">
                <h2 class="display-4">No Threads Found</h2>
                <p class="lead mt-4">Be The First Person To Ask a Question.</p>
            </div>
        </div>
        <?php } ?>
    </div>


    <?php include 'partials/_footer.php';?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
