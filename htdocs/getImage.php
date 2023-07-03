<?php
  $mysqli = mysqli_connect("localhost", "root", "", "mtgstats");
  $result = $mysqli->query("SELECT IMAGE FROM PLAYERS WHERE PLAYERID = $_GET[playerid]");
  $row = $result->fetch_assoc();

  if(!$row) {
    $mysqli->close();
    http_response_code(404);
    return;
  }
  
  header("Content-Type: image/jpeg");

  if($row['IMAGE']) echo $row['IMAGE'];
  else http_response_code(404);

  $mysqli->close();
?>