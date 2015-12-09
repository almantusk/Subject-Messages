<?php
header('Content-type: text/html; charset=UTF-8') ;
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
    //getMessage();
}

function createMessage()
{
$i=0;
$sql;
global $connect;
$data = json_decode(file_get_contents('php://input'), true);
$i = count($data);
if($i == 1)
{
  if($data["all"])
  $sql = "SELECT * FROM `messages`";
  else if($data["user_id"])
   $sql = "SELECT * FROM `messages`WHERE user_id  =\"".$data["user_id"]. "\"";
  else if($data["message_id"])
   $sql = "SELECT * FROM `messages`WHERE message_id =".$data["message_id"];
  else if($data["module_code"])
   $sql = "SELECT * FROM `messages`WHERE module_code =\"".$data["module_code"]. "\"";
  else if($data["message_date"])
   $sql = "SELECT * FROM `messages`WHERE message_date  =\"".$data["message_date"]. "\"";
  else if($data["lecture_type"])
   $sql = "SELECT * FROM `messages`WHERE lecture_type  =\"".$data["lecture_type"]. "\"";
    $result = $connect->query($sql);
    if ($result->num_rows > 0)
    {
     $rows = array();
     while($r = mysqli_fetch_assoc($result)) {
     $rows[] = $r;
    }
	header('content-type: application/json; charset=utf-8');
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
    } 
    else 
    {
      $rows = array();
      //echo "0 results";
      header('content-type: application/json; charset=utf-8');
      echo json_encode($rows, JSON_UNESCAPED_UNICODE);
    }
}
else if ($i == 2)
{
	if($data["lecture_type"])
	{
		if($data["user_id"])
			$sql = "SELECT * FROM `messages`WHERE user_id  =\"".$data["user_id"]. "\" ";
		else if($data["message_id"])
			$sql = "SELECT * FROM `messages`WHERE message_id =".$data["message_id"];
		else if($data["module_code"])
			$sql = "SELECT * FROM `messages`WHERE module_code =\"".$data["module_code"]. "\" ";
		else if($data["message_date"])
			$sql = "SELECT * FROM `messages`WHERE message_date  =\"".$data["message_date"]. "\" ";
		$sql = $sql."AND lecture_type =\"".$data["lecture_type"]. "\"";
	}
	$result = $connect->query($sql);
    if ($result->num_rows > 0)
    {
     $rows = array();
     while($r = mysqli_fetch_assoc($result)) {
     $rows[] = $r;
    }
	header('content-type: application/json; charset=utf-8');
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
    } 
    else 
    {
      $rows = array();
      //echo "0 results";
      header('content-type: application/json; charset=utf-8');
      echo json_encode($rows, JSON_UNESCAPED_UNICODE);
    }
}
else
{
	$counter = 0;
	if(!$data["user_id"])
	{
		$counter = $counter + 1;
		echo "Warning".$counter.": user id missing..Default user_id value: \"anonym\".\n";
		$user_id = "anonym";
	}
	else
		$user_id = $data["user_id"];
	if(!$data["user_name"])
	{
		$counter = $counter + 1;
		echo "Warning".$counter.": user name missing..Default user_name value: \"anonymous\".\n";
		$user_name = "anonymous";
	}
	else
		$user_name = $data["user_name"];
	if(!$data["message_content"])
	{
		$counter = $counter + 1;
		echo "Warning".$counter.": message content missing..Default message_content value: \"-\".\n";
		$message_content = "-";
	}
	else
		$message_content = $data["message_content"];
	if(!$data["message_attach"])
	{
		$counter = $counter + 1;
		echo "Warning".$counter.": message attach missing..Default message_attach value: \"-\".\n";
		$message_attach = "-";
	}
	else
		$message_attach = $data["message_attach"];
	if(!$data["module_code"])
	{
		$counter = $counter + 1;
		echo "Warning".$counter.": module code missing..Default module_code value: \"P00000\".\n";
		$module_code = "P00000";
	}
	else
		$module_code = $data["module_code"];
	if(!$data["lecture_type"])
	{
		$counter = $counter + 1;
		echo "Warning".$counter.": lecture type missing..Default lecture_type value: \"all\".\n";
		$lecture_type = "general";
	}
	else
		$lecture_type = $data["lecture_type"];
    $query = "Insert into messages(user_id, user_name, message_content, message_attach, module_code, lecture_type) 
    values ('$user_id','$user_name', '$message_content', '$message_attach', '$module_code', '$lecture_type')";
    mysqli_query($connect, $query) or die (mysqli_error($connect));
    mysqli_close($connect);
    echo "\nThe json has been inserted to the \"messages\" databse.\n";
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
        " message_content: ". $row["message_content"];
      }
    } 
    else 
    {
      echo "0 results";
    }
  $connect->close();
}
?>	