<?php
 session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("conn.php");
$response = [];




if ($_SERVER['REQUEST_METHOD'] === "POST") {
   
 try{

  

    if (isset($_SESSION["user_id"])) {
     
        $user =  $_SESSION['user_id'];
      
        $stmt = $conn->prepare("SELECT * FROM test_table_reg WHERE id=?");
        $stmt->bindParam(1, $user);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row['id']===$_SESSION["user_id"]){
                $response = ["success" => true, "data" => ["fname"=>$row['fname'],"lname"=>$row['lname'] ,"email"=>$row['email'] , "mobile"=>$row['mno']]];
            } else {
                $response = ["success" => false, "message" => "User Not Found"];
            }
        } else {
            $response = ["success" => false, "message" => "Error executing the query"];
        }
    } else {
        $response = ["success" => false, "message" => "Error: User not authenticated."];
    }


 }
catch(PDOException $e) {
    $response["Error"] = "Error: " . $e->getMessage();
    }


}

else {
    $response = ["success" => false, "message" => "Invalid request method"];
}
// Output JSON response

error_log(json_encode($response));
// header('Content-Type: application/json');
echo json_encode($response);
?>
