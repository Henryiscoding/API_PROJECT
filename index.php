<?php
//login get , post and update

require_once './vendor/autoload.php';

$app = new Slim\App();
$app->GET('/get_login',  function () {
  require_once 'db.php';
  
  $query = "SELECT name , email , password from users";
  $result = $link->query($query);

    while ($row = $result->fetch_assoc()){
          $data[] = $row;   
    }

    return json_encode($data);
});

$app->PATCH('/get_login_details',  function () {
  require_once('db.php');

  $id = '';
  $name = '';
  $email = '';
  $password = md5('');
  $query = "SELECT name , email , password FROM users";

  if ($query->num_rows > 0) {
    // output data of each row
    while($row = $query->fetch_assoc()) {
      $data = $request->getParsedBody();

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

    $query = "SELECT id FROM users WHERE name = $name, email = $email";

    if ($query->num_rows > 0) {
      while($row = $query->fetch_assoc()) {
      $id = $row["id"];
      setcookie("user_id", "$id", time() - 7200);
      }
    }
  }
  setcookie("token", "$name.$surname", time() - 7200);//two hours
  $token_var = $_COOKIE['token'];

  $updateDB = "UPDATE token = $token_var FROM users WHERE name = $name, email = $email";
  $result = $link->query($updateDB);

  while ($row = $result->fetch_assoc()){
    $data[] = $row;  
  }

  return json_encode($data);
});

$app->POST('/post_login',  function () {
  require_once 'db.php';

  if($_COOKIE['token'] != null){

    $data = $request->getParsedBody();
    $name = $data['name'];
    $email = $data['email'];
    $password = md5($data['password']);

    $query = "INSERT INTO `users`(`name`, `email`, `password`) VALUES ('$name','$email','$password')";
    if ($link->query($sql) === TRUE) {
      $last_id = $link->insert_id;
      return "New record created successfully. Last inserted ID is: " . $last_id;
    } else {
      return "Error: " . $sql . "<br>" . $link->error;
    }
  }
  else {
    header("/get_login_details");
  }
});

$app->PATCH('/update_login',  function () {
  require_once 'db.php';

  if($_COOKIE['token'] != null){

    $data = $request->getParsedBody();
    $new_password = md5($data['new_password']);
  
    $stmt = $link->prepare("UPDATE `users` SET `password`='?' WHERE id = '?'");
    $stmt->bind_param('ii', $new_password, $_COOKIE['user_id']);
    
    if(!isset($_COOKIE[token])) {
      return "you are not authorised to complete this action";
    } else {
      $stmt->execute();
      return "password updated";
    }
  }
  else {
    header("/get_login_details");
  }
});
  
//cart get, update , insert and delete

$app->GET('/get_cart',  function () {
  require_once 'db.php';

  $user_id_var = $_COOKIE['user_id'] ;
  $query = "SELECT  `name`, `price`, `quantity`, `image` FROM `cart` where user_id = $user_id_var";
  $result = $link->query($query);

  while ($row = $result->fetch_assoc()){
    $data[] = $row;
    $original_qauntity = "cart_qauntity";
    $row["qauntity"] = $qauntity_int;
    setcookie($cart_qauntity, $qauntity_int, time() + (86400), "/");
  }
  return json_encode($data);
});

$app->POST('/post_cart',  function () {
  require_once 'db.php';
  if($_COOKIE['token'] != null){
    $data = $request->getParsedBody();
    $id = $data['id'];
    $pid = $data['pid'];
    $cart_name = $data['cart_name'];
    $cart_price = $data['cart_price'];
    $cart_qauntity = $data['cart_qauntity'];
    $cart_image = $data['cart_image'];
    $user_id_var = $_COOKIE['user_id'] ;
    $stmt = $link->prepare("INSERT INTO `cart`(`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('?','?','?','?','?','?','?')");
    $stmt->bind_param("iiisiis", $id, $user_id_var, $pid, $cart_name, $cart_price, $cart_qauntity, $cart_image);

    if(( $_COOKIE[$qauntity_int] + $cart_qauntity) < 20){
      $stmt->execute();
      return "New records created successfully";
    }
    else{
      return "you may only have 20 items in your cart";
    }
  }
  else {
    header("/get_login_details");
  }
});

$app->PATCH('/update_cart',  function () {
  require_once 'db.php';

  if($_COOKIE['token'] != null){
    $data = $request->getParsedBody();
    $new_cart_qauntity = $data['new_cart_qauntity'];
    $insert_pid = $data['pid'];
    $user_id_var = $_COOKIE['user_id'] ;
    $query = "UPDATE `cart` SET `quantity`='$new_cart_qauntity' WHERE pid = '$insert_pid' AND user_id = $user_id_var ";
   
    if ($link->query($sql) === TRUE) {
      return "Record insertd successfully";
    } else {
      return "Error updating record: " . $link->error;
    }
  }
  else {
    header("/get_login_details");
  }
});

$app->DELETE('/delete_cart',  function () {
  require_once 'db.php';

  if($_COOKIE['token'] != null){
    $data = $request->getParsedBody();
    $delete_pid = $data['pid'];
    $user_id_var = $_COOKIE['user_id'] ;

    $query = "DELETE FROM `cart` WHERE 'pid' = '$delete_pid' AND 'user_id' = '$user_id_var'";
    if ($link->query($sql) === TRUE) {
      return "Record deleted successfully";
    } else {
      return "Error deleting record: " . $link->error;
    }
  }
  else {
    header("/get_login_details");
  }
});

// get products

$app->GET('/get_products',  function () {
  require_once 'db.php';
  if($_COOKIE['token'] != null){
    $query = "SELECT * from products";
    $result = $link->query($query);

    while ($row = $result->fetch_assoc()){
      $data[] = $row;
    }

    return json_encode($data);
  }
  else {
    header("/get_login_details");
  }
});

//wishlist get and post

$app->GET('/get_wishlist',  function () {
  require_once 'db.php';
  $user_id_var = $_COOKIE['user_id'] ;

  if($_COOKIE['token'] != null){
    $query = "SELECT  `name`, `price`, `qauntity`, `image` FROM `wishlist` WHERE user_id = $user_id_var";
    $result = $link->query($query);

      while ($row = $result->fetch_assoc()){
            $data[] = $row;
      }

      return json_encode($data);
  }
  else {
    header("/get_login_details");
  }
});

$app->POST('/post_wishlist',  function () {
  require_once 'db.php';
  if($_COOKIE['token'] != null){
    $data = $request->getParsedBody();
    $id = $data['id'];
    $pid = $data['pid'];
    $wishlist_name = $data['wishlist_name'];
    $wishlist_price = $data['wishlist_price'];
    $wishlist_qauntity = $data['wishlist_qauntity'];
    $wishlist_image = $data['wishlist_image'];
    $user_id_var = $_COOKIE['user_id'] ;
    
    $query = "INSERT INTO `wishlist`(`id`, `user_id`, `pid`, `name`, `price`, `qauntity`, `image`) VALUES ('$id','$user_id_var','$pid','$wishlist_name','$wishlist_price','$wishlist_qauntity','$wishlist_image')";
    if ($link->query($sql) === TRUE) {
      $last_id = $link->insert_id;
      return "New record created successfully. Last inserted ID is: " . $last_id;
    } else {
      return "Error: " . $sql . "<br>" . $link->error;
    }
  }
  else {
    header("/get_login_details");
  }
});

$app->run();

?>