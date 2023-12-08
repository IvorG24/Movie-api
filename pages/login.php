<?php
session_start(); // Start the session at the beginning

$serverName = "LAPTOP-H96FD3CI\\SQLEXPRESS";
$connectionOptions = [
    "Database" => "WEBAPP",
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Initialize error message
$errorMessage = "";

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    // Check if the email exists in the database
    $check_query = "SELECT * FROM TVFLIX WHERE EMAIL='$email'";
    $check_result = sqlsrv_query($conn, $check_query);

    if (sqlsrv_has_rows($check_result)) {
        // Fetch user's data including age
        $user_row = sqlsrv_fetch_array($check_result, SQLSRV_FETCH_ASSOC);
        $user_age = $user_row['AGE'];

        if ($user_age < 18) {
            $errorMessage = "You must be 18 or older to log in.";
        } else {
            // Check if the email and password match
            $login_query = "SELECT * FROM TVFLIX WHERE EMAIL='$email' AND PASSWORD='$password'";
            $login_result = sqlsrv_query($conn, $login_query);

            if (sqlsrv_has_rows($login_result)) {
                // Set session variables on successful login
                $_SESSION["email"] = $email;
                $_SESSION["loggedin"] = true;

                // Redirect to homepage
                header("Location: ../Tvflix/index.html");
                exit();
            } else {
                $errorMessage = "Incorrect email or password.";
            }
        }
    } else {
        $errorMessage = "Email not found.";
    }
}

sqlsrv_close($conn);

// If the user is under 18, prevent them from passing the login
if ($errorMessage) {
    echo $errorMessage;
    // Optionally, you can redirect them to a different page or display a message.
    // For example:
    // header("Location: login.php?error=" . urlencode($errorMessage));
    // exit();
}
?>
