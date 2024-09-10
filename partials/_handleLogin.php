<?php
$showError = "false";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';
    $email = $_POST['loginEmail'];
    $pass = $_POST['loginPass'];

    $sql = "SELECT * FROM users WHERE user_email='$email'";
    $result = mysqli_query($conn, $sql);
    $numRows = mysqli_num_rows($result);
    
    if ($numRows == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($pass, $row['user_pass'])) {
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['useremail'] = $email;
            
            // Redirect to the forum index after successful login
            header("Location: /forum/index.php");
            exit(); // Stop further script execution
        } 
    } else {
        header("Location: /forum/index.php");
        exit();
    }
}
?>
