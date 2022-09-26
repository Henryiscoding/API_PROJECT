<?php

if(!isset($_COOKIE[$cookie_1_name])) {
  header("/login_user");
} 

require_once './vendor/autoload.php';

$app = new Slim\App();
$app->get('/get_login',  function () {
require_once 'db.php';

  $query = "SELECT id from users where token = $_COOKIE[$token_name]";
  $result = $link->query($query);

    while ($row = $result->fetch_assoc()){
          $data = $user_id;
    }
});

$user_name = "user_id";
setcookie($user_name, $user_id, time() + (86400), "/"); 

$app->patch('/patch_token',  function () {
  require_once 'db.php';

  $token = (rand ( 10000 , 99999 ));
  $token_name = "user_token";
  setcookie($token_name, $token, time() + (86400), "/");

  $stmt = $link->prepare("INSERT INTO `users`(`token`) VALUES ('?') where id = $user_id");
  $stmt->bind_param("i", $token);

  $stmt->execute();
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


if(!isset($_COOKIE[$cookie_1_name])) {
  header("login_user");
} 
?>