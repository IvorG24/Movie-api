
<?php
session_start();

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
        // Check the age of the user
        $user_row = sqlsrv_fetch_array($login_result, SQLSRV_FETCH_ASSOC);
        $user_age = $user_row['AGE'];

        if ($user_age > 18) {
            $errorMessage = "You must be less than 18 here.";
        } else {
            // Set session variables on successful login
            $_SESSION["email"] = $email;
            $_SESSION["loggedin"] = true;

            // Redirect to homepage
            header("Location: ../Tvflix_minor/index.html");
            exit();
        }
    } else {
        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
            header("Location: ./login_minor.php"); // Redirect to login page
            exit;
        }
        $errorMessage = "Incorrect email or password.";
    }
}

sqlsrv_close($conn);

if ($errorMessage) {
    echo $errorMessage;
    // Optionally, you can redirect them to a different page or display a message.
    // For example:
    // header("Location: login.php?error=" . urlencode($errorMessage));
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href= "https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">  
    <title>Login</title>
</head>

<body class="h-auto bg-netflix-image relative bg-cover overflow-hidden">
    <div class="absolute top-0 right-0 bottom-0 left-0 bg-black bg-opacity-40 "><img  class="w-full h-screen opacity-80" src="https://miro.medium.com/v2/resize:fit:1400/1*5lyavS59mazOFnb55Z6znQ.png" alt=""></div>


    <section class="z-20 relative">   
    <div class="wrapper flex flex-col">
        <div class="top p-4 flex items-center px-6">
            <h1 class=" text-red-600 text-4xl font-bold">TvFlix</h1>
        </div>
        
        <div class="middle flex flex-col justify-center items-center h-screen text-white">
            <div class=" p-20 bg-black bg-opacity-70 rounded-lg border-2">
                
                <h1 class="text-3xl py-4 font-semibold">Sign in For Minor</h1>

            <form action="" method="Post" class="flex flex-col items-center gap-5 w-full z-30">            
                <input class="px-4 py-2 bg-gray-300 rounded-sm text-black" type="email" placeholder="Email" name="email">
                <input class="px-4 py-2  bg-gray-300 rounded-sm text-black" type="password" placeholder="Password" name="password">
                <a href="../Tvflix_minor/index.html" class="w-full"><button type="submit" class="w-full bg-red-800 px-4 py-2 rounded-md">Login</button></a>
                
                <div class="container flex flex-col justify-between gap-12">
                <div class="flex gap-10 text-stone-600">
                    <p>Remember me</p>
                    <a  href="https://www.netflix.com/">Need Help ?</a>
                </div>
                <div class="flex">
                    <h1 class="text-stone-600 px-1">More to Tvflix ? </h1>
                    <a class="text-gray-700" href="register_minor.php"> Sign up now</a>
                </div>
             </div>
            </form>
         </div>
        </div>
      </div>
    </div>
        
    </section>
    <!-- <script type="module" src="../assets/js/main.js"></script> -->
</body>

</html>