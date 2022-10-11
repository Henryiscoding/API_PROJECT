<?php
//login get , post and update

require_once './vendor/autoload.php';

$app = new Slim\App();
$app->GET('/get_login',  function () {
  require_once 'db.php';
  
  $query = "SELECT name , email , password from users";
  $result = $bd_conn->query($query);

    while ($row = $result->fetch_assoc()){
          $data[] = $row;   
    }

    return json_encode($data);
});

$app->PATCH('/get_login_details',  function () {
  require_once('db.php');

  $data = $request->getParsedBody();
  $name = $data['name'];
  $email = $data['email'];
  $password = md5($data['password']);

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
  $result = $bd_conn->query($updateDB);

  while ($row = $result->fetch_assoc()){
    $data[] = $row;  
  }

});

$app->POST('/post_login',  function () {
  require_once 'db.php';

  if($_COOKIE['token'] != null){

    $data = $request->getParsedBody();
    $name = $data['name'];
    $email = $data['email'];
    $password = md5($data['password']);

    $query = "INSERT INTO `users`(`name`, `email`, `password`) VALUES ('$name','$email','$password')";
    if ($bd_conn->query($sql) === TRUE) {
      $last_id = $bd_conn->insert_id;
      return "New record created successfully. Last inserted ID is: " . $last_id;
    } else {
      return "Error: " . $sql . "<br>" . $bd_conn->error;
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
  
    $stmt = $bd_conn->prepare("UPDATE `users` SET `password`='?' WHERE id = '?'");
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
  $query = "SELECT  `product_name`, `product_price`, `product_quantity`, `product_image` FROM `cart` where user_id = $user_id_var";
  $result = $bd_conn->query($query);

  while ($row = $result->fetch_assoc()){
    $data[] = $row;
    $original_qauntity = "cart_product_qauntity";
    $row["product_qauntity"] = $qauntity_int;
    setcookie($cart_qauntity, $qauntity_int, time() + (86400), "/");
  }
  return json_encode($data);
});

$app->POST('/post_cart',  function () {
  require_once 'db.php';
  if($_COOKIE['token'] != null){
    $data = $request->getParsedBody();
    $id = $data['cart_id'];
    $product_id = $data['product_id'];
    $cart_name = $data['cart_product_name'];
    $cart_price = $data['cart_product_price'];
    $cart_qauntity = $data['cart_product_qauntity'];
    $cart_image = $data['cart_product_image'];
    $user_id_var = $_COOKIE['user_id'] ;
    $stmt = $bd_conn->prepare("INSERT INTO `cart`(`cart_id`, `user_id`, `product_id`, `product_name`, `product_price`, `product_quantity`, `product_image`) VALUES ('?','?','?','?','?','?','?')");
    $stmt->bind_param("iiisiis", $id, $user_id_var, $product_id, $cart_name, $cart_price, $cart_qauntity, $cart_image);

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
    $insert_pid = $data['product_id'];
    $user_id_var = $_COOKIE['user_id'] ;
    $query = "UPDATE `cart` SET `product_quantity`='$new_cart_qauntity' WHERE product_id = '$insert_pid' AND user_id = $user_id_var ";
   
    if ($bd_conn->query($sql) === TRUE) {
      return "Record insertd successfully";
    } else {
      return "Error updating record: " . $bd_conn->error;
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

    $query = "DELETE FROM `cart` WHERE 'product_id' = '$delete_pid' AND 'user_id' = '$user_id_var'";
    if ($bd_conn->query($sql) === TRUE) {
      return "Record deleted successfully";
    } else {
      return "Error deleting record: " . $bd_conn->error;
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
    $result = $bd_conn->query($query);

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
    $query = "SELECT  `product_name`, `product_price`, `product_qauntity`, `product_image` FROM `wishlist` WHERE user_id = $user_id_var";
    $result = $bd_conn->query($query);

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
    $product_id = $data['product_id'];
    $wishlist_name = $data['wishlist_product_name'];
    $wishlist_price = $data['wishlist_product_price'];
    $wishlist_qauntity = $data['wishlist_product_qauntity'];
    $wishlist_image = $data['wishlist_product_image'];
    $user_id_var = $_COOKIE['user_id'] ;
    
    $query = "INSERT INTO `wishlist`(`wishlist_id`, `user_id`, `product_id`, `product_name`, `product_price`, `product_qauntity`, `product_image`) VALUES ('$id','$user_id_var','$product_id','$wishlist_name','$wishlist_price','$wishlist_qauntity','$wishlist_image')";
    if ($bd_conn->query($sql) === TRUE) {
      $last_id = $bd_conn->insert_id;
      return "New record created successfully. Last inserted ID is: " . $last_id;
    } else {
      return "Error: " . $sql . "<br>" . $bd_conn->error;
    }
  }
  else {
    header("/get_login_details");
  }
});

$app->run();

?>

