<?php
    $showError  = "false";
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include '_dbconnect.php';
        $email = $_POST['signupEmail'];
        $password = $_POST['signup_password'];
        $cpassword = $_POST['signup_cpassword'];

        // Check whether the email already exists in the database
        $existSql = "SELECT * FROM users WHERE user_email='$email'";
        $result = mysqli_query($conn, $existSql);
        $numRows = mysqli_num_rows($result);
        
        if($numRows > 0){
            $showError = "Email Already Exists";
        } else {
            if($password == $cpassword){
                // Hash the password before storing it
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users` (`user_email`, `user_pass`, `timestamp`) VALUES ('$email', '$hash', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                
                if($result){
                    // Redirect to success page if signup is successful
                    header("Location: /forum/index.php?signupsuccess=true");
                    exit();
                } else {
                    $showError = "Signup failed due to server error.";
                }
            } else {
                $showError = "Passwords Don't Match";
            }
        }
        // Redirect back with an error message
        header("Location: /forum/index.php?signupsuccess=false&error=$showError");
    }
?>
