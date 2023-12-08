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

    // Check if the email and password match
    $login_query = "SELECT * FROM TVFLIX WHERE EMAIL='$email' AND PASSWORD='$password'";
    $login_result = sqlsrv_query($conn, $login_query);

    if (sqlsrv_has_rows($login_result)) {
        // Set session variables on successful login
        $_SESSION["email"] = $email;
        $_SESSION["loggedin"] = true;

        // Redirect to homepage
        header("Location: ../../Pages/Login/homepage.php");
        exit();
    } else {
        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
            header("Location: ../Tvflix/index.html"); // Redirect to login page
            exit;
        }
        $errorMessage = "Incorrect email or password.";
    }


}

sqlsrv_close($conn);
?>