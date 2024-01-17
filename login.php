<?php
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM test_table_reg WHERE email = ?");
        $stmt->bindParam(1, $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
    
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header("Location: profile.php");
            echo "successful";
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
 <style>
*{

padding: 0;
margin: 0;
box-sizing: border-box;
}

body{
background-color: #05c46b;
font-family: Verdana, Geneva, Tahoma, sans-serif;
}

form
{

max-width: 500px;
margin: 70px auto;

background-color: #fff;


padding: 20px 30px;
}
label{
display: block;
font-family: inherit;
margin:10px 0 10px 0 ;
}

input
{
width: 95%;
border: 1px solid #777;
border-radius: 2px;
padding: 10px;
font-family: inherit;
font-weight: 1px;
}
button
{
display: block;
width: 95%;
margin: 20px 0 20px 0;
background-color: #05c46b;
padding: 10px;
font-family: inherit;
}

img
{
    max-height:150px ;
    width: 100%;
}
</style>
</head>
<body>
    
   
    
    
    <form action="login.php" method="post">
            
<img src="https://images.freecreatives.com/wp-content/uploads/2015/04/logo025.png" alt="Site Logo">
            <label for="email">Email </label>
            <input type="email" name="email" required>

            <label for="password">Password </label>
            <input type="password" name="password" required>


            <button type="submit" >Login</button>
        </form>
    
</body>
</html>
