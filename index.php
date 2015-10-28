<?php
define('hostname', 'localhost');
define('user','u560012736_stud');
define('password', '163630');
define('databaseName', 'u560012736_mess');
$connect = mysqli_connect(hostname, user, password, databaseName);

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    createMessage();
}
if($_SERVER["REQUEST_METHOD"]=="GET")
{
    getMessage();
}

function createMessage()
{
$i=0;
$sql;
global $connect;
$data = json_decode(file_get_contents('php://input'), true);
$i = count($data);
if($i ==1)
{
  if($data["all"])
  $sql = "SELECT * FROM `messages`";
  else if($data["user_id"])
   $sql = "SELECT * FROM `messages`WHERE user_id  =\"".$data["user_id"]. "\"";
  else if($data["message_id"])
   $sql = "SELECT * FROM `messages`WHERE message_id =".$data["message_id"];
  else if($data["module_code"])
   $sql = "SELECT * FROM `messages`WHERE module_code =\"".$data["module_code"]. "\"";
   else if($data["module_name"])
   $sql = "SELECT * FROM `messages`WHERE module_name  =\"".$data["module_name"]. "\"";
   else if($data["message_date"])
   $sql = "SELECT * FROM `messages`WHERE message_date  =\"".$data["message_date"]. "\"";
    $result = $connect->query($sql);
    if ($result->num_rows > 0)
    {
     $rows = array();
     while($r = mysqli_fetch_assoc($result)) {
     $rows[] = $r;
    }
    print json_encode($rows);
    } 
    else 
    {
      echo "0 results";
    }
}
else
{
    $user_id = $data["user_id"];
    $user_name = $data["user_name"];
    $message_content = $data["message_content"];
    $message_attach = $data["message_attach"];
    $message_date = $data["message_date"];
    $module_code = $data["module_code"];
    $module_name = $data["module_name"];
    $query = "Insert into messages(user_id, user_name, message_content, message_attach, message_date, module_code, module_name) 
    values ('$user_id','$user_name', '$message_content', '$message_attach', '$message_date', '$module_code', '$module_name')";
    mysqli_query($connect, $query) or die (mysqli_error($connect));
    mysqli_close($connect);
}
}

function getMessage()
{
  global $connect;
  $sql;
  if( $_GET["id"])
  {
    $sql = "SELECT * FROM `messages` WHERE message_id =". $_GET["id"];
  }
  else if($_GET["all"])
  {
    $sql = "SELECT * FROM `messages`";
  }
   else if($_GET["module_code"])
  {
    $sql = "SELECT * FROM `messages`WHERE module_code =". $_GET["module_code"];
  }
   else if($_GET["user_id"])
  {
    $sql = "SELECT * FROM `messages`WHERE user_id =". $_GET["user_id"];
  }
  $result = $connect->query($sql);
    if ($result->num_rows > 0)
    {
      // output data of each row
      while($row = $result->fetch_assoc())
      {
        echo "user_id: ".$row["user_id"]. 
        " user_name: " . $row["user_name"]. 
        " message_content: ". $row["message_content"] .         
        " module_name: ". $row["module_name"];
      }
    } 
    else 
    {
      echo "0 results";
    }
  $connect->close();
}
?>