<?php
session_start();

$serverName = "LAPTOP-H96FD3CI\\SQLEXPRESS";
$connectionOptions = [
    "Database" => "WEBAPP",
    "Uid" => "",
    "PWD" => ""
];
$conn = sqlsrv_connect($serverName, $connectionOptions);

        if ($conn == false) {
        die(print_r(sqlsrv_errors(), true));
        } else {
        }
 $sourceQuery2 = "SELECT * FROM TVFLIX WHERE TVID = (SELECT MAX(TVID) FROM TVFLIX)";
 $results2 = sqlsrv_query($conn, $sourceQuery2);
 if ($results2 === false) {
    die(print_r(sqlsrv_errors(), true));
    }

$error_message = "";
$success_message = "";
$ageErr="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : "";
    $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : "";
    $age = isset($_POST["age"]) ? $_POST["age"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    $repeatPassword = isset($_POST["repeatPassword"]) ? $_POST["repeatPassword"] : "";

   
    if ($ageErr <=17){
        $ageErr="Age must be 18 above";
    }else{
        $age = $_POST["age"];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid Email";
    } elseif ($password !== $repeatPassword) {
        $error_message = "Passwords do not match";
    } else {
        $check_query = "SELECT * FROM USERS WHERE EMAIL='$email'";
        $check_result = sqlsrv_query($conn, $check_query);

        if ($check_result === false) {
            $error_message = "Error checking email availability: " . print_r(sqlsrv_errors(), true);
        } else {
            if (sqlsrv_has_rows($check_result)) {
                $error_message = "Email already taken. Please choose a different one.";
            } else {
                
                    $insert_query = "INSERT INTO TVFLIX (EMAIL, FIRSTNAME, LASTNAME, PASSWORD) 
                    VALUES ('$email', '$firstname', '$lastname' ,'$age','$password')";
                    $insert_result = sqlsrv_query($conn, $insert_query);
                
                if ($insert_result === false) {
                    $error_message = "Error inserting user: " . print_r(sqlsrv_errors(), true);
                } else {
                    $_SESSION["email"] = $email;
                    $_SESSION["firstname"] = $firstname;
                    $success_message = "Registration successful! You can now log in.";
                }

            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="favicon.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <link href= "https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">  
</head>

<body class="h-auto bg-netflix-image bg-cover">
    <div class="absolute top-0 right-0 bottom-0 left-0 bg-black bg-opacity-40 "></div>

<section class="z-20 relative">
    <div class="wrapper text-white flex h-screen items-center justify-around">
        <div class="left flex-grow-0.5"> 
            <div class="middle flex flex-col justify-center items-center h-screen text-white">
                <div class=" p-24 bg-black bg-opacity-70 rounded-lg">
                    
                    <h1 class="text-3xl py-4 font-semibold">Sign Up</h1>
    
                <form action="" class="flex flex-col items-center gap-5 w-full z-30">            
                    <input class="px-4 py-2 bg-stone-800 rounded-sm" type="email" placeholder="Email" name="email">
                    <input class="px-4 py-2  bg-stone-800 rounded-sm" type="text" placeholder="Firstname" name="firstname">
                    <input class="px-4 py-2  bg-stone-800 rounded-sm" type="text" placeholder="Lastname" name="lastname">
                    <input class="px-4 py-2  bg-stone-800 rounded-sm" type="text" placeholder="Age" name="age">
                    <input class="px-4 py-2  bg-stone-800 rounded-sm" type="password" placeholder="Password" name="password">
                    <input class="px-4 py-2  bg-stone-800 rounded-sm" type="password" placeholder="Retype-Password" name="repeatPassword">
                    
                    <button type="submit" class="w-full bg-red-800 px-4 py-2 rounded-md">Register</button>
                    
                    <div class="container flex flex-col justify-between gap-12">
                    <div class="flex gap-10 text-stone-600">
                        <p>Subsribe Now</p>
                        <a  href="https://www.netflix.com/">Need Help ?</a>
                    </div>
                    <div class="flex">
                        <h1 class="text-stone-600 px-1">More to Tvflix ? </h1>
                        <a href="login.html">Log in now</a>
                    </div>
                 </div>
                </form>
             </div>
            </div>
        </div>

        <div class="right flex-grow-1"> 
         
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/Netflix_2015_N_logo.svg/423px-Netflix_2015_N_logo.svg.png" alt="" width="450">
        </div>
    </div>
</section>
    <script type="module" src="/assets/js/main.js"></script>
</body>

</html>