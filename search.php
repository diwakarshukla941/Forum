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

    #maincontainer {
        min-height: 100vh;
    }
    </style>
</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>

    <div class="container my-3" id="maincontainer">
    <h1 class="py-3">Search results for <em>"<?php echo $_GET['search']?>"</em></h1>


    <?php
        $search = $_GET['search'];
        $noresults = true;
        $select = "SELECT * FROM threads WHERE MATCH (thread_title, thread_desc) AGAINST ('$search')";
         $query = mysqli_query($conn, $select);
         while($row = mysqli_fetch_assoc($query)){
            $title = $row['thread_title'];
            $desc = $row['thread_desc']; 
            $thread_id= $row['thread_id'];
            $url = "thread.php?thread_id=$thread_id";
            $noresults = false;
    ?>


    <!-- Search Results -->
    

        <!-- // Display the search result -->
        <div class="result">
            <h3><a href=" <?php echo $url; ?>" class="text-dark"><?php echo $title;?></a> </h3>
            <p><?php echo $desc;?></p>
        </div>
    </div>

    <?php
        
    }if ($noresults){
            echo '<div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <p class="display-4">No Results Found</p>
                        <p class="lead"> Suggestions: <ul>
                                <li>Make sure that all words are spelled correctly.</li>
                                <li>Try different keywords.</li>
                                <li>Try more general keywords. </li></ul>
                        </p>
                    </div>
                </div>';
        }        
    ?>

    <?php include 'partials/_footer.php';?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>