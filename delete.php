<?php
include("conn.php");
$response=[];



// if($_SERVER['REQUEST_METHOD']==='POST')
// {
session_start();
$delete_id=$_SESSION['user_id'];

$stmt=$conn->prepare('DELETE FROM test_table_reg WHERE id=?');
$stmt->bindParam(1,$delete_id);
if($stmt->execute())
{

    $response[]=['success'=>1,'message'=> 'user deleted succesfully'];

}
else
{
    $response[]=['success'=> 0,'message'=> 'user not deleted'];
}




// }
// else
// {
//   $response[]=['success'=> '0','message'=> 'invalid request'];
// }

echo json_encode($response);

?>