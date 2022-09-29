<?php 

$app = new Slim\App();
$app->get('/get_login',  function () {
  require_once 'db.php';

  $query = "SELECT name , email , password from users";
  $result = $link->query($query);

    while ($row = $result->fetch_assoc()){
          $data[] = $row;
          
    }
  
    return json_encode($data);

    $query = "SELECT id from users where token = $_COOKIE[$token]";
    $user_id = $link->query($query);

    $user_name = "user_id";
    setcookie($user_name, $user_id, time() + (86400), "/");
  
    
});

//possible ?

$app->get('/get_cart',  function () {
  require_once 'db.php';
  
    $query = "SELECT SUM(qauntity) FROM cart where user_id = '$_COOKIE[$user_id]'";
    $result = $link->query($query);
    
    
      if ($result < 30){
        $stmt = $link->prepare("UPDATE `users` SET `password`='?' WHERE id = '?'");
        $stmt->bind_param('ss', $new_password, $_COOKIE[$user_id]);
      }
      else{
        echo "you have to many items in your cart";
      }
  
      return json_encode($data);
  });

$data = $request->getParsedBody();
$id = $data['id'];
$user_id = $_COOKIE[$user_id];
$pid = $data['pid'];
$cart_name = $data['cart_name'];
$cart_price = $data['cart_price'];
$cart_qauntity = $data['cart_qauntity'];
$cart_image = $data['cart_image'];

$stmt = $link->prepare("INSERT INTO `cart`(`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('?','?','?','?','?','?','?')");
$stmt->bind_param("iiisiis", $id, $user_id, $pid, $cart_name, $cart_price, $cart_qauntity, $cart_image);

if((array_sum($data) + $cart_qauntity) < 30){
$stmt->execute();
echo "New records created successfully";
}
else{
  echo "you may only have 30 items in your cart";
}


$app->post('/post_login',  function () {
  require_once 'db.php';

  $data = $request->getParsedBody();
  $name = $data['name'];
  $email = $data['email'];
  $password = $data['password'];
  $token = (rand ( 10000 , 99999 ));
  $token_name = "user_token";
  setcookie($token_name, $token, time() + (86400), "/");


  $query = $query ="INSERT INTO users
  (name, email, password , token)
  VALUES
  ('$name', '$email', '$password', '$_COOKIE[$token]')
  ON DUPLICATE KEY UPDATE
  token = '$_COOKIE[$token]'";
  if ($link->query($sql) === TRUE) {
    $last_id = $link->insert_id;
    echo "New record created successfully. Last inserted ID is: " . $last_id;
  } else {
    echo "Error: " . $sql . "<br>" . $link->error;
  }
});

$data = $request->getParsedBody();
$name = $data['name'];
$email = $data['email'];
$password = $data['password'];
$token = (rand ( 10000 , 99999 ));
$token_name = "user_token";
setcookie($token_name, $token, time() + (86400), "/");

$query ="INSERT INTO users
(name, email, password , token)
VALUES
('$name', '$email', '$password', '$token')
ON DUPLICATE KEY UPDATE
token = '$token'";


$app->insert('/update_login',  function () {
  require_once 'db.php';

  $data = $request->getParsedBody();
  $data = $request->getParsedBody();
  $name = $data['name'];
  $email = $data['email'];
  $password = $data['password'];
  $token = (rand ( 10000 , 99999 ));
  $token_name = "user_token";
  setcookie($token_name, $token, time() + (86400), "/");
  

  $stmt = $link->prepare("INSERT INTO 'users'(name, email, password , token)  VALUES ('?', '?', '?', '?') ON DUPLICATE KEY UPDATE token = '?'");
  $stmt->bind_param('ssiii', $name, $email, $password, $_COOKIE[$token], $_COOKIE[$token]);



  
  $data = $request->getParsedBody();
  $name = $data['name'];
  $email = $data['email'];
  $password = $data['password'];
  $token = (rand ( 10000 , 99999 ));
  $token_name = "user_token";
  setcookie($token_name, $token, time() + (86400), "/");

  $query ="INSERT INTO 'users'(name, email, password , token)  VALUES ('$name', '$email', '$password', '$_COOKIE[$token]') ON DUPLICATE KEY UPDATE token = '$_COOKIE[$token]'";

  if ($link -> affected_rows = 1) {
    $user_id = $link->insert_id;
    echo "New record created successfully" ;
    $user_name = "user_id";
    setcookie($user_name, $last_id, time() + (86400), "/");
    header("/login_user");
  } else if ($link -> affected_rows = 2){
    $user_id = $link->insert_id;
    $user_name = "user_id";
    setcookie($user_name, $user_id, time() + (86400), "/");
    echo "You have been logged in";
    header("/get_products");
  }else {
    if(!isset($_COOKIE[$token_name])) {
      echo "you are not authorised to complete this action";
    } else {
      echo "you are signed in";
      header("/get_products");
    };
  }
  
  
 
});

$app->patch('/get_login_details',  function () {
  require_once('db.php');

  $id = '';
  $name = '';
  $email = '';
  $password = '';

  $query = "SELECT name , email , password FROM users"

  if ($query->num_rows > 0) {
      // output data of each row
      while($row = $query->fetch_assoc()) {
        $data = $request->getParsedBody()

        if($row["name"] === $data['name']){
          $name = $row["name"];
        }
        if($row["email"] === $data['email']){
          $email = $row["email"];
        }
        if($row["password"] === $data['password']){
          $password = $row["password"];
        }
      }
      $query = "SELECT id FROM users WHERE name = $name, email = $email, password = $password"
      if ($query->num_rows > 0) {
        while($row = $query->fetch_assoc()) {
        $id = $row["id"];
        setcookie("user_id", "$id", time() - 7200);
        };
          
  }

  setcookie("token", "$name.$surname", time() - 7200);//two hours

  echo($_COOKIE['token'])//equals $name.$surname"

  //echo out a function of echo $_COOKIE['token']


  $updateDB = "UPDATE token from users WHERE name = $name, email = $email, password = $password, token = $_COOKIE['token']";
});

$app->post(){//add to cart
  if($_COOKIE['token'] != null){





  }
  else {
    header("/get_login_details");
  }
}