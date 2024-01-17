<?php
include "conn.php";
$response = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $validNameRegex = "/^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/";

        if (isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST['email'])) {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $mobile = $_POST['mobile'];


            if(empty($fname))
            {

                $response['fname_error'] = 'First name is required';
            }
            elseif (!preg_match($validNameRegex, $_POST['fname'])) {
                $response["fname_error"] = "First Name is not a valid value";
            }


            if(empty($lname))
            {
                $response["lname_error"] = "Last Name is Required";
            }
            elseif (!preg_match($validNameRegex, $_POST['lname'])) {
                $response["lname_error"] = "Last Name is not a valid value";
            }

            if (empty($email)) {
                $response["email_already_registered"] = "Email is Required";
            } 
            elseif(!filter_var($email , FILTER_VALIDATE_EMAIL))
            {
                $response["email_already_registered"] = "Enter a Invalid Email";
            }
            else {
                $stmt = $conn->prepare('SELECT email from test_table_reg where email=?');
                $stmt->bindParam(1, $email);
                $stmt->execute();
                $db_email = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($db_email) {
                    $response["email_already_registered"] = "User already registered";
                }
            }

         if(empty($password))
         {

            $response["password_error"] = "Password is required";
            if(empty($$confirm_password))
            {
               $response["cpassword_error"] = "Confirm Password is required";
            }

         }
          
        else
        {
            if ($password != $confirm_password) {
                $response["password_error"] = "Password and Confirm Password do not match.";
            } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
                $response["password_error"] = "Password must be at least 8 characters long, containing one uppercase, one lowercase, one numeric value, and one special character.";
            }

        }










if(empty($mobile))
{
   $response["mobile_error"] = "Mobile Number is Required";

}
else{  
    $mmobile=preg_replace('/[^0-9]/', '', $mobile);
    
    if (strlen($mmobile) !== 10 ) {
        $response['mobile_error'] = "Invalid Mobile Number";
    }
}

    

            if (empty($response)) {


                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn->prepare("INSERT INTO test_table_reg (fname, lname, email, password, mno) VALUES (?, ?, ?, ?, ?)");
                $stmt->bindParam(1, $fname);
                $stmt->bindParam(2, $lname);
                $stmt->bindParam(3, $email);
                $stmt->bindParam(4, $hashed_password);
                $stmt->bindParam(5, $mobile);

                if ($stmt->execute()) {
                    $response["message"] = "User Registered Successfully";
                    
                } else {
                    $response["message"] = "Error in database insertion";
                }
            }

            echo json_encode($response);
        }
    } catch (PDOException $e) {
        $response["message"] = "Error: " . $e->getMessage();
        echo json_encode($response);
    }
} else {
    $response["message"] = "Error: Invalid request method.";
    echo json_encode($response);
}
?>