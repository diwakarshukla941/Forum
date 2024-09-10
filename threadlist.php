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
        $getId = $_GET['catid'];
        $select = "SELECT * FROM categories WHERE category_id =$getId ";
        $query = mysqli_query($conn,$select);
        $row = mysqli_fetch_assoc($query);
        $name =  $row['category_name'];
        $desc =  $row['category_description']; 

    ?>

    <?php
            $method = $_SERVER["REQUEST_METHOD"];
            $showAlert = false;
            if($method == "POST"){
                // insert thread into db
                $user_id = $_POST['user_id']; 
                $th_title = $_POST['title'];
                $th_desc = $_POST['desc'];

            // preventing xss attack (eg. <script>alert("hello world")</script>) this can cause error in the website

                $th_title = str_replace("<", "&lt;", $th_title);
                $th_title = str_replace(">", "&gt;", $th_title); 

                $th_desc = str_replace("<", "&lt;", $th_desc);
                $th_desc = str_replace(">", "&gt;", $th_desc); 
                $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ( '$th_title', '$th_desc', '$getId', '$user_id', current_timestamp())";
                $result = mysqli_query($conn, $sql);

                $showAlert = true;

                if($showAlert){
                    echo '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!!</strong> Your Thread Has been Added.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
            }
        ?>

    <!-- category cintainer cards -->
    <div class="container my-4">
        <div class="jumbotron bg-dark text-light px-4   ">
            <h1 class="display-4 mx-4">Welcome to <?php echo $name;?> Forums</h1>
            <p><?php echo $desc;?></p>
            <hr>
            <p class="lead mx-4">Be respectful: Avoid personal insults, harassment, and demeaning or discriminatory
                behavior.
                <br>
                Stay on topic: Keep your posts relevant to the thread topic.
                <br>
                Be considerate: Don't post personal information about other participants, or anything that could harm
                their reputation.
                <br>
                Be legal: Don't post anything illegal, defamatory, or in violation of intellectual property or antitrust
                laws.
                <br>
                Be authentic: Don't disrupt other users or network services.
                <br>
                Be clear: Include a clear topic title in your post, and provide sources when sharing information.
            </p>
            <hr class="my-4">
            <p class="mx-4">This is a peer to peer </p>
            <a href="#" class="btn btn-success btn-lg  m-4" role="button">learn More</a>
        </div>
    </div>

    <?php
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    ?>
    <div class="container">
        <h1>Ask a question</h1>
        <!-- form -->
        <form action="<?php echo  $_SERVER['REQUEST_URI']?>" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Thread Title</label>
                <input type="text" class="form-control" id="title" aria-describedby="title" name="title">
            </div>
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION["user_id"]; ?>">
            <div id="title" class="form-text">Keep Your Title clear and short.</div>
            <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a comment here" id="desc" name="desc"></textarea>
                <label for="desc">Elaborate Your Problem</label>
            </div>

            <button type="submit" class="btn btn-success mb-4 mt-3">Submit</button>
        </form>
    </div>

    <?php } 
        else {    ?>
    <div class="container">
        <h1>Ask a question</h1>
        <h4>Please Login To Post!!</h4>
    </div>


    <?php } ?>

    <div class="container">
        <h1 class="py-2">Browse Questions</h1>

        <?php
            $getId = $_GET['catid'];
            $select = "SELECT * FROM threads WHERE thread_cat_id =$getId ";
            $query = mysqli_query($conn,$select);
            $noResult = true;
            while($row = mysqli_fetch_assoc($query)){
            $noResult = false;
            $id =  $row['thread_id'];
            $title =  $row['thread_title'];
            $Tdesc =  $row['thread_desc']; 
            $thread_user_id = $row['thread_user_id'];
            $sql = "SELECT user_email FROM users WHERE user_id = '$thread_user_id'";
            $query2 = mysqli_query($conn, $sql);
            
            if ($query2) {
                $row2 = mysqli_fetch_assoc($query2);
                if ($row2) {
                    $user_email = $row2['user_email'];
                } else {
                    $user_email = 'Email not found'; // Default value or handle as needed
                }
            } else {
                $user_email = 'Query failed'; // Handle query failure
            }
        ?>
        <div class="d-flex my-3">
            <div class="flex-shrink-0">
                <img style="width: 54px;" src="images/user.png" style>
            </div>
            <div class="flex-grow-1 ms-3">
                <h5 class="mt-0"><a href="./thread.php?thread_id=<?php echo $id;?>" class="text-dark">
                        <?php echo $title;?></a></h5>
                <?php echo $Tdesc;?>
            </div>
            Asked By:- <br> <?php echo $user_email ?>
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