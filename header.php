<?php
//login get , post and update
session_start();
$token = (rand ( 10000 , 99999 ));
$_SESSION["token"] = $token;

$app->update('/update_token',  function () {
   require_once 'db.php';

   $stmt = $link->prepare("INSERT INTO `users`(`token`) VALUES ('?')");
   $stmt->bind_param("i", $token);

   $token = (rand ( 10000 , 99999 ));
   $stmt->execute();
});

$app->get('/get_token',  function () {
require_once 'db.php';

  $query = "SELECT id  from users where token = $token";
  $result = $link->query($query);

    while ($row = $result->fetch_assoc()){
          $data[] = $row;
    }

    return json_encode($data);
});

$app->delete('/delete_token',  function () {
  require_once 'db.php'; 
   
  $query = "DELETE FROM `users` WHERE token = '$token'";
  if ($link->query($sql) === TRUE) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . $link->error;
  }
  });
  